<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = ['cart_id', 'product_option_id', 'qty'];

    public function cart() {
        return $this->belongsTo(Cart::class);
    }

    public function productOption(){
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }
}
