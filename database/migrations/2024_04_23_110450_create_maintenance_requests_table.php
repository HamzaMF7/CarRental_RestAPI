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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->text('Description');
            $table->date('RequestDate');
            $table->enum('Status', ['Pending', 'In Progress', 'Completed', 'Cancelled', 'Scheduled', 'Waiting for Parts']);
            $table->date('CompletionDate')->nullable();
            $table->unsignedBigInteger('CarID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
