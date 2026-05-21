<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringPackage extends Model
{
    protected $fillable = [
        'name', 'description', 'price_per_pax', 'min_pax', 'image', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function cateringOrders()
    {
        return $this->hasMany(CateringOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
