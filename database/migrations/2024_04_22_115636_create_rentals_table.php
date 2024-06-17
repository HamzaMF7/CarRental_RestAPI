<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->date('StartDate');
            $table->date('EndDate');
            $table->decimal('TotalCost', 10, 2);
            $table->enum('Status', ['Pending', 'Confirmed', 'Active', 'Completed', 'Cancelled', 'Expired', 'Returned', 'Overdue', 'Damaged', 'Refunded'])->default('Pending');
            $table->string('AdditionalRequirements')->nullable();
            $table->string('PhoneNumber', 255);
            $table->string('City');
            $table->unsignedBigInteger('PickupLocationID');
            $table->unsignedBigInteger('ReturnLocationID');
            $table->unsignedBigInteger('CarID');
            $table->unsignedBigInteger('UserID');
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
