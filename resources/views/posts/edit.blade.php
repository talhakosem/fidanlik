@extends('layouts.admin')

@section('title', 'Blog Yazısını Düzenle')

@push('styles')
<link href="{{ admin_asset('libs/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ admin_asset('libs/@yaireo/tagify/dist/tagify.css') }}" />
<link href="{{ admin_asset('libs/quill/dist/quill.snow.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row mb-8">
    <div class="col-md-12">
        <div>
            <h2>Blog Yazısını Düzenle</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-inherit">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-inherit">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Düzenle</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="row g-6">
    @csrf
    @method('PUT')
    
    <div class="col-lg-8 col-12">
        <div class="card card-lg">
            <div class="card-body p-6 d-flex flex-column gap-4">
                <!-- Başlık -->
                <div>
                    <label for="title" class="form-label">Başlık *</label>
                    <input type="text" 
                           class="form-control @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $post->title) }}"
                           placeholder="Yazı başlığı" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Slug: {{ $post->slug }}</small>
                </div>

                <!-- Kapak Görseli -->
                <div>
                    <label class="form-label">Kapak Görseli</label>
                    @if($post->cover_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $post->cover_image) }}" 
                                 alt="Mevcut Görsel" 
                                 class="img-thumbnail" 
                                 style="max-width: 200px;">
                        </div>
                    @endif
                    <input type="file" 
                           class="form-control @error('cover_image') is-invalid @enderror" 
                           id="cover_image" 
                           name="cover_image"
                           accept="image/*">
                    <small class="form-text text-muted">Maksimum 2MB - JPG, PNG, GIF formatları</small>
                    @error('cover_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kısa Açıklama -->
                <div>
                    <label for="short_description" class="form-label">Kısa Açıklama</label>
                    <textarea class="form-control @error('short_description') is-invalid @enderror" 
                              id="short_description" 
                              name="short_description" 
                              rows="3"
                              placeholder="Kısa bir açıklama yazın">{{ old('short_description', $post->short_description) }}</textarea>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- İçerik -->
                <div>
                    <label class="form-label">İçerik *</label>
                    <div id="editor" style="height: 300px;">{!! old('content', $post->content) !!}</div>
                    <textarea name="content" id="content" class="d-none @error('content') is-invalid @enderror" required>{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="card card-lg">
            <div class="card-body p-6 d-flex flex-column gap-4">
                <!-- Kategori -->
                <div>
                    <label for="category" class="form-label">Kategori</label>
                    <select class="form-select @error('category') is-invalid @enderror" 
                            id="category" 
                            name="category">
                        <option value="">Kategori Seçin</option>
                        <option value="Tarifler" {{ old('category', $post->category) == 'Tarifler' ? 'selected' : '' }}>Tarifler</option>
                        <option value="Şirket" {{ old('category', $post->category) == 'Şirket' ? 'selected' : '' }}>Şirket</option>
                        <option value="Perakende" {{ old('category', $post->category) == 'Perakende' ? 'selected' : '' }}>Perakende</option>
                        <option value="Teknoloji" {{ old('category', $post->category) == 'Teknoloji' ? 'selected' : '' }}>Teknoloji</option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tags -->
                <div>
                    <label class="form-label" for="tags">Etiketler</label>
                    <input type="text" 
                           class="form-control @error('tags') is-invalid @enderror" 
                           id="tags" 
                           name="tags"
                           value="{{ old('tags', is_array($post->tags) ? implode(', ', $post->tags) : '') }}"
                           placeholder="Virgülle ayırın: etiket1, etiket2">
                    <small class="form-text text-muted">Örnek: market, alışveriş, organik</small>
                    @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- SEO Başlık -->
                <div>
                    <label for="meta_title" class="form-label">SEO Başlık</label>
                    <input type="text" 
                           class="form-control @error('meta_title') is-invalid @enderror" 
                           id="meta_title" 
                           name="meta_title"
                           value="{{ old('meta_title', $post->meta_title) }}"
                           placeholder="Meta başlık">
                    @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- SEO Açıklama -->
                <div>
                    <label for="meta_description" class="form-label">SEO Açıklama</label>
                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                              id="meta_description" 
                              name="meta_description" 
                              rows="3"
                              placeholder="Meta açıklama">{{ old('meta_description', $post->meta_description) }}</textarea>
                    @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Yayınla -->
                <div>
                    <div class="form-check form-switch ps-0">
                        <label class="form-check-label" for="is_published">Yayınla</label>
                        <input class="form-check-input ms-auto" 
                               type="checkbox" 
                               role="switch" 
                               id="is_published" 
                               name="is_published"
                               value="1"
                               {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                    </div>
                </div>

                <!-- Yorumları Aç -->
                <div>
                    <div class="form-check form-switch ps-0">
                        <label class="form-check-label" for="comments_enabled">Yorumları Etkinleştir</label>
                        <input class="form-check-input ms-auto" 
                               type="checkbox" 
                               role="switch" 
                               id="comments_enabled" 
                               name="comments_enabled"
                               value="1"
                               {{ old('comments_enabled', $post->comments_enabled) ? 'checked' : '' }}>
                    </div>
                </div>

                <!-- Buttons -->
                <div>
                    <div class="d-flex flex-row gap-2">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="bi bi-check-circle me-2"></i>Güncelle
                        </button>
                        <a href="{{ route('posts.index') }}" class="btn btn-light w-100">
                            <i class="bi bi-x-circle me-2"></i>İptal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ admin_asset('libs/quill/dist/quill.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quill Editor
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Form submit olduğunda Quill içeriğini textarea'ya aktar
    document.querySelector('form').addEventListener('submit', function() {
        document.querySelector('#content').value = quill.root.innerHTML;
    });
});
</script>
@endpush
