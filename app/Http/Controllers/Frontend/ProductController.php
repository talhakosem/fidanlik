<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display products by category.
     */
    public function index(Request $request, $slug = null)
    {
        $query = Product::where('is_active', true)
            ->with(['images', 'categories']);

        // Kategori filtresi
        if ($slug) {
            $category = Category::where('slug', $slug)->firstOrFail();
            $query->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            });
        } else {
            $category = null;
        }

        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(12);

        return view('frontend.products.index', compact('products', 'category'));
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['images', 'categories'])
            ->firstOrFail();

        // İlgili ürünler (aynı kategoriden)
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->with(['images'])
            ->latest()
            ->limit(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}
