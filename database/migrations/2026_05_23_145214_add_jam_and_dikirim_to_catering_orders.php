<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catering_orders', function (Blueprint $table) {
            $table->time('jam_acara')->nullable()->after('tanggal_acara');
            $table->time('jam_pengantaran')->nullable()->after('jam_acara');
        });

        DB::statement("ALTER TABLE catering_orders MODIFY COLUMN status ENUM('pengajuan','menunggu_pembayaran','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'pengajuan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE catering_orders MODIFY COLUMN status ENUM('pengajuan','menunggu_pembayaran','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'pengajuan'");

        Schema::table('catering_orders', function (Blueprint $table) {
            $table->dropColumn(['jam_acara', 'jam_pengantaran']);
        });
    }
};
