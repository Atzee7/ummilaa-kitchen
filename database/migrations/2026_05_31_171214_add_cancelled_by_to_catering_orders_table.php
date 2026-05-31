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
        Schema::table('catering_orders', function (Blueprint $table) {
            $table->enum('cancelled_by', ['user', 'admin'])->nullable()->after('alasan_pembatalan');
        });
    }

    public function down(): void
    {
        Schema::table('catering_orders', function (Blueprint $table) {
            $table->dropColumn('cancelled_by');
        });
    }
};
