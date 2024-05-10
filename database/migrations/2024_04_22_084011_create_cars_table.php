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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('CarName');
            $table->decimal('Price', 10, 2);
            $table->integer('Capacity');
            $table->string('Image');
            $table->enum('FuelType', ['diesel', 'essence', 'electric', 'hybrid/essence', 'hybrid/diesel']);
            $table->string('TransmissionType');
            $table->enum('CurrentStatus', [
                'Available',
                'Booked',
                'Out for Rent',
                'Under Maintenance',
                'Returned',
                'Unavailable',
                'Damaged',
            ])->default('Available');
            $table->unsignedBigInteger('CarDetailID');
            $table->unsignedBigInteger('CategoryID');
            $table->unsignedBigInteger('BrandID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
