<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sort_order',
        'is_enabled'
    ];

    protected $casts = ['is_enabled' => 'boolean'];

    // public function getRouteKeyName()
    // {
    //     return 'name';
    // }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id', 'id');
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, Subcategory::class, 'category_id', 'subcategory_id');
    }
}
