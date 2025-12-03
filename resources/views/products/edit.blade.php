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

                <!-- Ürün Görselleri -->
                <div class="mt-4">
                    <h5 class="mb-3">Ürün Görselleri</h5>
                    
                    <!-- Görsel Yükleme -->
                    <div class="mb-4">
                        <label for="product-images" class="form-label">Yeni Görsel Ekle</label>
                        <input type="file" 
                               class="form-control" 
                               id="product-images" 
                               name="images[]" 
                               multiple 
                               accept="image/*">
                        <small class="form-text text-muted">Birden fazla görsel seçebilirsiniz (JPEG, PNG, JPG, GIF, WEBP - Max: 2MB)</small>
                        <button type="button" class="btn btn-primary mt-2" id="upload-images-btn">
                            <i class="bi bi-upload me-2"></i>Görselleri Yükle
                        </button>
                    </div>

                    <!-- Görsel Listesi -->
                    <div id="images-container" class="row g-3">
                        @foreach($product->images as $image)
                            <div class="col-md-3 col-sm-4 col-6 image-item" data-image-id="{{ $image->id }}">
                                <div class="card position-relative">
                                    <img src="{{ asset('storage/' . $image->image) }}" 
                                         class="card-img-top" 
                                         alt="Ürün Görseli"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Sıra: {{ $image->sort_order }}</small>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger delete-image-btn" 
                                                    data-image-id="{{ $image->id }}"
                                                    data-product-id="{{ $product->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-secondary drag-handle" style="cursor: move;">
                                            <i class="bi bi-grip-vertical"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($product->images->isEmpty())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>Henüz görsel eklenmemiş.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12">
        <div class="card card-lg">
            <div class="card-body p-6 d-flex flex-column gap-4">
                <!-- Ana Kategori (Tek Seçim - Geriye Dönük Uyumluluk) -->
                <div>
                    <label for="category_id" class="form-label">Ana Kategori</label>
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
                    <small class="form-text text-muted">Geriye dönük uyumluluk için (opsiyonel)</small>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Çoklu Kategoriler -->
                <div>
                    <label class="form-label">Kategoriler</label>
                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                        @php
                            $selectedCategories = old('categories', $product->categories->pluck('id')->toArray());
                        @endphp
                        @foreach($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="categories[]" 
                                       value="{{ $category->id }}" 
                                       id="category_{{ $category->id }}"
                                       {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                <label class="form-check-label" for="category_{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <small class="form-text text-muted">Birden fazla kategori seçebilirsiniz</small>
                    @error('categories')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
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

    // Görsel yükleme
    const uploadBtn = document.getElementById('upload-images-btn');
    const imageInput = document.getElementById('product-images');
    const productId = {{ $product->id }};

    uploadBtn.addEventListener('click', function() {
        const files = imageInput.files;
        if (files.length === 0) {
            alert('Lütfen en az bir görsel seçin.');
            return;
        }

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }

        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Yükleniyor...';

        fetch(`/products/${productId}/images`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Görsel yükleme hatası: ' + (data.message || 'Bilinmeyen hata'));
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="bi bi-upload me-2"></i>Görselleri Yükle';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Görsel yükleme sırasında bir hata oluştu.');
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="bi bi-upload me-2"></i>Görselleri Yükle';
        });
    });

    // Görsel silme
    document.querySelectorAll('.delete-image-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Bu görseli silmek istediğinize emin misiniz?')) {
                return;
            }

            const imageId = this.dataset.imageId;
            const productId = this.dataset.productId;

            fetch(`/products/${productId}/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`[data-image-id="${imageId}"]`).remove();
                    if (document.querySelectorAll('.image-item').length === 0) {
                        location.reload();
                    }
                } else {
                    alert('Görsel silme hatası: ' + (data.message || 'Bilinmeyen hata'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Görsel silme sırasında bir hata oluştu.');
            });
        });
    });

    // Görsel sıralama (SortableJS)
    const imagesContainer = document.getElementById('images-container');
    if (imagesContainer && imagesContainer.children.length > 0) {
        new Sortable(imagesContainer, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                const imageIds = Array.from(imagesContainer.querySelectorAll('.image-item')).map(item => item.dataset.imageId);
                
                fetch(`/products/${productId}/images/reorder`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ image_ids: imageIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Sıra numaralarını güncelle
                        imagesContainer.querySelectorAll('.image-item').forEach((item, index) => {
                            const sortOrderElement = item.querySelector('.text-muted');
                            if (sortOrderElement) {
                                sortOrderElement.textContent = `Sıra: ${index + 1}`;
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
});
</script>
@endpush

