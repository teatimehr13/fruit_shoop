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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_city');
            $table->string('shipping_district');
            $table->string('shipping_address_detail');
            $table->string('shipping_zip_code');
            $table->string('shipping_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->dropColumn('shipping_city');
            $table->dropColumn('shipping_district');
            $table->dropColumn('shipping_address_detail');
            $table->dropColumn('shipping_zip_code');
            $table->dropColumn('shipping_email');
        });
    }
};
