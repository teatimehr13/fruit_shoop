<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductOptionRequest;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/** 
 * back.products.options.index
 * back.products.options.store
 * back.options.destroy
 * back.options.update
 */

class ProductOptionController extends Controller
{
    public function index(Product $product)
    {
        $productOptions = $product->productOptions()->get();
        return response()->json($productOptions);
    }

    public function create()
    {
        //
    }

    public function store(ProductOptionRequest $request)
    {
        $validated = $request->validated();
        $productOption = ProductOption::create($validated);
        return response()->json($productOption, 201); //新增201
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(ProductOptionRequest $request, ProductOption $productOption)
    {
        $validated = $request->validated();
        $productOption->update($validated);
        return response()->json($productOption, 200);
    }

    public function destroy(ProductOption $productOption)
    {
        $productOption->delete();
        return response()->noContent();
    }

    private function fetchIndexData(Request $request) {}

    public function save(ProductOptionRequest $request, Product $product)
    {
        $validated = $request->validated();
        // return response()->json($request->validated());

        DB::transaction(function () use ($validated, $product) {
            // 1. 新增與更新
            foreach ($validated['options'] as $optionData) {   
                if (!empty($optionData['id'])) {
                    // 更新：不改變 sort_order
                    ProductOption::where('id', $optionData['id'])->update([
                        'option_text' => $optionData['option_text'],
                        'original_price' => $optionData['original_price'],
                        'price' => $optionData['price'],
                        'inventory' => $optionData['inventory'],
                        'is_enabled' => $optionData['is_enabled'] ?? true,
                    ]);
                } else {
                    // 新增：計算新的 sort_order
                    $next = ProductOption::where('product_id', $product->id)
                        ->lockForUpdate()
                        ->max('sort_order');

                    $product->productOptions()->create([
                        'option_text' => $optionData['option_text'],
                        'original_price' => $optionData['original_price'],
                        'price' => $optionData['price'],
                        'inventory' => $optionData['inventory'],
                        'is_enabled' => $optionData['is_enabled'] ?? true,
                        'sort_order' => ($next ?? 0) + 1,
                    ]);
                }
            }

            // 2. 處理刪除
            if (!empty($validated['deleted_ids'])) {
                ProductOption::whereIn('id', $validated['deleted_ids'])->delete();
            }
        });

        $options = $product->productOptions()->orderBy('sort_order', 'asc')->get();

        return response()->json(['message' => '保存成功', 'options' => $options], 201);
    }
}
