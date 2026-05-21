<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringOrder extends Model
{
    // Batas waktu pembayaran disamakan dengan order catalogue: 10 menit sejak
    // pelanggan membuka halaman bayar. Lewat batas → pesanan dibatalkan otomatis.
    const PAYMENT_TIMEOUT_MINUTES = 10;

    protected $fillable = [
        'user_id', 'catering_package_id',
        'nama_acara', 'tanggal_acara', 'jumlah_pax', 'lokasi_acara', 'detail_lokasi_acara', 'catatan',
        'admin_notes', 'nama_pemesan', 'no_telepon', 'total', 'status', 'alasan_pembatalan',
        'snap_token', 'midtrans_transaction_id', 'payment_type', 'paid_at', 'payment_expires_at',
    ];

    protected $casts = [
        'tanggal_acara'      => 'date',
        'paid_at'            => 'datetime',
        'payment_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(CateringPackage::class, 'catering_package_id');
    }

    public function costItems()
    {
        return $this->hasMany(CateringOrderCostItem::class)->orderBy('sort_order');
    }

    public function histories()
    {
        return $this->hasMany(CateringOrderHistory::class)->latest();
    }

    public function logHistory(string $status, ?string $notes = null): void
    {
        $this->histories()->create(['status' => $status, 'notes' => $notes]);
    }

    public function isPayable(): bool
    {
        return $this->status === 'menunggu_pembayaran' && (int) $this->total > 0;
    }

    public function needsPaymentDeadline(): bool
    {
        return $this->status === 'menunggu_pembayaran';
    }

    public function paymentExpiresAt(): ?\Carbon\Carbon
    {
        return $this->needsPaymentDeadline() && $this->payment_expires_at
            ? $this->payment_expires_at->copy()
            : null;
    }

    public function isPaymentExpired(): bool
    {
        $expires = $this->paymentExpiresAt();
        return $expires !== null && now()->greaterThanOrEqualTo($expires);
    }

    public static function cancelExpiredUnpaidOrders(): int
    {
        $orders = self::where('status', 'menunggu_pembayaran')
            ->whereNotNull('payment_expires_at')
            ->where('payment_expires_at', '<', now())
            ->get();

        foreach ($orders as $order) {
            $order->update([
                'status'            => 'dibatalkan',
                'alasan_pembatalan' => 'Pembayaran melewati batas waktu (10 menit)',
            ]);
            $order->logHistory('dibatalkan', 'Pembayaran melewati batas waktu (10 menit)');
        }

        return $orders->count();
    }
}
