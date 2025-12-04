<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Random 15 ürün getir
        $randomProducts = \App\Models\Product::where('is_active', true)
            ->with(['images', 'categories'])
            ->inRandomOrder()
            ->limit(15)
            ->get();
        
        // Random 3 blog yazısı getir
        $randomPosts = \App\Models\Post::where('is_published', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();
        
        return view('frontend.home', compact('randomProducts', 'randomPosts'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('frontend.about');
    }
}
