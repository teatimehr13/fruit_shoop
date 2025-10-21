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
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('option_text');
            $table->unsignedInteger('original_price');
            $table->unsignedInteger('price');
            $table->unsignedInteger('inventory')->default(0);
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true)->index();
            $table->timestamps();

            $table->unique(['product_id', 'option_text']);
            $table->index(['product_id', 'is_enabled', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};
