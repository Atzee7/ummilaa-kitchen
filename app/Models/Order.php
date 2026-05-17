<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PAYMENT_TIMEOUT_MINUTES = 10;
    const ONLINE_PAYMENT_METHODS  = ['Mandiri Virtual Account', 'QRIS'];

    protected $fillable = [
        'user_id', 'nama_penerima', 'alamat', 'detail_alamat', 'no_telepon',
        'metode_pembayaran', 'metode_pengiriman', 'catatan',
        'subtotal', 'ongkir', 'total', 'status', 'alasan_pembatalan'
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
        return self::where('status', 'belum_bayar')
            ->where('created_at', '<', $cutoff)
            ->update([
                'status'            => 'dibatalkan',
                'alasan_pembatalan' => 'Pembayaran melewati batas waktu (10 menit)',
            ]);
    }
}
