@extends('layouts.admin')

@section('title', 'Ürün Düzenle')

@section('content')
<div class="row mb-8">
    <div class="col-md-12">
        <div>
            <h2>Ürün Düzenle</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-inherit">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-inherit">Ürünler</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Düzenle</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<form action="{{ route('products.update', $product) }}" method="POST" class="row g-6">
    @csrf
    @method('PUT')
    
    <div class="col-lg-8 col-12">
        <div class="card card-lg">
            <div class="card-body p-6 d-flex flex-column gap-4">
                <!-- Ürün Adı -->
                <div>
                    <label for="name" class="form-label">Ürün Adı *</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $product->name) }}"
                           placeholder="Ürün adı" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" 
                           class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug', $product->slug) }}"
                           placeholder="urun-adi">
                    <small class="form-text text-muted">Boş bırakılırsa ürün adından otomatik oluşturulur</small>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Açıklama -->
                <div>
                    <label for="description" class="form-label">Açıklama</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="8"
                              placeholder="Ürün açıklaması">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
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
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" 
                            id="category_id" 
                            name="category_id">
                        <option value="">Kategori Seçin</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Agrolidya Linki -->
                <div>
                    <label for="agrolidya_link" class="form-label">Agrolidya Linki</label>
                    <input type="url" 
                           class="form-control @error('agrolidya_link') is-invalid @enderror" 
                           id="agrolidya_link" 
                           name="agrolidya_link"
                           value="{{ old('agrolidya_link', $product->agrolidya_link) }}"
                           placeholder="https://agrolidya.com/urun/...">
                    <small class="form-text text-muted">E-ticaret sitesindeki ürün linkini girin</small>
                    @error('agrolidya_link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label for="stock" class="form-label">Stok Miktarı</label>
                    <input type="number" 
                           min="0"
                           class="form-control @error('stock') is-invalid @enderror" 
                           id="stock" 
                           name="stock"
                           value="{{ old('stock', $product->stock) }}">
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Minimum Sipariş Adedi -->
                <div>
                    <label for="min_quantity" class="form-label">Minimum Sipariş Adedi</label>
                    <input type="number" 
                           min="1"
                           class="form-control @error('min_quantity') is-invalid @enderror" 
                           id="min_quantity" 
                           name="min_quantity"
                           value="{{ old('min_quantity', $product->min_quantity) }}">
                    @error('min_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stok Kodu (SKU) -->
                <div>
                    <label for="sku" class="form-label">Stok Kodu (SKU)</label>
                    <input type="text" 
                           class="form-control @error('sku') is-invalid @enderror" 
                           id="sku" 
                           name="sku"
                           value="{{ old('sku', $product->sku) }}"
                           placeholder="SKU-001">
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Teslimat Tarihi -->
                <div>
                    <label for="delivery_date" class="form-label">Teslimat Tarihi</label>
                    <input type="date" 
                           class="form-control @error('delivery_date') is-invalid @enderror" 
                           id="delivery_date" 
                           name="delivery_date"
                           value="{{ old('delivery_date', $product->delivery_date ? $product->delivery_date->format('Y-m-d') : '') }}">
                    @error('delivery_date')
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
                           value="{{ old('meta_title', $product->meta_title) }}"
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
                              placeholder="Meta açıklama">{{ old('meta_description', $product->meta_description) }}</textarea>
                    @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Aktif -->
                <div>
                    <div class="form-check form-switch ps-0">
                        <label class="form-check-label" for="is_active">Aktif</label>
                        <input class="form-check-input ms-auto" 
                               type="checkbox" 
                               role="switch" 
                               id="is_active" 
                               name="is_active"
                               value="1"
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    </div>
                </div>

                <!-- Buttons -->
                <div>
                    <div class="d-flex flex-row gap-2">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="bi bi-check-circle me-2"></i>Güncelle
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-light w-100">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ürün adı değiştiğinde slug'ı otomatik oluştur
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.manual !== 'true') {
            const slug = this.value
                .toLowerCase()
                .replace(/ğ/g, 'g')
                .replace(/ü/g, 'u')
                .replace(/ş/g, 's')
                .replace(/ı/g, 'i')
                .replace(/ö/g, 'o')
                .replace(/ç/g, 'c')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        }
    });
    
    // Slug manuel değiştirildiğinde işaretle
    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
});
</script>
@endpush

