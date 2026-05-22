<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringOrderCostItem extends Model
{
    protected $fillable = ['catering_order_id', 'label', 'amount', 'sort_order'];

    public function order()
    {
        return $this->belongsTo(CateringOrder::class, 'catering_order_id');
    }
}
