<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE catering_orders MODIFY COLUMN status ENUM('pengajuan','menunggu_pembayaran','terkonfirmasi','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'pengajuan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE catering_orders MODIFY COLUMN status ENUM('pengajuan','menunggu_pembayaran','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'pengajuan'");
    }
};
