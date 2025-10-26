<?php

namespace App\Http\Controllers\Back;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductOptionRequest;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Inertia\Inertia;

/** 
 * back.products.options.index
 * back.products.options.store
 * back.options.destroy
 * back.options.update
*/

class ProductOptionController extends Controller
{
    public function index(Product $product){
        $productOptions = $product->productOptions()->get();
        return response()->json($productOptions);
    }

    public function create()
    {
        //
    }

    public function store(ProductOptionRequest $request){
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

    private function fetchIndexData(Request $request){

    }
}
