<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $about = About::query()
            ->get(['id', 'title', 'content', 'image']);

        
        return Inertia::render('Back/About/Index', ['about' => $about]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(AboutRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $dir = "about/";
            $fileName = $request->file('image')->hashName();
            $path = $request->file('image')->storeAs($dir, $fileName, 'public');
        }

        $validated['image'] = $path;
        $about = About::create($validated);

        return response()->json($about, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(About $about)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(About $about)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AboutRequest $request, About $about)
    {
        $validated = $request->validated();
        $oldPath = $about->image;  // 相對路徑
        $newPath = null;


        if ($request->hasFile('image')) {
            $dir = "about/";
            $newPath = $request->file('image')->store($dir, 'public'); //自動生成不重覆檔名
            $validated['image'] = $newPath;
        }

        $about->update($validated);

        if ($newPath && $oldPath && $oldPath !== $newPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // if ($request->boolean('remove_image') && $oldPath) {
        //     $product->update(['image' => null]);
        //     Storage::disk('public')->delete($oldPath);
        // }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        if ($about->image && Storage::disk('public')->exists($about->image)) {
            Storage::disk('public')->delete($about->image);
        }

        $about->delete();
        return response()->noContent(); // 204
    }

    public function save(AboutRequest $request)
    {
        $validated = $request->validated();
        $about = About::first();
        $old = $about?->image;

        //上傳新圖片
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('about', 'public');
        }
        $about ? $about->update($validated) : $about = About::create($validated);

        //刪舊圖片
        if ($old && $old !== $about->image && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        //僅刪除，不換圖上傳
        if ($request->boolean('rm_img') && $old) {
            $about->update(['image' => null]);
            if (Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
        }



        return response()->json($about->fresh(), 200);
    }
}
