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
            // nilai: 'user' | 'admin' | 'system' | null (belum dibatalkan)
            $table->string('dibatalkan_oleh')->nullable()->after('alasan_pembatalan');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('dibatalkan_oleh');
        });
    }
};
