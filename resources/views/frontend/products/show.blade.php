@extends('layouts.frontend')

@section('title', $product->meta_title ?? $product->name)

@section('meta_description', $product->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($product->description), 160))

@push('styles')
<!-- Open Graph / Facebook -->
<meta property="og:type" content="product">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
<meta property="og:description" content="{{ $product->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($product->description), 160) }}">
@if($product->images->count() > 0)
<meta property="og:image" content="{{ asset('storage/' . $product->images->first()->image) }}">
@endif

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $product->meta_title ?? $product->name }}">
<meta property="twitter:description" content="{{ $product->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($product->description), 160) }}">
@if($product->images->count() > 0)
<meta property="twitter:image" content="{{ asset('storage/' . $product->images->first()->image) }}">
@endif
@endpush

@push('scripts')
<!-- Product Structured Data -->
@php
    $structuredData = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->name,
        'description' => strip_tags($product->description ?? ''),
    ];
    
    if($product->images->count() > 0) {
        $structuredData['image'] = $product->images->map(function($image) {
            return asset('storage/' . $image->image);
        })->toArray();
    }
    
    if($product->sku) {
        $structuredData['sku'] = $product->sku;
    }
    
    if($product->agrolidya_link) {
        $structuredData['offers'] = [
            '@type' => 'Offer',
            'url' => $product->agrolidya_link,
            'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
        ];
    }
@endphp
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<!--Breadcrumb start-->
<div class="container py-4">
    <div class="row">
        <div class="col-12 py-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Ana Sayfa</a></li>
                    @if($product->categories->count() > 0)
                        <li class="breadcrumb-item"><a href="{{ url('/' . $product->categories->first()->slug) }}">{{ $product->categories->first()->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!--Breadcrumb end-->

<!--Product start-->
<section class="pb-lg-8">
    <div class="container">
        <div class="row gy-4 gy-lg-0">
            <!--Product image-->
            <div class="col-lg-6">
                <div class="product-content">
                    @if($product->images->count() > 0)
                        <div class="row row-cols-2 g-4">
                            <div class="col-12">
                                <div class="img-hover">
                                    <img src="{{ asset('storage/' . $product->images->first()->image) }}" 
                                         alt="{{ $product->name }}"
                                        class="img-fluid w-100" />
                                </div>
                            </div>
                            @if($product->images->count() > 1)
                                @foreach($product->images->skip(1)->take(4) as $image)
                                    <div class="col-3 col-lg-6">
                                        <div class="img-hover">
                                            <img src="{{ asset('storage/' . $image->image) }}" 
                                                 alt="{{ $product->name }}"
                                                class="img-fluid w-100" />
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @else
                        <div class="img-hover">
                            <img src="{{ frontend_asset('images/png/collection-img-1.png') }}" 
                                 alt="{{ $product->name }}"
                                class="img-fluid w-100" />
                        </div>
                    @endif
                </div>
            </div>
            
            <!--Product details-->
            <div class="col-lg-6">
                <div id="sidebar">
                    <div class="sidebar__inner ps-lg-5">
                        @if($product->categories->count() > 0)
                            <span class="badge bg-info">{{ $product->categories->first()->name }}</span>
                        @endif
                        
                        <div class="mt-3 mb-2">
                            <div class="mb-3">
                                <h1 class="h2 mb-2">{{ $product->name }}</h1>
                                @if($product->sku)
                                    <span class="text-muted small">SKU: {{ $product->sku }}</span>
                                @endif
                            </div>
                        </div>
                    
                        <div class="d-flex flex-md-row flex-column gap-2">
                            <a href="{{ $product->agrolidya_link ?? 'https://agrolidya.com' }}" target="_blank" class="btn btn-dark w-100">Satın Al</a>
                        </div>

                        @if($product->meta_description)
                            <div class="mt-3">
                                <h4 class="mb-3">Ürün Açıklaması</h4>
                                <div class="product-description">
                                    {!! html_entity_decode($product->meta_description, ENT_QUOTES, 'UTF-8') !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Product end-->

@if($product->description)
<!--Product Description start-->
<section class="py-lg-8 py-6">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card border">
                    <div class="card-body p-6">
                        <h3 class="mb-4">Ürün Detayları</h3>
                        <div class="product-description">
                            {!! html_entity_decode($product->description, ENT_QUOTES, 'UTF-8') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Product Description end-->
@endif

@if($relatedProducts->count() > 0)
<!--Related Products start-->
<section class="pb-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!--Heading-->
                <div class="text-center mb-6">
                    <h2 class="mb-0">Benzer Ürünler</h2>
                </div>
            </div>
        </div>
        <div class="row gy-6 gx-4">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card border-0 h-100">
                        <div class="text-center product-card-img mb-4 position-relative">
                            <a href="{{ url('/' . $relatedProduct->slug) }}">
                                @if($relatedProduct->images->count() > 0)
                                    <img src="{{ asset('storage/' . $relatedProduct->images->first()->image) }}" 
                                         alt="{{ $relatedProduct->name }}"
                                        class="img-fluid">
                                @else
                                    <img src="{{ frontend_asset('images/png/collection-img-1.png') }}" 
                                         alt="{{ $relatedProduct->name }}"
                                        class="img-fluid">
                                @endif
                            </a>
                        </div>
                        <div class="card-body">
                            @if($relatedProduct->categories->count() > 0)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-medium text-uppercase">{{ $relatedProduct->categories->first()->name }}</span>
                                </div>
                            @endif
                            <div class="mb-3">
                                <h3 class="fs-6 mb-0">
                                    <a href="{{ url('/' . $relatedProduct->slug) }}" class="text-inherit text-decoration-none">
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>
                            </div>
                            <a href="{{ $relatedProduct->agrolidya_link ?? 'https://agrolidya.com' }}" target="_blank" class="btn btn-primary btn-sm w-100">
                                Satın Al
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--Related Products end-->
@endif
@endsection