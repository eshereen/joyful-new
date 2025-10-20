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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique(); // Unique SKU foeach variant
            $table->string('wick_type')->default('wooden');
            $table->string('size')->default('200');
            $table->integer('stock')->default(0);
            $table->integer('price')->nullable();
            $table->integer('compare_price')->nullable(); // Optional: variant-specific pricing
            $table->integer('weight')->nullable(); // Optional weight for shipping calculations
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('product_id')->references('id')->on('products');
            $table->unique(['product_id', 'sku']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
