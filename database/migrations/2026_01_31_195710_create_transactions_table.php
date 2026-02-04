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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
            $table->integer('km_entry')->nullable();
            $table->integer('km_next')->nullable();
            $table->decimal('total_bruto', 15, 2);
            $table->enum('final_discount_type', ['percent', 'nominal'])->nullable();
            $table->decimal('final_discount_value', 15, 2)->default(0);
            $table->decimal('total_netto', 15, 2);
            $table->string('status')->default('pending'); // pending, paid, cancelled
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
