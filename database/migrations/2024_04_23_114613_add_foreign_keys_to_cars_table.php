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
            $table->foreign('CategoryID')->references('id')->on('categories');
            $table->foreign('BrandID')->references('id')->on('brands');
            $table->foreign('LocationID')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['CategoryID']);
            $table->dropForeign(['BrandID']);
            $table->dropForeign(['LocationID']);
        });
    }
};
