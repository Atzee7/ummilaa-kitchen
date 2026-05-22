<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catering_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('catering_package_id')->nullable()->constrained('catering_packages')->nullOnDelete();
            $table->string('nama_acara');
            $table->date('tanggal_acara');
            $table->integer('jumlah_pax');
            $table->text('lokasi_acara');
            $table->text('catatan')->nullable();
            $table->string('nama_pemesan');
            $table->string('no_telepon');
            $table->integer('total')->nullable();
            $table->enum('status', ['pengajuan', 'menunggu_pembayaran', 'diproses', 'selesai', 'dibatalkan'])->default('pengajuan');
            $table->text('alasan_pembatalan')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catering_orders');
    }
};
