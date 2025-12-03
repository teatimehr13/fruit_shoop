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

    protected $appends = [
        'cheapest_price',
        'cheapest_original_price',
        'has_discount',
        'cheapest_option_id',
        // 'cheapest_option_qty'
    ];

    protected $hidden = [
        'cheapestOption',
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

    public function getRouteKeyName()
    {
        return 'name';
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

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function siblingSubcategories()
    {
        return $this->subcategory
            ? Subcategory::where('category_id', $this->subcategory->category_id)->get()
            : collect();
    }

    public function cheapestOption()
    {
        return $this->hasOne(ProductOption::class)
            ->orderBy('price');
    }

    public function getCheapestPriceAttribute()
    {
        return $this->cheapestOption?->price;
    }

    public function getCheapestOriginalPriceAttribute()
    {
        return $this->cheapestOption?->original_price;
    }

    public function getHasDiscountAttribute()
    {
        return !is_null($this->cheapest_original_price)
            && $this->cheapest_original_price > $this->cheapest_price;
    }

    public function getCheapestOptionIdAttribute()
    {
        return $this->cheapestOption?->id;
    }
    //  public function getCheapestOptionQtyAttribute()
    // {
    //     return $this->cheapestOption?->qty;
    // }
}
