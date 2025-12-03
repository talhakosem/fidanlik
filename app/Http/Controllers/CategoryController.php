<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::where('parent_id', 0)
            ->orderBy('name')
            ->get();

        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer|min:0',
            'show_in_top_menu' => 'nullable|boolean',
            'show_in_footer_menu' => 'nullable|boolean',
        ]);

        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Slug unique kontrolü
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Category::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Görsel yükleme
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        // Parent ID (0 veya geçerli bir ID olmalı)
        if (isset($data['parent_id']) && $data['parent_id'] > 0) {
            // Parent ID'nin geçerli olduğunu kontrol et
            if (!Category::where('id', $data['parent_id'])->exists()) {
                $data['parent_id'] = 0;
            }
        } else {
            $data['parent_id'] = 0;
        }

        // Boolean değerler
        $data['show_in_top_menu'] = $request->has('show_in_top_menu');
        $data['show_in_footer_menu'] = $request->has('show_in_footer_menu');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::where('parent_id', 0)
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer|min:0',
            'show_in_top_menu' => 'nullable|boolean',
            'show_in_footer_menu' => 'nullable|boolean',
        ]);

        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Slug unique kontrolü (kendi ID'si hariç)
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Görsel yükleme
        if ($request->hasFile('image')) {
            // Eski görseli sil
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        // Parent ID (0 veya geçerli bir ID olmalı)
        if (isset($data['parent_id']) && $data['parent_id'] > 0) {
            // Parent ID'nin geçerli olduğunu kontrol et
            if (!Category::where('id', $data['parent_id'])->exists()) {
                $data['parent_id'] = 0;
            }
        } else {
            $data['parent_id'] = 0;
        }

        // Boolean değerler
        $data['show_in_top_menu'] = $request->has('show_in_top_menu');
        $data['show_in_footer_menu'] = $request->has('show_in_footer_menu');

        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Alt kategorileri kontrol et
        if ($category->children()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Bu kategorinin alt kategorileri var. Önce alt kategorileri silin.');
        }

        // Görseli sil
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori silindi.');
    }
}
