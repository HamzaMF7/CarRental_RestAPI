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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('UserID');
            $table->date('StartDate');
            $table->date('EndDate');
            $table->decimal('TotalCost', 10, 2);
            $table->unsignedBigInteger('CarID');
            $table->enum('Status', ['Pending', 'Confirmed', 'Active', 'Completed', 'Cancelled', 'Expired', 'Returned', 'Overdue', 'Damaged', 'Refunded'])->default('Pending');
            $table->string('AdditionalRequirements')->nullable();
            $table->unsignedBigInteger('PickupLocationID');
            $table->unsignedBigInteger('ReturnLocationID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
