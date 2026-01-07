<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction as TransactionModel;
use App\Models\TransactionDetail;
use App\Models\Product;
use FedaPay\FedaPay;
use FedaPay\Transaction;

class PaymentController extends Controller
{
    /**
     * Handle the payment callback from FedaPay.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleCallback(Request $request)
    {
        Log::info('Payment Callback Received', $request->all());

        $data = $request->all();
        $entity = $data['entity'] ?? [];
        $transactionId = $entity['id'] ?? null;

        if (!$transactionId) {
            Log::error('Callback received without Transaction ID');
            return response()->json(['status' => 'error', 'message' => 'No Transaction ID'], 400);
        }

        // Configure FedaPay
        // WARNING: Ensure FEDAPAY_SECRET_KEY is set in your .env file
        FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY', 'sk_sandbox_YOUR_KEY_HERE')); 
        FedaPay::setEnvironment(env('FEDAPAY_ENVIRONMENT', 'sandbox'));

        try {
            // Retrieve Transaction from FedaPay to verify status
            $transaction = Transaction::retrieve($transactionId);
            $status = $transaction->status;

            if ($status === 'approved') {
                // Extract Metadata
                $customMetadata = $entity['custom_metadata'] ?? [];
                $clientName = $customMetadata['customer_name'] ?? 'Unknown';
                $clientPhone = $customMetadata['customer_phone'] ?? 'Unknown';
                
                // Decode cart items
                $cartItemsJson = $customMetadata['cart_items'] ?? '[]';
                $cartItems = json_decode($cartItemsJson, true); 

                // --- VALIDATION: Verify Amount against Database Prices ---
                $expectedAmount = 0;
                if (is_array($cartItems)) {
                    foreach ($cartItems as $item) {
                        $product = Product::find($item['product_id']);
                        if ($product) {
                            $price = ($product->discount_price == -1) ? $product->price : $product->discount_price;
                            $expectedAmount += $price * $item['quantity'];
                        }
                    }
                }

                // Check if paid amount matches expected amount
                if ($expectedAmount != $entity['amount']) {
                    Log::error("Transaction Validation Failed: Amount mismatch for ref {$entity['reference']}. Database calculated: $expectedAmount, Paid: {$entity['amount']}");
                    return response()->json(['status' => 'error', 'message' => 'Amount verification failed'], 400);
                }
                // ---------------------------------------------------------

                // Transaction Reference (used as ID in our DB)
                $reference = $entity['reference'];

                // Use DB Transaction to ensure data integrity
                DB::beginTransaction();

                try {
                    // 1. Create Transaction Record
                    // Check if exists to prevent duplicates
                    $existing = TransactionModel::find($reference);
                    if (!$existing) {
                        $newTransaction = TransactionModel::create([
                            'id_transaction' => $reference,
                            'client_name' => $clientName,
                            'client_phone_number' => $clientPhone,
                            'amount' => $entity['amount'],
                        ]);

                        // 2. Create Transaction Details
                        if (is_array($cartItems)) {
                            foreach ($cartItems as $item) {
                                TransactionDetail::create([
                                    'id_transaction' => $reference,
                                    'product_id' => $item['product_id'],
                                    'product_amount' => $item['quantity'],
                                    'unit_price_sold_at' => $item['price'],
                                ]);
                            }
                        }
                    }

                    DB::commit();
                    Log::info("Transaction $reference stored successfully.");

                } catch (\Exception $dbEx) {
                    DB::rollBack();
                    Log::error("Database Error storing transaction $reference: " . $dbEx->getMessage());
                    throw $dbEx;
                }
            } else {
                Log::warning("Transaction $transactionId status is $status, not approved.");
            }

        } catch (\Exception $e) {
            Log::error("Error processing callback for ID $transactionId: " . $e->getMessage());
        }

        // Forward to Webhook.site as requested previously (optional now but keeping for consistency if needed)
        try {
             Http::post('https://webhook.site/77c63a59-f039-4657-b79f-377306fca75d', $data);
        } catch (\Exception $e) {}

        return response()->json(['status' => 'success']);
    }
}
