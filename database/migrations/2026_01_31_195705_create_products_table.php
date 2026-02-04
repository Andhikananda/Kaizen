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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->nullable();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('thumbnail')->nullable();
            $table->text('about')->nullable();
            $table->integer('stock')->default(0);
            $table->unsignedInteger('price');
            $table->string('unit')->default('pcs');
            $table->boolean('is_active')->default(true);
            $table->softDeletes(); // Menambahkan kolom deleted_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
