<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringOrderHistory extends Model
{
    protected $fillable = ['catering_order_id', 'status', 'notes'];

    public function order()
    {
        return $this->belongsTo(CateringOrder::class, 'catering_order_id');
    }
}
