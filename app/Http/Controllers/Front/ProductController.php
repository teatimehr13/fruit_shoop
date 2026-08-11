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
    public function index(Request $request, ?Category $category)
    {
        $categories_tab = Category::select(['id', 'name'])
            ->where('is_enabled', 1)
            ->orderBy('sort_order')
            ->get();

        $query = Product::query()
            ->select(['id', 'subcategory_id', 'slug', 'name', 'price', 'description'])
            ->where('is_enabled', 1)
            ->with(['primaryImage', 'cheapestOption', 'productOptions'])
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
        ]);
    }



    public function home()
    {
        $popularProducts = Product::query()
            ->select(['id', 'name', 'slug'])
            ->where([
                'is_featured' => 1,
                'is_enabled'  => 1,
            ])
            ->orderByRaw('featured_sort IS NULL')
            ->orderBy('featured_sort')
            ->with([
                'primaryImage:id,product_id,image',
                'productOptions:id,product_id,option_text,price',
            ])
            ->limit(8)
            ->get()
            ->map(fn($p) => [
                'id'              => $p->id,
                'slug'            => $p->slug,
                'name'            => $p->name,
                'image'           => $p->primaryImage?->img_url,
                'product_options' => $p->productOptions,
            ])
            ->filter(fn($p) => $p['image'] && $p['product_options']->isNotEmpty())
            ->values();

        return Inertia::render('Front/Home/Index', [
            'popularProducts' => $popularProducts,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request) {}

    public function show(Product $product)
    {
        // $productRow = $product->select(['id', 'subcategory_id', 'slug', 'name', 'price', 'description'])
        //     ->with('primaryImage')
        //     ->with('cheapestOption')
        //     ->with('productOptions')
        //     ->findOrFail($product->id);
        abort_unless($product->is_enabled, 404);

        $product->load(['primaryImage', 'cheapestOption', 'productOptions', 'productImages' => fn($q) => $q->orderByDesc('is_primary')->orderBy('id')]);

        return Inertia::render('Front/Products/Show', [
            'product' => $product
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
