@extends('layouts.admin')

@section('title', 'Ürünler')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Ürünler</h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Yeni Ürün
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">Görsel</th>
                                <th>Ürün Adı</th>
                                <th>Kategori</th>
                                <th>Agrolidya Linki</th>
                                <th>Stok</th>
                                <th>Durum</th>
                                <th>Tarih</th>
                                <th class="text-end">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        @if($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="rounded"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        <small class="text-muted">{{ $product->slug }}</small>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge bg-info">{{ $product->category->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->agrolidya_link)
                                            <a href="{{ $product->agrolidya_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-box-arrow-up-right me-1"></i>Linke Git
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->stock > 0)
                                            <span class="badge bg-success">{{ $product->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">Stokta Yok</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-warning">Pasif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $product->created_at->format('d.m.Y') }}</small><br>
                                        <small class="text-muted">{{ $product->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="btn btn-sm btn-outline-secondary me-2"
                                           title="Düzenle">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger delete-product-btn" 
                                                data-product-id="{{ $product->id }}"
                                                title="Sil">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        
                                        <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-inbox display-4 text-muted"></i>
                                        <p class="text-muted mb-0 mt-2">Henüz ürün yok.</p>
                                        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary mt-2">
                                            İlk Ürünü Oluştur
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($products->hasPages())
        <div class="col-12 mt-4">
            <div>
                <nav class="mt-7 mt-lg-10">
                    <ul class="pagination mb-0">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">Previous</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}">Previous</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $currentPage = $products->currentPage();
                            $lastPage = $products->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                        @endphp

                        @if($startPage > 1)
                            <li class="page-item"><a class="page-link" href="{{ $products->url(1) }}">1</a></li>
                            @if($startPage > 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif

                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $currentPage)
                                <li class="page-item"><a class="page-link active" href="#!">{{ $page }}</a></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $products->url($page) }}">{{ $page }}</a></li>
                            @endif
                        @endfor

                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item"><a class="page-link" href="{{ $products->url($lastPage) }}">{{ $lastPage }}</a></li>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($products->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}">Next</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">Next</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-product-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            if (confirm('Bu ürünü silmek istediğinize emin misiniz? Ürün görselleri de silinecektir.')) {
                document.getElementById('delete-form-' + productId).submit();
            }
        });
    });
});
</script>
@endpush

