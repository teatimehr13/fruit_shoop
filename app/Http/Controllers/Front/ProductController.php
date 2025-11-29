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
    // public function index(Request $request, ?string $categoryName = null)
    // {
    //     $categories_tab = Category::select(['id', 'name'])->orderBy('sort_order')->get();
    //     $query = Product::query()->select(['id', 'subcategory_id', 'slug', 'name', 'price', 'description'])
    //         ->with('primaryImage')
    //         ->with('cheapestOption');
    //     // ->get();

    //     if ($categoryName) {
    //         $category = Category::where('name', $categoryName)->first();

    //         if ($category) {
    //             $query->whereHas('subcategory', function ($q) use ($category) {
    //                 $q->where('category_id', $category->id);
    //             });
    //         }
    //     }

    //     // if ($subcategoryId = $request->get('subcategory_id')) {
    //     //     $query->where('subcategory_id', $subcategoryId);
    //     // }

    //     $products = $query->get();

    //     return Inertia::render('Front/Products/Index', [
    //         'categories_tab' => $categories_tab,
    //         'products' => $products
    //     ]);
    // }

    public function index(Request $request, ?Category $category)
    {
        // dd($category);
        $categories_tab = Category::select(['id', 'name'])
            ->orderBy('sort_order')
            ->get();

        $query = Product::query()
            ->select(['id', 'subcategory_id', 'slug', 'name', 'price', 'description'])
            ->with('primaryImage')
            ->with('cheapestOption');

        if ($category && $category->exists) {
            $query->whereHas('subcategory', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            });
        }

        // dd(
        //     $query->toSql(),        // 帶 ? 的 SQL 字串
        //     $query->getBindings()   // 對應的參數
        // );

        $products = $query->get();


        return Inertia::render('Front/Products/Index', [
            'categories_tab' => $categories_tab,
            'products'       => $products,
            'activeCategory'  => $category?->name,
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
