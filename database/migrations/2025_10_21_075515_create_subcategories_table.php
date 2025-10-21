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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true)->index();
            $table->timestamps();

            $table->unique(['category_id', 'name']);  //同分類不可同名
            $table->index(['category_id', 'is_enabled', 'sort_order']); //複合索引
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
