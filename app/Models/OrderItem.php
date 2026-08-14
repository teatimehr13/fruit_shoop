<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'qty',
        'product_option_id',
        'option_text'
    ];

    protected $appends = ['img_url'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productOption()
    {
        return $this->belongsTo(ProductOption::class);
    }

    public function getImgUrlAttribute()
    {
        return $this->image ? '/storage/' . $this->image : $this->image;
    }
}
