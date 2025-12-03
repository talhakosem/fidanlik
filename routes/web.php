<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;


Route::get('/', [\App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');

// Frontend Blog Routes
Route::get('blog', [\App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('frontend.blog.index');
Route::get('blog/{slug}', [\App\Http\Controllers\Frontend\BlogController::class, 'show'])->name('frontend.blog.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::post('products/{product}/images', [\App\Http\Controllers\ProductController::class, 'storeImage'])->name('products.images.store');
    Route::delete('products/{product}/images/{productImage}', [\App\Http\Controllers\ProductController::class, 'deleteImage'])->name('products.images.delete');
    Route::post('products/{product}/images/reorder', [\App\Http\Controllers\ProductController::class, 'updateImageOrder'])->name('products.images.reorder');
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
