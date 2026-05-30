<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    const PAYMENT_TIMEOUT_MINUTES = 10;
    const ONLINE_PAYMENT_METHODS  = ['Bayar Online'];

    protected $fillable = [
        'user_id', 'nama_penerima', 'alamat', 'detail_alamat', 'no_telepon',
        'metode_pembayaran', 'metode_pengiriman', 'catatan', 'tanggal_pengiriman', 'waktu_pengiriman',
        'subtotal', 'ongkir', 'total', 'status', 'alasan_pembatalan', 'dibatalkan_oleh',
        'snap_token', 'midtrans_transaction_id', 'payment_type', 'paid_at',
    ];

    protected $casts = [
        'paid_at'             => 'datetime',
        'tanggal_pengiriman'  => 'date',
    ];

    public function items() { return $this->hasMany(OrderItem::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function testimonial() { return $this->hasOne(Testimonial::class); }

    public function needsPaymentDeadline(): bool
    {
        return $this->status === 'belum_bayar';
    }

    public function paymentExpiresAt(): ?\Carbon\Carbon
    {
        return $this->needsPaymentDeadline()
            ? $this->created_at->copy()->addMinutes(self::PAYMENT_TIMEOUT_MINUTES)
            : null;
    }

    public function isPaymentExpired(): bool
    {
        $expires = $this->paymentExpiresAt();
        return $expires !== null && now()->greaterThanOrEqualTo($expires);
    }

    public static function cancelExpiredUnpaidOrders(): int
    {
        $cutoff = now()->subMinutes(self::PAYMENT_TIMEOUT_MINUTES);
        $expiredOrders = self::with('items')
            ->where('status', 'belum_bayar')
            ->where('created_at', '<', $cutoff)
            ->get();

        if ($expiredOrders->isEmpty()) return 0;

        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                static::restoreStock($order);
                $order->update([
                    'status'            => 'dibatalkan',
                    'alasan_pembatalan' => 'Pembayaran melewati batas waktu (10 menit)',
                    'dibatalkan_oleh'   => 'system',
                ]);
            });
        }

        return $expiredOrders->count();
    }

    public static function restoreStock(self $order): void
    {
        foreach ($order->items as $item) {
            $product = \App\Models\Product::lockForUpdate()->find($item->product_id);
            if (!$product) continue;
            $product->increment('stock', $item->quantity);
            if ($product->status === 'habis') {
                $product->update(['status' => 'ready']);
            }
        }
    }

    public function getPaymentLabel(): string
    {
        $labels = [
            'qris'          => 'QRIS',
            'bank_transfer' => 'Transfer Bank',
            'credit_card'   => 'Kartu Kredit',
            'gopay'         => 'GoPay',
            'shopeepay'     => 'ShopeePay',
            'echannel'      => 'Mandiri Bill',
            'cstore'        => 'Convenience Store',
        ];
        return $this->payment_type
            ? ($labels[$this->payment_type] ?? strtoupper($this->payment_type))
            : $this->metode_pembayaran;
    }

    public function scopeExcludeAutoCancelled($query)
    {
        return $query->where(function ($q) {
            $q->where('status', '!=', 'dibatalkan')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'dibatalkan')
                     ->where('alasan_pembatalan', 'not like', '% di Midtrans')
                     ->where('alasan_pembatalan', '!=', 'Pembayaran melewati batas waktu (10 menit)');
              });
        });
    }
}
