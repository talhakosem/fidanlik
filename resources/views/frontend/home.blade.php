@extends('layouts.frontend')

@section('title', 'Ana Sayfa')

@php
    $settings = \App\Models\Setting::getSettings();
@endphp

@section('meta_description', $settings->site_description ?? 'Fidanlık ana sayfası')

@section('content')
<!--Hero Image Section start-->
<section class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('storage/badem-fidani.png') }}" 
                     alt="Badem Fidanı" 
                     class="img-fluid w-100 rounded" 
                     style="max-height: 500px; object-fit: cover;" />
            </div>
        </div>
    </div>
</section>
<!--Hero Image Section end-->

<!--Categories Swiper Section start-->
<section class="py-lg-8 py-6">
    <div class="container">
        @php
            $topMenuCategories = \App\Models\Category::where('show_in_top_menu', true)
                ->where(function($query) {
                    $query->whereNull('parent_id')
                          ->orWhere('parent_id', 0);
                })
                ->with(['children' => function($q) {
                    $q->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->get();
        @endphp
        
        @if($topMenuCategories->count() > 0)
            <div class="swiper-container swiper" id="swiper-categories" data-pagination-type="" data-speed="800" data-space-between="100"
                data-pagination="false" data-navigation="true" data-autoplay="true" data-effect="fade"
                data-autoplay-delay="3000"
                data-breakpoints='{"480": {"slidesPerView": 2}, "768": {"slidesPerView": 3}, "1024": {"slidesPerView": 1}}'>
                <div class="swiper-wrapper pb-lg-8">
                    @foreach($topMenuCategories as $category)
                        <div class="swiper-slide w-100 bg-light bg-opacity-50 border-bottom">
                            <div class="container d-flex flex-column justify-content-center h-100">
                                <div class="row align-items-center py-md-8 py-6">
                                    <div class="col-lg-5">
                                        <div class="">
                                            <h1 class="mb-3 mt-4 display-5 fw-bold">{{ $category->name }}</h1>
                                            @if($category->meta_description || $category->description)
                                                <p class="mb-4 pe-lg-6">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($category->meta_description ?? $category->description ?? ''), 200) }}
                                                </p>
                                            @endif
                                            <a href="{{ url('/' . $category->slug) }}" class="btn btn-outline-primary">İncele</a>
                                        </div>
                                    </div>
                                    <div class="offset-lg-1 col-lg-6">
                                        <div class="position-relative">
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" 
                                                     alt="{{ $category->name }}"
                                                     class="img-fluid" />
                                            @elseif($category->children->count() > 0)
                                                <div class="bg-light p-4 rounded">
                                                    <h4 class="mb-3">Alt Kategoriler</h4>
                                                    <ul class="list-unstyled">
                                                        @foreach($category->children as $childCategory)
                                                            <li class="mb-2">
                                                                <a href="{{ url('/' . $childCategory->slug) }}" class="text-decoration-none">
                                                                    <i class="bi bi-arrow-right me-2"></i>{{ $childCategory->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                                    <p class="text-muted">Görsel bulunamadı</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Add Navigation -->
                <div class="swiper-navigation position-absolute end-25 bottom-0 bottom-md-10 me-md-n10 mb-8">
                    <div class="swiper-button-next btn btn-icon btn-sm btn-outline-primary rounded-circle"></div>
                    <div class="swiper-button-prev me-2 btn btn-icon btn-sm btn-outline-primary rounded-circle"></div>
                </div>
            </div>
        @endif
    </div>
</section>
<!--Categories Swiper Section end-->
@endsection

