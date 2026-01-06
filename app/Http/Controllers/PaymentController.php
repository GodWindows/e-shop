<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        // Log the incoming request for debugging
        Log::info('Payment Callback Received', $request->all());

        $data = $request->all();

        // Extract required data
        // Structure: name, object, entity -> { custom_metadata, metadata, amount, reference }
        $entity = $data['entity'] ?? [];
        
        $payload = [
            'custom_metadata' => $entity['custom_metadata'] ?? null,
            'metadata' => $entity['metadata'] ?? null,
            'amount' => $entity['amount'] ?? null,
            'reference' => $entity['reference'] ?? null,
        ];

        // Send to externally specified webhook
        try {
            $response = Http::post('https://webhook.site/77c63a59-f039-4657-b79f-377306fca75d', $payload);
            
            Log::info('Forwarded to Webhook.site', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to forward to webhook', ['error' => $e->getMessage()]);
        }

        return response()->json(['status' => 'success']);
    }
}
