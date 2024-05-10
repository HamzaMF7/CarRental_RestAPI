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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('Amount', 10, 2);
            $table->enum('PaymentMethod', ['Credit Card', 'PayPal', 'Bank Transfer', 'Cash', 'Other']);
            $table->enum('PaymentStatus', ['Pending', 'Completed', 'Failed', 'Refunded', 'Cancelled', 'Expired', 'Partially Refunded', 'Chargeback']);
            $table->date('PaymentDate');
            $table->string('TransactionID', 255);
            $table->unsignedBigInteger('RentalID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
