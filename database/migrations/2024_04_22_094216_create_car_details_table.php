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
        Schema::create('car_details', function (Blueprint $table) {
            $table->id();
            $table->string('Model', 255);
            $table->string('Color', 50);
            $table->boolean('Hybrid')->nullable();
            $table->boolean('Electric')->nullable();
            $table->boolean('AirConditioner');
            $table->string('RegistrationNumber', 20);
            $table->integer('Mileage');
            $table->boolean('GPSInstalled');
            $table->boolean('BluetoothEnabled');
            $table->text('InsuranceDetails')->nullable();
            $table->text('MaintenanceHistory')->nullable();
            $table->unsignedBigInteger('CarID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_details');
    }
};
