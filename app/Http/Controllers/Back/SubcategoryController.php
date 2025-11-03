<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SubcategoryController extends Controller
{
    public function index(Request $request) {}

    public function create()
    {
        //
    }

    public function store(SubCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $subcategory = DB::transaction(function () use ($validated, $category) {
            // 鎖表/鎖行，確保 max() 在不被別的同時操作干預
            $next = Subcategory::lockForUpdate()->max('sort_order');
            $validated['sort_order'] = ($next ?? 0) + 1;

            return $category->subcategories()->create($validated)->fresh();
            // return Subcategory::create($validated)->fresh(); // 取回含 timestamps 的最新狀態
        });

        return response()->json($subcategory, 201); //新增201
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(SubCategoryRequest $request, Subcategory $subcategory)
    {
        $validated = $request->validated();
        // 若會動到 sort_order、或有其他需要同步調整的欄位，建議用交易包起來
        DB::transaction(function () use ($subcategory, $validated) {
            $subcategory->update($validated);
        });

        // 取回最新狀態（含 cas
        $subcategory->refresh();
        return response()->json($subcategory, 200); //更新200
    }

    public function destroy(Subcategory $subcategory)
    {
        if (!$subcategory->delete()) {
            return response()->json(['msg' => 'Failed to delete category'], 500);
        }

        return response()->json(['msge' => 'success'], 200);
    }
}
