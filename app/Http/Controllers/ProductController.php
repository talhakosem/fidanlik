<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(20);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'agrolidya_link' => 'nullable|url|max:500',
            'meta_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'min_quantity' => 'nullable|integer|min:1',
            'stock' => 'nullable|integer|min:0',
            'sku' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|integer',
            'delivery_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Slug unique kontrolü
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Product::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Varsayılan değerler
        $data['min_quantity'] = $data['min_quantity'] ?? 1;
        $data['stock'] = $data['stock'] ?? 0;
        $data['is_active'] = $request->has('is_active');

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Ürün başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'agrolidya_link' => 'nullable|url|max:500',
            'meta_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'min_quantity' => 'nullable|integer|min:1',
            'stock' => 'nullable|integer|min:0',
            'sku' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|integer',
            'delivery_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        // Slug oluştur
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Slug unique kontrolü (kendi ID'si hariç)
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Product::where('slug', $data['slug'])->where('id', '!=', $product->id)->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Varsayılan değerler
        $data['min_quantity'] = $data['min_quantity'] ?? 1;
        $data['stock'] = $data['stock'] ?? 0;
        $data['is_active'] = $request->has('is_active');

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Ürün güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Ürün görsellerini sil
        foreach ($product->images as $image) {
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
            $image->delete();
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Ürün silindi.');
    }
}
