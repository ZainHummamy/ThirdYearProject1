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
        Schema::create('service_trip', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('trip_id')->constrained()->references('trip_id')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_trip');
    }
};
