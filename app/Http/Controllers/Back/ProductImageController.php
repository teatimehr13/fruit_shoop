<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImageRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductImageController extends Controller
{
    public function index(Product $product)
    {
        $productImages = $product->productImages()->get();
        return response()->json($productImages);
    }

    public function create()
    {
        //
    }

    public function store(ProductImageRequest $request, Product $product)
    {
        $validated = $request->validated()['productImages'];
        $rows = [];
        foreach ($validated as $row) {
            // 先存檔案，拿相對路徑
            $path = $row['image']->store("products/{$product->id}", 'public');

            // 建資料（用關聯建立，自動帶 product_id）
            $isPrimary = !empty($row['is_primary']);
            // $touchedPrimary = $touchedPrimary || $isPrimary;

            $rows[] = $product->productImages()->create([
                'image'      => $path,
                'alt_text'   => $row['alt_text'] ?? null,
                'is_primary' => $isPrimary,
            ]);
        }

        return response()->json(['data' => $rows], 201);

        // $created = DB::transaction(function () use ($product, $validated) {
        //     $rows = [];
        //     $touchedPrimary = false;

        //     foreach ($validated as $row) {
        //         // 先存檔案，拿相對路徑
        //         $path = $row['image']->store("products/{$product->id}", 'public');

        //         // 建資料（用關聯建立，自動帶 product_id）
        //         $isPrimary = !empty($row['is_primary']);
        //         // $touchedPrimary = $touchedPrimary || $isPrimary;

        //         $rows[] = $product->images()->create([
        //             'image'      => $path,  
        //             'alt_text'   => $row['alt_text'] ?? null,
        //             'is_primary' => $isPrimary,
        //         ]);
        //     }

        //     // 3) 如果本批有人設為封面，把其他張的 is_primary 清 0
        //     // if ($touchedPrimary) {
        //     //     $product->images()
        //     //         ->whereNotIn('id', collect($rows)->pluck('id'))
        //     //         ->update(['is_primary' => false]);
        //     // }
        //     return $rows;
        // });

        // return response()->json(['data' => $created], 201);
    }

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

    private function fetchIndexData(Request $request)
    {
        // $productImages = Product::product_images()
    }
}
