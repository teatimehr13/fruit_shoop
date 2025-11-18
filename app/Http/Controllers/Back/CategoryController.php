<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = $this->fetchIndexData();
        return Inertia::render(
            'Back/Category/Index',
            [
                'categories' => $categories
            ]
        );
    }

    public function create()
    {
        //
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        // $maxOrder = Category::max('sort_order');
        // $validated['sort_order'] = ($maxOrder ?? 0) + 1;
        // $category = Category::create($validated);

        $category = DB::transaction(function () use ($validated) {
            // 鎖表/鎖行，確保 max() 在不被別的同時操作干預
            $next = Category::lockForUpdate()->max('sort_order');
            $validated['sort_order'] = ($next ?? 0) + 1;

            return Category::create($validated)->fresh(); // 取回含 timestamps 的最新狀態
        });

        return response()->json($category, 201); //新增201
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        // 若會動到 sort_order、或有其他需要同步調整的欄位，建議用交易包起來
        DB::transaction(function () use ($category, $validated) {
            $category->update($validated);
        });

        // 取回最新狀態（含 cas
        $category->refresh();

        return response()->json($category, 200); //更新200
    }

    public function destroy(Category $category)
    {
        if ($category->subcategories()->count() !== 0) {
            return response()->json(['msg' => 'subcategory exist'], 422);
        }

        if (!$category->delete()) {
            return response()->json(['msg' => 'Failed to delete category'], 500);
        }

        return response()->json(['msge' => 'success'], 200);
    }

    private function fetchIndexData()
    {
        // $categories = Category::with('subcategories')->orderBy('sort_order')->get();
        $categories = Category::query()
            ->select(['id', 'name', 'sort_order', 'is_enabled'])
            ->orderBy('sort_order')
            ->with([
                'subcategories' => fn($q) => $q
                    ->select(['id', 'category_id', 'name', 'sort_order', 'is_enabled'])
                    ->orderBy('sort_order')
            ])
            ->get();
        // ->paginate(20)
        // ->withQueryString();

        return $categories;
    }

    public function indexJson()
    {
        $categories = $this->fetchIndexData();
        return response()->json(['data' => $categories]);
    }


}
