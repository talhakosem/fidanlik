<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = Post::where('is_published', true);

        // Kategori filtresi
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(9);

        return view('frontend.blog.index', compact('posts'));
    }

    /**
     * Display the specified blog post.
     * This method also handles category and product slugs.
     */
    public function show($slug)
    {
        // Önce kategori kontrolü yap
        $category = \App\Models\Category::where('slug', $slug)->first();
        if ($category) {
            $productController = new \App\Http\Controllers\Frontend\ProductController();
            return $productController->index(request(), $slug);
        }

        // Sonra ürün kontrolü yap
        $product = \App\Models\Product::where('slug', $slug)
            ->where('is_active', true)
            ->first();
        if ($product) {
            $productController = new \App\Http\Controllers\Frontend\ProductController();
            return $productController->show($slug);
        }

        // Son olarak blog post kontrolü
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('frontend.blog.show', compact('post'));
    }
}
