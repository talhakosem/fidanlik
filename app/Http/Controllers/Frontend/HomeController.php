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
        return view('frontend.home');
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('frontend.about');
    }
}
