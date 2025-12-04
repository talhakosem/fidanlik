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
<section class="py-lg-4 py-4">
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
                                            @if($category->children->count() > 0)
                                                <div class="bg-light p-4 rounded">
                                                    <h4 class="mb-3">Alt Kategoriler</h4>
                                                    <ul class="list-unstyled">
                                                        @php
                                                            $totalChildren = $category->children->count();
                                                            $displayChildren = $category->children->take(4);
                                                        @endphp
                                                        @foreach($displayChildren as $childCategory)
                                                            <li class="mb-2">
                                                                <a href="{{ url('/' . $childCategory->slug) }}" class="text-decoration-none">
                                                                    <i class="bi bi-arrow-right me-2"></i>{{ $childCategory->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                        @if($totalChildren > 4)
                                                            <li class="mb-2">
                                                                <span class="text-muted">
                                                                    <i class="bi bi-arrow-right me-2"></i>Daha fazla ({{ $totalChildren }})
                                                                </span>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center p-4 rounded" style="min-height: 200px;">
                                                    <p class="text-muted mb-0">Alt kategori bulunmamaktadır</p>
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

<!--Random Products Section start-->
@if(isset($randomProducts) && $randomProducts->count() > 0)
<section class="py-lg-2 pt-0 mx-3 mx-lg-0">
    <div class="container">
        <div class="row mb-md-8 mb-4">
            <div class="col-lg-12">
                <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-md-between gap-4">
                    <!--Heading-->
                    <div class="col-sm-7">
                        <h2>Öne Çıkan Ürünler</h2>
                        <p class="mb-0">Sizin için seçtiğimiz özel ürünlerimizi keşfedin.</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('frontend.products.index') }}" class="d-flex align-items-center gap-2 btn-dark-link">
                            <span class="text-link">Tümünü Gör</span>
                            <span class="btn btn-outline-primary btn-icon btn-xxs rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                    class="bi bi-chevron-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Slider-->
    <div class="swiper-container swiper px-3" id="swiper-products" data-pagination-type="progressbar" data-speed="400"
        data-space-between="30" data-pagination="true" data-navigation="true" data-autoplay="false"
        data-effect="slides" data-autoplay-delay="3000"
        data-breakpoints='{"480": {"slidesPerView": 2}, "768": {"slidesPerView": 3}, "1024": {"slidesPerView": 6}}'>
        <div class="swiper-wrapper pb-10">
            @foreach($randomProducts as $product)
                <div class="swiper-slide">
                    <div class="product-card">
                        <div class="text-center product-card-img mb-4" style="height: 300px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <a href="{{ url('/' . $product->slug) }}" class="d-block w-100 h-100 position-relative">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image) }}" 
                                         alt="{{ $product->name }}"
                                         class="img-fluid"
                                         style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                    @if($product->images->count() > 1)
                                        <img src="{{ asset('storage/' . $product->images->skip(1)->first()->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="img-fluid product-img-hover position-absolute top-0 start-0 w-100 h-100"
                                             style="object-fit: cover; object-position: center;">
                                    @endif
                                @else
                                    <img src="{{ frontend_asset('images/png/collection-img-1.png') }}" 
                                         alt="{{ $product->name }}"
                                         class="img-fluid"
                                         style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                @endif
                            </a>
                            <div class="product-card-btn">
                                <a href="{{ url('/' . $product->slug) }}" class="btn btn-primary btn-icon btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-eye" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                        <path
                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                    </svg>
                                </a>
                                <a href="{{ $product->agrolidya_link ?? 'https://agrolidya.com' }}" 
                                   target="_blank"
                                   class="btn btn-primary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                        class="bi bi-plus" viewBox="0 0 16 16">
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                    </svg>
                                    Satın Al
                                </a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            @if($product->categories->count() > 0)
                                <span class="small fw-medium text-uppercase">{{ $product->categories->first()->name }}</span>
                            @else
                                <span class="small fw-medium text-uppercase">Ürün</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <h3 class="fs-6 mb-0 product-heading d-inline-block text-truncate">
                                <a href="{{ url('/' . $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination top-100 mt-n4 start-lg-10 w-lg-75"></div>
        <!-- Add Navigation -->
        <div class="swiper-navigation position-absolute end-10 bottom-0 mb-4 d-none d-lg-block">
            <div class="swiper-button-next btn btn-icon btn-sm btn-outline-primary rounded-circle" id="slide2">
            </div>
            <div class="swiper-button-prev me-2 btn btn-icon btn-sm btn-outline-primary rounded-circle" id="slide1">
            </div>
        </div>
    </div>
</section>
@endif
<!--Random Products Section end-->

<!--Blog Section start-->
@if(isset($randomPosts) && $randomPosts->count() > 0)
<section class="py-lg-10 py-6">
    <div class="container">
        <div class="row mb-md-8 mb-4">
            <div class="col-lg-12">
                <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-md-between gap-4">
                    <!--Heading-->
                    <div class="col-sm-7">
                        <h2 class="mb-0">Blog Yazılarımız</h2>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('frontend.blog.index') }}" class="d-flex align-items-center gap-2 btn-dark-link">
                            <span class="text-link">Tüm Yazılar</span>
                            <span class="btn btn-outline-primary btn-icon btn-xxs rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                    class="bi bi-chevron-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            @foreach($randomPosts as $post)
                <div class="col-lg-4 col-md-6">
                    <article class="">
                        <a href="{{ url('/' . $post->slug) }}" class="position-relative d-flex">
                            <figure class="img-hover mb-0 w-100" style="height: 250px; overflow: hidden;">
                                @if($post->cover_image)
                                    <img src="{{ asset('storage/' . $post->cover_image) }}" 
                                         alt="{{ $post->title }}" 
                                         class="img-fluid w-100 h-100" 
                                         style="object-fit: cover; object-position: center;" />
                                @else
                                    <img src="{{ frontend_asset('images/blog/blog-img-1.jpg') }}" 
                                         alt="{{ $post->title }}" 
                                         class="img-fluid w-100 h-100" 
                                         style="object-fit: cover; object-position: center;" />
                                @endif
                            </figure>
                            @if($post->category)
                                <div class="position-absolute bottom-0 p-3">
                                    <span class="badge text-bg-info">{{ $post->category }}</span>
                                </div>
                            @endif
                        </a>
                        <div class="mt-4">
                            <h3 class="fs-5">
                                <a href="{{ url('/' . $post->slug) }}" class="text-inherit">{{ $post->title }}</a>
                            </h3>
                            @if($post->short_description)
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->short_description), 120) }}</p>
                            @elseif($post->content)
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                            @endif
                            <p class="d-flex gap-3 align-items-center">
                                <span class="d-flex align-items-center gap-1 small">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.2"
                                            d="M13.5 3.5V6H2.5V3.5C2.5 3.36739 2.55268 3.24021 2.64645 3.14645C2.74021 3.05268 2.86739 3 3 3H13C13.1326 3 13.2598 3.05268 13.3536 3.14645C13.4473 3.24021 13.5 3.36739 13.5 3.5Z"
                                            fill="#4E4E4E" />
                                        <path
                                            d="M13 2.5H11.5V2C11.5 1.86739 11.4473 1.74021 11.3536 1.64645C11.2598 1.55268 11.1326 1.5 11 1.5C10.8674 1.5 10.7402 1.55268 10.6464 1.64645C10.5527 1.74021 10.5 1.86739 10.5 2V2.5H5.5V2C5.5 1.86739 5.44732 1.74021 5.35355 1.64645C5.25979 1.55268 5.13261 1.5 5 1.5C4.86739 1.5 4.74021 1.55268 4.64645 1.64645C4.55268 1.74021 4.5 1.86739 4.5 2V2.5H3C2.73478 2.5 2.48043 2.60536 2.29289 2.79289C2.10536 2.98043 2 3.23478 2 3.5V13.5C2 13.7652 2.10536 14.0196 2.29289 14.2071C2.48043 14.3946 2.73478 14.5 3 14.5H13C13.2652 14.5 13.5196 14.3946 13.7071 14.2071C13.8946 14.0196 14 13.7652 14 13.5V3.5C14 3.23478 13.8946 2.98043 13.7071 2.79289C13.5196 2.60536 13.2652 2.5 13 2.5ZM4.5 3.5V4C4.5 4.13261 4.55268 4.25979 4.64645 4.35355C4.74021 4.44732 4.86739 4.5 5 4.5C5.13261 4.5 5.25979 4.44732 5.35355 4.35355C5.44732 4.25979 5.5 4.13261 5.5 4V3.5H10.5V4C10.5 4.13261 10.5527 4.25979 10.6464 4.35355C10.7402 4.44732 10.8674 4.5 11 4.5C11.1326 4.5 11.2598 4.44732 11.3536 4.35355C11.4473 4.25979 11.5 4.13261 11.5 4V3.5H13V5.5H3V3.5H4.5ZM13 13.5H3V6.5H13V13.5Z"
                                            fill="#211F1C" />
                                    </svg>
                                    {{ $post->created_at->format('d M, Y') }}
                                </span>
                            </p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<!--Blog Section end-->

<!--Feature Section start-->
<section class="py-lg-6 py-4">
    <div class="container">
        <div class="row">
            <!--Hakkımızda-->
            <div class="col-lg-3 col-md-6 col-12 border-end">
                <div class="text-center py-md-6 px-md-4 py-5">
                    <div class="mb-3">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.2"
                                d="M16 16.1362V29C15.832 28.9993 15.667 28.9563 15.52 28.875L4.52 22.8525C4.36293 22.7665 4.23181 22.64 4.14035 22.4861C4.04888 22.3322 4.00041 22.1565 4 21.9775V10.0225C4.0004 9.88241 4.03021 9.74401 4.0875 9.61621L16 16.1362Z"
                                fill="#CA8A04" />
                            <path
                                d="M27.96 8.26877L16.96 2.25002C16.6661 2.08763 16.3358 2.00244 16 2.00244C15.6642 2.00244 15.3339 2.08763 15.04 2.25002L4.04 8.27127C3.72586 8.44315 3.46363 8.69622 3.28069 9.00405C3.09775 9.31188 3.00081 9.66319 3 10.0213V21.9763C3.00081 22.3344 3.09775 22.6857 3.28069 22.9935C3.46363 23.3013 3.72586 23.5544 4.04 23.7263L15.04 29.7475C15.3339 29.9099 15.6642 29.9951 16 29.9951C16.3358 29.9951 16.6661 29.9099 16.96 29.7475L27.96 23.7263C28.2741 23.5544 28.5364 23.3013 28.7193 22.9935C28.9023 22.6857 28.9992 22.3344 29 21.9763V10.0225C28.9999 9.6638 28.9032 9.31172 28.7203 9.00317C28.5373 8.69462 28.2747 8.44096 27.96 8.26877ZM16 4.00002L26.0425 9.50002L22.3213 11.5375L12.2775 6.03752L16 4.00002ZM16 15L5.9575 9.50002L10.195 7.18002L20.2375 12.68L16 15ZM5 11.25L15 16.7225V27.4463L5 21.9775V11.25ZM27 21.9725L17 27.4463V16.7275L21 14.5388V19C21 19.2652 21.1054 19.5196 21.2929 19.7071C21.4804 19.8947 21.7348 20 22 20C22.2652 20 22.5196 19.8947 22.7071 19.7071C22.8946 19.5196 23 19.2652 23 19V13.4438L27 11.25V21.9713V21.9725Z"
                                fill="#CA8A04" />
                        </svg>
                    </div>
                    <h3 class="fs-5">Hakkımızda</h3>
                    <p class="mb-0">
                        20 yıllık tecrübemizle
                        <span class="fw-semibold text-dark">sertifikalı fidan</span>
                        üretimi
                    </p>
                </div>
            </div>
            <!--Güvenli-->
            <div class="col-lg-3 col-md-6 col-12 border-end">
                <div class="text-center py-md-6 px-md-4 py-5">
                    <div class="mb-3">
                        <svg width="33" height="32" viewBox="0 0 33 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.2"
                                d="M28.4925 21.76C28.2983 23.2113 27.5836 24.5427 26.4813 25.5065C25.3791 26.4704 23.9642 27.0011 22.5 27C17.9913 27 13.6673 25.2089 10.4792 22.0208C7.29107 18.8327 5.5 14.5087 5.5 9.99997C5.49888 8.53573 6.0296 7.12091 6.99345 6.01864C7.95731 4.91637 9.28869 4.20167 10.74 4.00747C10.9545 3.98199 11.1716 4.02666 11.3586 4.13482C11.5456 4.24299 11.6926 4.40881 11.7775 4.60747L14.4188 10.5075C14.4836 10.659 14.5101 10.8242 14.4957 10.9885C14.4813 11.1527 14.4265 11.3108 14.3363 11.4487L11.665 14.625C11.5702 14.768 11.5142 14.9331 11.5024 15.1042C11.4906 15.2753 11.5233 15.4466 11.5975 15.6012C12.6313 17.7175 14.8188 19.8787 16.9413 20.9025C17.0967 20.9763 17.2688 21.0084 17.4404 20.9954C17.612 20.9825 17.7773 20.925 17.92 20.8287L21.045 18.1662C21.1835 18.0741 21.3428 18.0179 21.5084 18.0029C21.6741 17.9878 21.8409 18.0143 21.9938 18.08L27.8888 20.7212C28.0883 20.8056 28.2551 20.9525 28.364 21.1398C28.4729 21.3272 28.518 21.5448 28.4925 21.76Z"
                                fill="#CA8A04" />
                            <path
                                d="M28.295 19.8077L22.4075 17.169L22.3925 17.1615C22.0866 17.0305 21.7529 16.9779 21.4216 17.0085C21.0902 17.039 20.7718 17.1518 20.495 17.3365C20.4624 17.358 20.4311 17.3814 20.4013 17.4065L17.3588 20.0002C15.4313 19.064 13.4413 17.089 12.505 15.1865L15.1025 12.0977C15.1275 12.0665 15.1513 12.0352 15.1738 12.0015C15.3543 11.7256 15.4638 11.4094 15.4926 11.081C15.5214 10.7525 15.4686 10.4221 15.3388 10.119C15.3383 10.114 15.3383 10.109 15.3388 10.104L12.6925 4.20524C12.5209 3.80932 12.2259 3.4795 11.8515 3.26503C11.4771 3.05056 11.0433 2.96294 10.615 3.01524C8.92122 3.23812 7.36649 4.06995 6.24118 5.35535C5.11587 6.64076 4.49695 8.29185 4.50001 10.0002C4.50001 19.9252 12.575 28.0002 22.5 28.0002C24.2084 28.0033 25.8595 27.3844 27.1449 26.2591C28.4303 25.1338 29.2621 23.579 29.485 21.8852C29.5373 21.4569 29.4497 21.0232 29.2352 20.6488C29.0207 20.2743 28.6909 19.9793 28.295 19.8077ZM22.5 26.0002C18.258 25.9956 14.191 24.3084 11.1914 21.3088C8.19184 18.3092 6.50464 14.2423 6.50001 10.0002C6.49531 8.7796 6.93507 7.59898 7.73718 6.67887C8.53929 5.75876 9.64889 5.16207 10.8588 5.00024C10.8583 5.00522 10.8583 5.01025 10.8588 5.01524L13.4838 10.8902L10.9 13.9827C10.8738 14.0129 10.85 14.0451 10.8288 14.079C10.6407 14.3676 10.5303 14.7 10.5084 15.0438C10.4865 15.3876 10.5538 15.7313 10.7038 16.0415C11.8363 18.3577 14.17 20.674 16.5113 21.8052C16.8237 21.9538 17.1694 22.0188 17.5144 21.9938C17.8595 21.9688 18.1922 21.8547 18.48 21.6627C18.5124 21.6411 18.5433 21.6173 18.5725 21.5915L21.6113 19.0002L27.4863 21.6327C27.4863 21.6327 27.4963 21.6327 27.5 21.6327C27.3399 22.8441 26.744 23.9556 25.8237 24.7595C24.9035 25.5633 23.7219 26.0044 22.5 26.0002ZM18.5 9.00024C18.5 8.73502 18.6054 8.48066 18.7929 8.29313C18.9804 8.10559 19.2348 8.00024 19.5 8.00024H22.5V5.00024C22.5 4.73502 22.6054 4.48066 22.7929 4.29313C22.9804 4.10559 23.2348 4.00024 23.5 4.00024C23.7652 4.00024 24.0196 4.10559 24.2071 4.29313C24.3947 4.48066 24.5 4.73502 24.5 5.00024V8.00024H27.5C27.7652 8.00024 28.0196 8.10559 28.2071 8.29313C28.3947 8.48066 28.5 8.73502 28.5 9.00024C28.5 9.26545 28.3947 9.51981 28.2071 9.70734C28.0196 9.89488 27.7652 10.0002 27.5 10.0002H24.5V13.0002C24.5 13.2655 24.3947 13.5198 24.2071 13.7073C24.0196 13.8949 23.7652 14.0002 23.5 14.0002C23.2348 14.0002 22.9804 13.8949 22.7929 13.7073C22.6054 13.5198 22.5 13.2655 22.5 13.0002V10.0002H19.5C19.2348 10.0002 18.9804 9.89488 18.7929 9.70734C18.6054 9.51981 18.5 9.26545 18.5 9.00024Z"
                                fill="#CA8A04" />
                        </svg>
                    </div>
                    <h3 class="fs-5">Güvenli Alışveriş</h3>
                    <p class="mb-0">
                        Sertifikalı ve
                        <span class="fw-semibold text-dark">güvenilir</span>
                        fidan garantisi
                    </p>
                </div>
            </div>
            <!--20 Yıllık Firma-->
            <div class="col-lg-3 col-md-6 col-12 border-end">
                <div class="text-center py-md-6 px-md-4 py-5">
                    <div class="mb-3">
                        <svg width="33" height="32" viewBox="0 0 33 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.2"
                                d="M30.2075 19.125L19.625 29.7075C19.4375 29.8949 19.1832 30.0002 18.9181 30.0002C18.653 30.0002 18.3988 29.8949 18.2113 29.7075L5.7925 17.2925C5.60532 17.1051 5.50012 16.8511 5.5 16.5863V5H17.0863C17.3511 5.00012 17.6051 5.10532 17.7925 5.2925L30.2075 17.7075C30.3009 17.8004 30.3749 17.9109 30.4255 18.0325C30.476 18.1541 30.502 18.2845 30.502 18.4163C30.502 18.548 30.476 18.6784 30.4255 18.8C30.3749 18.9216 30.3009 19.0321 30.2075 19.125Z"
                                fill="#CA8A04" />
                            <path
                                d="M30.9138 17L18.5 4.58626C18.315 4.39973 18.0947 4.25185 17.852 4.15121C17.6093 4.05057 17.349 3.99917 17.0863 4.00001H5.50001C5.23479 4.00001 4.98044 4.10537 4.7929 4.2929C4.60537 4.48044 4.50001 4.73479 4.50001 5.00001V16.5863C4.49917 16.849 4.55057 17.1093 4.65121 17.352C4.75185 17.5947 4.89973 17.815 5.08626 18L17.5 30.4138C17.6857 30.5995 17.9062 30.7469 18.1489 30.8474C18.3916 30.948 18.6517 30.9997 18.9144 30.9997C19.1771 30.9997 19.4372 30.948 19.6799 30.8474C19.9225 30.7469 20.143 30.5995 20.3288 30.4138L30.9138 19.8288C31.0995 19.643 31.2469 19.4225 31.3474 19.1799C31.448 18.9372 31.4997 18.6771 31.4997 18.4144C31.4997 18.1517 31.448 17.8916 31.3474 17.6489C31.2469 17.4062 31.0995 17.1857 30.9138 17ZM18.9138 29L6.50001 16.5863V6.00001H17.0863L29.5 18.4138L18.9138 29ZM12.5 10.5C12.5 10.7967 12.412 11.0867 12.2472 11.3334C12.0824 11.58 11.8481 11.7723 11.574 11.8858C11.2999 11.9994 10.9983 12.0291 10.7074 11.9712C10.4164 11.9133 10.1491 11.7704 9.93935 11.5607C9.72957 11.3509 9.58671 11.0836 9.52883 10.7926C9.47095 10.5017 9.50066 10.2001 9.61419 9.92598C9.72772 9.6519 9.91998 9.41763 10.1667 9.25281C10.4133 9.08798 10.7033 9.00001 11 9.00001C11.3978 9.00001 11.7794 9.15805 12.0607 9.43935C12.342 9.72065 12.5 10.1022 12.5 10.5Z"
                                fill="#CA8A04" />
                        </svg>
                    </div>
                    <h3 class="fs-5">Kaliteli Ürünler</h3>
                    <p class="mb-0">
                        <span class="fw-semibold text-dark">Yüksek kalite</span>
                        standartlarında üretim
                    </p>
                </div>
            </div>
            <!--Hızlı Teslimat-->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="text-center py-md-6 px-md-4 py-5">
                    <div class="mb-3">
                        <svg width="33" height="32" viewBox="0 0 33 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.2"
                                d="M26.5 11H6.5C6.23478 11 5.98043 11.1054 5.79289 11.2929C5.60536 11.4804 5.5 11.7348 5.5 12V26C5.5 26.2652 5.60536 26.5196 5.79289 26.7071C5.98043 26.8946 6.23478 27 6.5 27H26.5C26.7652 27 27.0196 26.8946 27.2071 26.7071C27.3946 26.5196 27.5 26.2652 27.5 26V12C27.5 11.7348 27.3946 11.4804 27.2071 11.2929C27.0196 11.1054 26.7652 11 26.5 11ZM16.5 20C16.0055 20 15.5222 19.8534 15.1111 19.5787C14.7 19.304 14.3795 18.9135 14.1903 18.4567C14.0011 17.9999 13.9516 17.4972 14.048 17.0123C14.1445 16.5273 14.3826 16.0819 14.7322 15.7322C15.0819 15.3826 15.5273 15.1445 16.0123 15.048C16.4972 14.9516 16.9999 15.0011 17.4567 15.1903C17.9135 15.3795 18.304 15.7 18.5787 16.1111C18.8534 16.5222 19 17.0055 19 17.5C19 18.163 18.7366 18.7989 18.2678 19.2678C17.7989 19.7366 17.163 20 16.5 20Z"
                                fill="#CA8A04" />
                            <path
                                d="M26.5 10H22.5V7C22.5 5.4087 21.8679 3.88258 20.7426 2.75736C19.6174 1.63214 18.0913 1 16.5 1C14.9087 1 13.3826 1.63214 12.2574 2.75736C11.1321 3.88258 10.5 5.4087 10.5 7V10H6.5C5.96957 10 5.46086 10.2107 5.08579 10.5858C4.71071 10.9609 4.5 11.4696 4.5 12V26C4.5 26.5304 4.71071 27.0391 5.08579 27.4142C5.46086 27.7893 5.96957 28 6.5 28H26.5C27.0304 28 27.5391 27.7893 27.9142 27.4142C28.2893 27.0391 28.5 26.5304 28.5 26V12C28.5 11.4696 28.2893 10.9609 27.9142 10.5858C27.5391 10.2107 27.0304 10 26.5 10ZM12.5 7C12.5 5.93913 12.9214 4.92172 13.6716 4.17157C14.4217 3.42143 15.4391 3 16.5 3C17.5609 3 18.5783 3.42143 19.3284 4.17157C20.0786 4.92172 20.5 5.93913 20.5 7V10H12.5V7ZM26.5 26H6.5V12H26.5V26ZM16.5 14C15.6595 14.0003 14.8472 14.303 14.2115 14.8529C13.5758 15.4027 13.1592 16.163 13.0378 16.9947C12.9165 17.8264 13.0985 18.674 13.5506 19.3825C14.0027 20.0911 14.6946 20.6133 15.5 20.8538V23C15.5 23.2652 15.6054 23.5196 15.7929 23.7071C15.9804 23.8946 16.2348 24 16.5 24C16.7652 24 17.0196 23.8946 17.2071 23.7071C17.3946 23.5196 17.5 23.2652 17.5 23V20.8538C18.3054 20.6133 18.9973 20.0911 19.4494 19.3825C19.9015 18.674 20.0835 17.8264 19.9622 16.9947C19.8408 16.163 19.4242 15.4027 18.7885 14.8529C18.1528 14.303 17.3405 14.0003 16.5 14ZM16.5 19C16.2033 19 15.9133 18.912 15.6666 18.7472C15.42 18.5824 15.2277 18.3481 15.1142 18.074C15.0006 17.7999 14.9709 17.4983 15.0288 17.2074C15.0867 16.9164 15.2296 16.6491 15.4393 16.4393C15.6491 16.2296 15.9164 16.0867 16.2074 16.0288C16.4983 15.9709 16.7999 16.0007 17.074 16.1142C17.3481 16.2277 17.5824 16.42 17.7472 16.6666C17.912 16.9133 18 17.2033 18 17.5C18 17.8978 17.842 18.2794 17.5607 18.5607C17.2794 18.842 16.8978 19 16.5 19Z"
                                fill="#CA8A04" />
                        </svg>
                    </div>
                    <h3 class="fs-5">Hızlı Teslimat</h3>
                    <p class="mb-0">Hızlı ve güvenli teslimat garantisi</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Feature Section end-->
@endsection

