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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->nullable(); // 例如：google / facebook / github ...
            $table->string('provider_id')->nullable();      // 第三方的唯一識別（sub / id）
            $table->string('avatar')->nullable();
            $table->unique(['provider', 'provider_id']); // 防止同一供應商重複綁定
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
