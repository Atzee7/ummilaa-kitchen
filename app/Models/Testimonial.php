<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'catering_order_id',
        'nama',
        'rating',
        'komentar',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function cateringOrder()
    {
        return $this->belongsTo(CateringOrder::class, 'catering_order_id');
    }
}