<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringOrder extends Model
{
    // Masa aktif QR/token Midtrans, dibuat pendek seperti catalogue agar QR GoPay/QRIS
    // selalu segar saat di-scan. Token di-generate ulang tiap halaman bayar dibuka,
    // dan kedaluwarsa QR TIDAK membatalkan pesanan (pelanggan bisa ulang bayar).
    const PAYMENT_EXPIRY_MINUTES = 15;

    protected $fillable = [
        'user_id', 'catering_package_id',
        'nama_acara', 'tanggal_acara', 'jumlah_pax', 'lokasi_acara', 'catatan',
        'nama_pemesan', 'no_telepon', 'total', 'status', 'alasan_pembatalan',
        'snap_token', 'midtrans_transaction_id', 'payment_type', 'paid_at',
    ];

    protected $casts = [
        'tanggal_acara' => 'date',
        'paid_at'       => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(CateringPackage::class, 'catering_package_id');
    }

    public function isPayable(): bool
    {
        return $this->status === 'menunggu_pembayaran' && (int) $this->total > 0;
    }
}
