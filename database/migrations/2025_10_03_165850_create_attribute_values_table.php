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
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')
                  ->constrained('attributes')
                  ->onDelete('cascade'); // if attribute is deleted, its values go too
            $table->string('value'); // e.g. 200g, 400g, Wood, Cotton
            $table->timestamps();
            $table->unique(['attribute_id', 'value']); // prevent duplicates

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};
