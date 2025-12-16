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
        Schema::table('users', function (Blueprint $table) {
            $table->string('city')->nullable();           // 台北市
            $table->string('district')->nullable();       // 大安區
            $table->string('address_detail')->nullable(); // XX路123號5樓
            $table->string('zip_code')->nullable();       // 106

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //dropColumn
            $table->dropColumn('city');
            $table->dropColumn('district');
            $table->dropColumn('address_detail');
            $table->dropColumn('zip_code');
        });
    }
};
