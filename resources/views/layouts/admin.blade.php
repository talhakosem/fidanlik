<!DOCTYPE html>
<html lang="tr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Codescandy" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'FreshCart') }}</title>
    
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ admin_asset('images/favicon/favicon.ico') }}">

    <!-- Libs CSS -->
    <link href="{{ admin_asset('libs/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ admin_asset('libs/feather-webfont/dist/feather-icons.css') }}" rel="stylesheet">
    <link href="{{ admin_asset('libs/simplebar/dist/simplebar.min.css') }}" rel="stylesheet">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ admin_asset('css/theme.min.css') }}">
    
    <!-- Additional CSS -->
    @stack('styles')
</head>

<body>
    <!-- main -->
    <div>
        <div class="main-wrapper">
            <!-- navbar vertical -->
            <nav class="navbar-vertical-nav d-none d-xl-block">
                <div class="navbar-vertical">
                    <div class="px-4 py-5">
                        <a href="{{ route('dashboard') }}" class="navbar-brand">
                            <img src="{{ admin_asset('images/logo/freshcart-logo.svg') }}" alt="Logo" />
                        </a>
                    </div>
                    <div class="navbar-vertical-content flex-grow-1" data-simplebar="">
                        <ul class="navbar-nav flex-column" id="sideNavbar">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-house"></i></span>
                                        <span class="nav-link-text">Dashboard</span>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="nav-item mt-6 mb-3">
                                <span class="nav-label">Mağaza Yönetimi</span>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-cart"></i></span>
                                        <span class="nav-link-text">Ürünler</span>
                                    </div>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="#!">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-list-task"></i></span>
                                        <span class="nav-link-text">Kategoriler</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#navBlog" aria-expanded="false" aria-controls="navBlog">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-newspaper"></i></span>
                                        <span class="nav-link-text">Blog</span>
                                        <span class="badge bg-light-success text-dark-success ms-2">Yeni</span>
                                    </div>
                                </a>
                                <div id="navBlog" class="collapse" data-bs-parent="#sideNavbar">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="#!">Grid</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#!">Liste</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#!">Yeni Gönderi</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Mobile navbar -->
            <nav class="navbar-vertical-nav offcanvas offcanvas-start navbar-offcanvac" tabindex="-1" id="offcanvasExample">
                <div class="navbar-vertical">
                    <div class="px-4 py-5 d-flex justify-content-between align-items-center">
                        <a href="{{ route('dashboard') }}" class="navbar-brand">
                            <img src="{{ admin_asset('images/logo/freshcart-logo.svg') }}" alt="Logo" />
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="navbar-vertical-content flex-grow-1" data-simplebar="">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-house"></i></span>
                                        <span>Dashboard</span>
                                    </div>
                                </a>
                            </li>
                        
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-cart"></i></span>
                                        <span class="nav-link-text">Ürünler</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#!">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-list-task"></i></span>
                                        <span class="nav-link-text">Kategoriler</span>
                                    </div>
                                </a>
                            </li>
                        
                            <li class="nav-item">
                                <a class="nav-link" href="#!">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-icon"><i class="bi bi-newspaper"></i></span>
                                        <span class="nav-link-text">Blog</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- main wrapper -->
            <main class="main-content-wrapper">
                <section class="container">
                    @yield('content')
                </section>
            </main>
        </div>
    </div>

    <!-- Libs JS -->
    <script src="{{ admin_asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ admin_asset('libs/simplebar/dist/simplebar.min.js') }}"></script>

    <!-- Theme JS -->
    <script src="{{ admin_asset('js/theme.min.js') }}"></script>

    <!-- Additional JS -->
    @stack('scripts')
</body>
</html>

