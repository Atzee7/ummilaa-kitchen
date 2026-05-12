<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'name', 'category', 'category_id', 'description',
    'price', 'stock', 'image', 'status', 'badge'
    ];

    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function carts() { return $this->hasMany(Cart::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
}