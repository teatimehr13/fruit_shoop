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
            $table->string('address')->nullable()->after('password');
            $table->string('phone', 20)->unique()->nullable()->after('address');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->boolean('is_admin')->default(false)->index()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->dropColumn('phone_verified_at');
            $table->dropColumn('is_admin');
        });
    }
};
