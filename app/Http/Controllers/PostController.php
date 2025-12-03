<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'comments_enabled' => 'nullable|boolean',
        ]);

        // Görsel yükleme
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        // Tags'i array'e çevir
        if (isset($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        $data['slug'] = Str::slug($data['title']);
        $data['user_id'] = Auth::id();
        $data['is_published'] = $request->has('is_published');
        $data['comments_enabled'] = $request->has('comments_enabled');

        Post::create($data);

        return redirect()->route('posts.index')
            ->with('success', 'Gönderi başarıyla oluşturuldu.');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_description' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'comments_enabled' => 'nullable|boolean',
        ]);

        // Görsel yükleme
        if ($request->hasFile('cover_image')) {
            // Eski görseli sil
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        // Tags'i array'e çevir
        if (isset($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->has('is_published');
        $data['comments_enabled'] = $request->has('comments_enabled');

        $post->update($data);

        return redirect()->route('posts.index')
            ->with('success', 'Gönderi güncellendi.');
    }

    public function destroy(Post $post)
    {
        // Görseli sil
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Gönderi silindi.');
    }
}