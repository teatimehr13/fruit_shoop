<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'option_text',
        'original_price',
        'price',
        'inventory',
        'image',
        'sort_order',
        'is_enabled',
    ];



    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function cartItems(){
        return $this->hasMany(CartItem::class);
    }
}
