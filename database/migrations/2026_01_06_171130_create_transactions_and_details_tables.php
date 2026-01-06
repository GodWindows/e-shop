<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id_transaction')->primary(); // Not auto-increment, provided externally
            $table->string('client_name');
            $table->string('client_phone_number');
            $table->integer('amount');
            $table->timestamps();
        });

        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaction');
            $table->unsignedBigInteger('product_id');
            $table->integer('product_amount'); // Quantity
            $table->integer('unit_price_sold_at'); // Price at the moment of transaction
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_transaction')->references('id_transaction')->on('transactions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');
    }
};
