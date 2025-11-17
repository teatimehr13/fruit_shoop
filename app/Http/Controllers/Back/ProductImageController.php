<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function index(Product $product)
    {
        $productImages = $product->productImages()
            ->orderBy('sort_order', 'asc')->get();
        return response()->json($productImages);
    }

    public function create()
    {
        //
    }

    public function store(ProductImageRequest $request, Product $product)
    {
        $validated = $request->validated()['productImages'];
        $created = DB::transaction(function () use ($product, $validated) {
            $rows = [];
            // 鎖定這個商品的圖片列，避免併發計數/排序衝突
            $baseQuery  = $product->productImages()->lockForUpdate();
            $hasAny     = (clone $baseQuery)->exists();                   // 是否已有任何圖片
            $nextOrder  = (clone $baseQuery)->max('sort_order') ?? 0;     // 只算這個 product 的 max

            foreach ($validated as $i => $row) {
                // 先存檔案，拿相對路徑
                $path = $row['image']->store("products/{$product->id}", 'public');

                //同product_id未有任何圖時，上傳的第一筆為Primary，後續上傳則為否且由update更改
                $isPrimary = !$hasAny && $i === 0;
                $rows[] = $product->productImages()->create([
                    'image'      => $path,
                    'alt_text'   => $row['alt_text'] ?? null,
                    'is_primary' => $isPrimary,
                    'sort_order' => ++$nextOrder
                ]);
            }
            return $rows;
        });

        return response()->json(['data' => $created], 201);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, ProductImage $productImage)
    {
        $validated = $request->validate([
            'alt_text' => ['nullable', 'string']
        ]);

        $productImage->update(['alt_text' => $validated['alt_text']]);
        return response()->json($productImage->fresh(), 200);
    }

    public function destroy(ProductImage $productImage)
    {
        // if ($productImage->image && Storage::disk('public')->exists($productImage->image)) {
        //     Storage::disk('public')->delete($productImage->image);
        // }

        // $productImage->delete();
        // return response()->noContent();

        $productId = $productImage->product_id;
        $wasPrimary = $productImage->is_primary;

        // 檢查是否為最後一張圖片
        $imageCount = ProductImage::where('product_id', $productId)->count();

        if ($imageCount <= 1) {
            return response()->json([
                'message' => '產品至少需要保留一張圖片'
            ], 422);
        }

        DB::transaction(function () use ($productImage, $productId, $wasPrimary) {
            // 刪除檔案
            if ($productImage->image) {
                Storage::disk('public')->delete($productImage->image);
            }

            // 刪除記錄
            $productImage->delete();

            // 如果刪除的是主圖，自動設定新主圖
            if ($wasPrimary) {
                $newPrimary = ProductImage::where('product_id', $productId)
                    ->orderBy('sort_order')
                    ->first();

                if ($newPrimary) {
                    $newPrimary->update(['is_primary' => true]);
                }
            }
        });

        // 回傳更新後的所有圖片
        $allImages = ProductImage::where('product_id', $productId)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'message' => '刪除成功',
            'images' => $allImages,
            'auto_set_primary' => $wasPrimary
        ], 200);
    }

    private function fetchIndexData(Request $request)
    {
        // $productImages = Product::product_images()
    }

    public function destroyMany(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct', 'exists:product_images,id']
        ]);

        $ids = $validated['ids'];

        $deleted = DB::transaction(function () use ($ids) {
            $datas = ProductImage::whereIn('id', $ids)->get();
            foreach ($datas as $data) {
                if ($data->image && Storage::disk('public')->exists($data->image)) {
                    Storage::disk('public')->delete($data->image);
                }
            }
            return ProductImage::whereIn('id', $ids)->delete();
        });

        return response()->json(['deleted' => $deleted], 200);
    }

    public function setPrimary(ProductImage $productImage)
    {
        DB::transaction(function () use ($productImage) {
            ProductImage::where('product_id', $productImage->product_id)
                ->where('id', '!=', $productImage->id)
                ->update(['is_primary' => false]);

            $productImage->update(['is_primary' => true]);
        });

        $productImage = ProductImage::where('product_id', $productImage->product_id)
            ->orderBy('sort_order')
            ->get();

        return response()->json($productImage, 200);
    }
}
