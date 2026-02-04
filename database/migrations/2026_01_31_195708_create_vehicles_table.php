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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            // Ubah dari constrained() menjadi constrained('vehicle_models')
            $table->foreignId('model_id')->constrained('vehicle_models')->onDelete('cascade');

            $table->string('license_plate')->unique();
            $table->string('color')->nullable();
            $table->text('description')->nullable(); // Kolom tambahan dari ERD Anda
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
