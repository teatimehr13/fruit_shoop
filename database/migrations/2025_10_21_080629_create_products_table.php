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
            $table->foreignId('subcategory_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->unsignedInteger('price')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(true)->index();
            $table->timestamps();

            $table->index(['subcategory_id','is_enabled']);
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
