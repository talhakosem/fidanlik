@extends('layouts.frontend')

@section('title', $category ? $category->name . ' Ürünleri' : 'Ürünler')

@section('meta_description', $category ? ($category->meta_description ?? $category->description) : 'Tüm ürünlerimizi keşfedin')

@section('content')
<!--Pageheader start-->
<section class="bg-light d-flex flex-column align-items-center justify-content-center" style="
    background:
        linear-gradient(45deg, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.22)),
        url({{ $category && $category->image ? asset('storage/' . $category->image) : frontend_asset('images/jpg/page-header-img.jpg') }}) no-repeat;
    background-position: center;
    background-size: cover;
">
    <div class="container">
        <div class="row align-items-center py-6">
            <div class="col-lg-6">
                <div class="position-relative z-1">
                    <h1 class="mb-4 text-white">{{ $category ? $category->name : 'Ürünler' }}</h1>
                    <p class="lead text-white">
                        {{ $category ? 'Bu kategorideki tüm ürünlerimizi keşfedin' : 'Tüm ürünlerimizi keşfedin' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="w-lg-50 w-100 position-absolute end-0 top-0 object-fit-cover"></div>
</section>
<!--Pageheader end-->

<!--Breadcrumb start-->
<div class="container">
    <div class="row">
        <div class="col-12 py-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Ana Sayfa</a></li>
                    @if($category)
                        <li class="breadcrumb-item"><a href="{{ route('frontend.products.index') }}">Ürünler</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">Ürünler</li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
<!--Breadcrumb end-->

<!--Product listing start-->
<section class="py-lg-8 py-6">
    <div class="container">
        @if($products->count() > 0)
            <div class="row gy-4">
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card border-0 h-100">
                            <!--Product Image-->
                            <a href="{{ url('/' . $product->slug) }}" class="text-inherit">
                                <div class="position-relative">
                                    @if($product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="card-img-top img-fluid" 
                                             style="height: 250px; object-fit: cover;">
                                    @else
                                        <img src="{{ frontend_asset('images/png/collection-img-1.png') }}" 
                                             alt="{{ $product->name }}" 
                                             class="card-img-top img-fluid" 
                                             style="height: 250px; object-fit: cover;">
                                    @endif
                                    
                                    @if($product->categories->count() > 0)
                                        <span class="badge bg-info position-absolute top-0 start-0 m-2">
                                            {{ $product->categories->first()->name }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                            
                            <!--Product Body-->
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="{{ url('/' . $product->slug) }}" class="text-inherit text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                
                                @if($product->description)
                                    <p class="card-text text-muted small">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($product->description), 100) }}
                                    </p>
                                @endif
                                
                                <div class="mt-auto">
                                    @if($product->agrolidya_link)
                                        <a href="{{ $product->agrolidya_link }}" 
                                           target="_blank" 
                                           class="btn btn-primary w-100">
                                            Agrolidya'da Gör
                                        </a>
                                    @else
                                        <a href="{{ url('/' . $product->slug) }}" 
                                           class="btn btn-outline-primary w-100">
                                            Detayları Gör
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!--Pagination-->
            @if($products->hasPages())
                <div class="row mt-6">
                    <div class="col-12">
                        <nav>
                            <ul class="pagination justify-content-center mb-0">
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
                                        <li class="page-item active"><a class="page-link" href="#!">{{ $page }}</a></li>
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
        @else
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <p class="text-muted">Bu kategoride henüz ürün bulunmamaktadır.</p>
                        <a href="{{ route('frontend.products.index') }}" class="btn btn-primary">Tüm Ürünleri Gör</a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Category Description --}}
        @if($category && $category->description)
            <div class="row mt-8">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-body p-6">
                            <h3 class="mb-4">{{ $category->name }} Hakkında</h3>
                            <div class="category-description">
                                {!! html_entity_decode($category->description, ENT_QUOTES, 'UTF-8') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!--Product listing end-->
@endsection

