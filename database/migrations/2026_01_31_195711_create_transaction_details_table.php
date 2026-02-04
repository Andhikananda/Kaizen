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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            // Gunakan foreignId agar otomatis bertipe BigInteger (Sesuai dengan id di transactions)
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->string('product_type');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->enum('discount_type', ['percent', 'nominal'])->nullable();
            $table->decimal('discount_value', 15, 2)->default(0);
            $table->decimal('sub_total', 15, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
