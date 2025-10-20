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
        Schema::create('variant_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')
            ->constrained('variants')
            ->onDelete('cascade'); // delete options if variant is deleted

            $table->foreignId('attribute_value_id')
                    ->constrained('attribute_values')
                    ->onDelete('cascade'); // delete option if value is deleted

            $table->timestamps();

            $table->unique(['variant_id', 'attribute_value_id']); // each variant can have a value only once
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_options');
    }
};
