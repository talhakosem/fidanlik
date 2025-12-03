<!DOCTYPE html>
<html lang="tr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $settings = \App\Models\Setting::getSettings();
    @endphp
    
    <title>@yield('title', 'Ana Sayfa') - {{ $settings->site_title ?? config('app.name', 'Fidanlık') }}</title>
    
    <!-- Meta Description -->
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @else
        <meta name="description" content="{{ $settings->site_description ?? '' }}">
    @endif
    
    <!-- Meta Keywords -->
    @if($settings->site_keywords)
        <meta name="keywords" content="{{ $settings->site_keywords }}">
    @endif
    
    <!-- Favicon -->
    @if($settings->favicon)
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{ frontend_asset('images/favicon/favicon.ico') }}">
    @endif
    
    <!-- Libs CSS -->
    <link href="{{ frontend_asset('libs/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ frontend_asset('libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ frontend_asset('libs/drift-zoom/dist/drift-basic.min.css') }}" rel="stylesheet">
    <link href="{{ frontend_asset('libs/simplebar/dist/simplebar.min.css') }}" rel="stylesheet">
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ frontend_asset('css/theme.min.css') }}">
    
    <!-- Additional CSS -->
    @stack('styles')
</head>
<body>
    <!-- Header/Navbar -->
    @include('frontend.partials.header', ['settings' => $settings])
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('frontend.partials.footer', ['settings' => $settings])
    
    <!-- Libs JS -->
    <script src="{{ frontend_asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ frontend_asset('libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ frontend_asset('libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ frontend_asset('libs/drift-zoom/dist/Drift.min.js') }}"></script>
    
    <!-- Theme JS -->
    <script src="{{ frontend_asset('js/theme.min.js') }}"></script>
    
    <!-- Vendor JS -->
    <script src="{{ frontend_asset('js/vendors/swiper.js') }}"></script>
    <script src="{{ frontend_asset('js/vendors/drift.js') }}"></script>
    <script src="{{ frontend_asset('js/vendors/slide-hint-img.js') }}"></script>
    <script src="{{ frontend_asset('js/vendors/flag.js') }}"></script>
    <script src="{{ frontend_asset('js/vendors/color-change.js') }}"></script>
    <script src="{{ frontend_asset('js/vendors/add-to-cart.js') }}"></script>
    <script src="{{ frontend_asset('js/vendors/qty-input.js') }}"></script>
    <script src="{{ frontend_asset('js/vendors/btn-scrolltop.js') }}"></script>
    
    <!-- Additional JS -->
    @stack('scripts')
</body>
</html>

