<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'nama_penerima', 'alamat', 'detail_alamat', 'no_telepon',
        'metode_pembayaran', 'metode_pengiriman', 'catatan',
        'subtotal', 'ongkir', 'total', 'status'
    ];

    public function items() { return $this->hasMany(OrderItem::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function testimonial() { return $this->hasOne(Testimonial::class); } // tambahan
}