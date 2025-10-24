<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'subcategory_id',
        'slug',
        'name',
        'price',
        'image',
        'description',
        'is_enabled',
    ];

    protected static function booted()
    {
        static::deleting(function ($product) {
            if ($product->productImages->isNotEmpty()) {
                $product->productImages->each(function ($data) {
                    $path = str_replace('/storage/', '', $data->image);
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                    $data->delete();
                });
            }
        });

        static::creating(function ($product) {
            // 只有沒填 slug 才自動補
            if (blank($product->slug)) {
                $slug = Str::slug($product->name, '-');

                // 中文名字可能轉不出 slug，就給個簡短隨機碼頂著
                $product->slug = $slug !== '' ? $slug : Str::random(8);
            }
        });
    }


    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
}
