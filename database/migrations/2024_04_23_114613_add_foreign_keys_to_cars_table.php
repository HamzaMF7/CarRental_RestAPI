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
        Schema::table('cars', function (Blueprint $table) {
            $table->foreign('CarDetailID')->references('id')->on('car_details');
            $table->foreign('CategoryID')->references('id')->on('categories');
            $table->foreign('BrandID')->references('id')->on('brands');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['CarDetailID']);
            $table->dropForeign(['CategoryID']);
            $table->dropForeign(['BrandID']);
        });
    }
};
