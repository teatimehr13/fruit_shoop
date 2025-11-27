<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $categories_tab = Category::select(['id', 'name'])->orderBy('sort_order')->get();
        $products = Product::query()->select(['id', 'subcategory_id', 'slug', 'name', 'price', 'description'])
        ->with('primaryImage')
        ->with('cheapestOption')
        ->get();
        
        return Inertia::render('Front/Products/Index', [
            'categories_tab' => $categories_tab,
            'products' => $products
        ]);
    }

    public function home()
    {
        return Inertia::render('Front/Home/Index', [
            'data' => ''
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request) {}

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
