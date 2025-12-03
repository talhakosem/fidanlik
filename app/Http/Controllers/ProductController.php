<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
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
        $products = Product::latest()
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
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
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

        // Çoklu kategorileri ayır
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        $product = Product::create($data);

        // Çoklu kategorileri ekle
        if (!empty($categories)) {
            $product->categories()->sync($categories);
        }

        return redirect()->route('products.index')
            ->with('success', 'Ürün başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load(['images', 'categories']);

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
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
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

        // Çoklu kategorileri ayır
        $categories = $data['categories'] ?? [];
        unset($data['categories']);

        $product->update($data);

        // Çoklu kategorileri güncelle
        $product->categories()->sync($categories);

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

    /**
     * Store product images
     */
    public function storeImage(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $maxSortOrder = $product->images()->max('sort_order') ?? 0;

        foreach ($request->file('images') as $image) {
            $imagePath = $image->store('products', 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $imagePath,
                'sort_order' => ++$maxSortOrder,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Görseller başarıyla yüklendi.',
        ]);
    }

    /**
     * Delete product image
     */
    public function deleteImage(Product $product, ProductImage $productImage)
    {
        // Görselin bu ürüne ait olduğunu kontrol et
        if ($productImage->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bu görsel bu ürüne ait değil.',
            ], 403);
        }

        // Dosyayı sil
        if ($productImage->image && Storage::disk('public')->exists($productImage->image)) {
            Storage::disk('public')->delete($productImage->image);
        }

        // Veritabanından sil
        $productImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Görsel başarıyla silindi.',
        ]);
    }

    /**
     * Update image sort order
     */
    public function updateImageOrder(Request $request, Product $product)
    {
        $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'exists:product_images,id',
        ]);

        foreach ($request->image_ids as $index => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $product->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sıralama güncellendi.',
        ]);
    }
}
