<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = $this->fetchIndexData($request);
        $subcategories = Subcategory::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();
        return Inertia::render('Back/Product/Index', [
            'products' => $products,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'filters' => $request->only(['subcategory_id', 'name'])
        ]);
    }

    public function create()
    {
        //
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Product $product)
    {
        $catId = $product->subcategory->category_id;
        $subcategoriesSelect = Subcategory::query()
            ->where('is_enabled', true)
            ->where('category_id', $catId)
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        return Inertia::render('Back/Product/Edit', [
            'product' => $product,
            'subcategoriesSelect' => $subcategoriesSelect
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product->update($validated);

        return response()->json($product->fresh(), 200);
    }

    public function destroy(Product $product)
    {
        $dir = "products/{$product->id}";

        // 先刪單檔
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // 再刪整個目錄
        if (Storage::disk('public')->directoryExists($dir)) {
            Storage::disk('public')->deleteDirectory($dir);
        }

        // 刪資料
        $product->delete();

        return response()->noContent(); // 204
    }

    private function fetchIndexData(Request $request)
    {
        $query = Product::query()
            ->select(['id', 'subcategory_id', 'slug', 'name', 'description', 'is_enabled'])
            ->with(['subcategory' => function ($query) {
                $query->select(['id', 'name']);
            }])
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc'); // 讓分頁順序穩定

        $name = trim((string)$request->query('name', ''));
        $subcategory_id = $request->query('subcategory_id');

        // LIKE 需要跳脫 % _
        if ($name !== '') {
            $escaped = addcslashes($name, '%_');  //把 % _ 加上反斜線變成普通字
            $query->where('name', 'like', "%{$escaped}%");
        }

        if ($subcategory_id !== null && $subcategory_id !== '') {
            $query->where('subcategory_id', (int)$subcategory_id);
        }

        $products = $query->paginate(10)->withQueryString()->through(function ($item) {
            // 這裡就是「打平」的地方
            return [
                'id' => $item->id,
                'slug' => $item->slug,
                'name' => $item->name,
                'description' => $item->description,
                'is_enabled' => $item->is_enabled,
                // 直接創造一個第一層的 key 給前端
                'subcategory_name' => $item->subcategory?->name ?? '未分類',
            ];
        });

        return $products;
    }

    public function details(Product $product)
    {
        $product->load('subcategory');

        return response()->json([
            'product' => $product->only([
                'id',
                'slug',
                'name',
                'description',
                'is_enabled',
                'subcategory_id',
                'subcategory_name'
            ]),

            'images' => $product->productImages()
                ->orderBy('sort_order')
                ->get(['id', 'image', 'alt_text', 'is_primary', 'sort_order']),

            'options' => $product->productOptions()
                // ->with('values') // 如果你有 option values 關聯
                ->orderBy('sort_order', 'asc')
                ->get(),
        ]);
    }

    public function changeStatus(Product $product)
    {
        $product->update([
            'is_enabled' => !$product->is_enabled
        ]);

        return response()->json([
            'is_enabled' => $product->is_enabled
        ], 200);
    }

    public function getSubcategories(Category $category)
    {
        $subcategories = $category->subcategories()
            ->select('id', 'name')
            ->get();
        return response()->json($subcategories);
    }
}
