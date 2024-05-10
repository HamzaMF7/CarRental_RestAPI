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
        Schema::table('rentals', function (Blueprint $table) {
            $table->foreign('CarID')->references('id')->on('cars');
            $table->foreign('UserID')->references('id')->on('users');
            $table->foreign('PickupLocationID')->references('id')->on('locations');
            $table->foreign('ReturnLocationID')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign(['CarID']);
            $table->dropForeign(['UserID']);
            $table->dropForeign(['PickupLocationID']);
            $table->dropForeign(['ReturnLocationID']);
        });
    }
};
