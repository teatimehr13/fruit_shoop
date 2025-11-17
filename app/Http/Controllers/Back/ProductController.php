<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
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
        return Inertia::render('Back/Product/Index', [
            'products' => $products,
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

        // if ($request->hasFile('image')) {
        //     $dir = "products/{$product->id}";
        //     $filename = $request->file('image')->hashName();
        //     $relativePath = $request->file('image')->storeAs($dir, $filename, 'public');
        // }

        // $product->update(['image' => $relativePath]);

        return response()->json($product, 201); //新增201
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

        // $oldPath = $product->image;  // 相對路徑
        // $newPath = null;

        // // 有新檔才處理上傳
        // if ($request->hasFile('image')) {
        //     $dir = "products/{$product->id}";
        //     // hashName() 會自動產生檔名，store() 會自動建立資料夾
        //     $newPath = $request->file('image')->store($dir, 'public');
        //     $validated['image'] = $newPath;
        // }

        $product->update($validated);

        // // 新檔成功且與舊檔不同 → 刪舊檔
        // if ($newPath && $oldPath && $oldPath !== $newPath && Storage::disk('public')->exists($oldPath)) {
        //     Storage::disk('public')->delete($oldPath);
        // }

        // if ($request->boolean('remove_image') && $oldPath) {
        //     $product->update(['image' => null]);
        //     Storage::disk('public')->delete($oldPath);
        // }

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
        // $products = Product::query()
        //     ->select(['id', 'slug', 'name', 'price', 'image', 'description', 'is_enabled'])
        //     ->orderBy('created_at', 'asc')
        //     ->get();

        $query = Product::query()
            ->select(['id', 'slug', 'name', 'price', 'description', 'is_enabled'])
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

        // $query->when($request->boolean('enabled'), fn($q) => $q->where('is_enabled', true));
        // $query->when($request->filled('min_price'), fn($q) => $q->where('price', '>=', (int)$request->min_price));
        // $query->when($request->filled('max_price'), fn($q) => $q->where('price', '<=', (int)$request->max_price));

        $products = $query->paginate(10)->withQueryString();

        return $products;
    }

    public function details(Product $product)
    {
        return response()->json([
            'product' => $product->only([
                'id',
                'slug',
                'name',
                'description',
                'price',
                'is_enabled'
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

    // public function indexJson()
    // {
    //     $products = $this->fetchIndexData();
    //     return response()->json(['data' => $products]);
    // }

    public function changeStatus(Product $product)
    {
        $product->update([
            'is_enabled' => !$product->is_enabled
        ]);

        return response()->json([
            'is_enabled' => $product->is_enabled
        ],200);
    }
}
