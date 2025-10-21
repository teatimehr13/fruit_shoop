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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();               // 自家訂單編號
            $table->string('payment_order_number')->nullable()->unique(); // 金流方訂單編號
            $table->string('payment_token')->nullable()->unique();  // 金流驗證用
            $table->unsignedTinyInteger('order_status')->default(0)->index(); // 0=新建, 1=已付款...（自行定義）
            $table->string('payment_method')->nullable();
            $table->unsignedInteger('amount');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->text('address');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('note')->nullable()->comment('訂單備註');
            $table->timestamps();

            $table->index(['user_id','order_status','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
