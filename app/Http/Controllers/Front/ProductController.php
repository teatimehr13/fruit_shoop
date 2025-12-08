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
            ->with('cheapestOption')
            ->with('productOptions')
            ->withMin('productOptions', 'price');   // 會多一個欄位 options_min_price
            // ->withMax('productOptions', 'created_at'); // 會多一個欄位 options_max_created_at

        if ($category && $category->exists) {
            $query->whereHas('subcategory', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            });
        }

        //加入排序條件
        $sort_by = $request->input('sort_by', 'created_at'); // 預設用時間
        $sort_dir = $request->input('sort_dir', 'desc');     // 預設 desc

        $validSorts = ['created_at', 'price'];
        $validDirs = ['asc', 'desc'];

        // if (in_array($sort_by, $validSorts) && in_array($sort_dir, $validDirs)) {
        //     $query->orderBy($sort_by, $sort_dir);
        // }

        if (in_array($sort_dir, $validDirs)) {
            if ($sort_by === 'price') {
                // 用每個 product 的「最低 option 價」來排序
                $query->orderBy('product_options_min_price', $sort_dir);
            } elseif ($sort_by === 'created_at') {
                // 用商品本身的建立時間來排（最新上市）
                $query->orderBy('created_at', $sort_dir);;
            }
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

    public function show(Product $product)
    {
        $productRow = $product->select(['id', 'subcategory_id', 'slug', 'name', 'price', 'description'])
            ->with('primaryImage')
            ->with('cheapestOption')
            ->with('productOptions')
            ->findOrFail($product->id);

        return Inertia::render('Front/Products/Show', [
            'product' => $productRow
        ]);
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
