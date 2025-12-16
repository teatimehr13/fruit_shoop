<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {

        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_status_new', 50)
                ->default('not_selected_payment');
        });

        
        DB::table('orders')->update([
            'order_status_new' => DB::raw("CASE WHEN order_status = 1 THEN 'paid' ELSE 'not_selected_payment' END")
        ]);


        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_status');
        });


        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('order_status_new', 'order_status');
        });
    }

    public function down(): void
    {

        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('order_status_old')->default(0);
        });


        DB::table('orders')->update([
            'order_status_old' => DB::raw("CASE WHEN order_status = 'paid' THEN 1 ELSE 0 END")
        ]);


        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('order_status_old', 'order_status');
        });
    }
};
