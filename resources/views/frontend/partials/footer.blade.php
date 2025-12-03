<footer class="pt-lg-8 pt-5 bg-light">
    <div class="container">
        <div class="row mb-8">
            <div class="col-12">
                <div class="text-center">
                    @if($settings->logo)
                        <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->site_title ?? 'Logo' }}" style="max-height: 50px;">
                    @else
                        <img src="{{ frontend_asset('images/logo/logo.svg') }}" alt="{{ $settings->site_title ?? 'Logo' }}" />
                    @endif
                </div>
            </div>
        </div>
        <div class="row mb-8 gy-4">
            @php
                // Meyve Fidanları kategorisini ayrı al
                $meyveFidanlari = \App\Models\Category::where('name', 'like', '%meyve fidanları%')
                    ->where(function($query) {
                        $query->whereNull('parent_id')
                              ->orWhere('parent_id', 0);
                    })
                    ->with(['children' => function($q) {
                        $q->orderBy('sort_order');
                    }])
                    ->first();
                
                // Diğer kategoriler
                $footerCategoryNames = ['ceviz fidanları', 'zeytin fidanları'];
                $footerCategories = \App\Models\Category::where(function($query) use ($footerCategoryNames) {
                    foreach($footerCategoryNames as $index => $name) {
                        if($index === 0) {
                            $query->where('name', 'like', '%' . $name . '%');
                        } else {
                            $query->orWhere('name', 'like', '%' . $name . '%');
                        }
                    }
                })
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
            
            {{-- Kurumsal Bölümü --}}
            <div class="col-lg-2 col-md-4 col-6">
                <div>
                    <h3 class="fs-5 mb-lg-4 mb-3">Kurumsal</h3>
                    <ul class="list-unstyled lh-lg nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link p-0 text-link d-inline">Ana Sayfa</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('frontend.about') }}" class="nav-link p-0 text-link d-inline">Hakkımızda</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('frontend.blog.index') }}" class="nav-link p-0 text-link d-inline">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://agrolidya.com/contact" target="_blank" class="nav-link p-0 text-link d-inline">İletişim</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Meyve Fidanları --}}
            @if($meyveFidanlari)
                <div class="col-lg-2 col-md-4 col-6">
                    <div>
                        <h3 class="fs-5 mb-lg-4 mb-3">{{ $meyveFidanlari->name }}</h3>
                        <ul class="list-unstyled lh-lg nav flex-column">
                            @if($meyveFidanlari->children->count() > 0)
                                @foreach($meyveFidanlari->children as $childCategory)
                                    <li class="nav-item">
                                        <a href="{{ url('/' . $childCategory->slug) }}" class="nav-link p-0 text-link d-inline">
                                            {{ $childCategory->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <li class="nav-item">
                                    <a href="{{ url('/' . $meyveFidanlari->slug) }}" class="nav-link p-0 text-link d-inline">
                                        Tüm {{ $meyveFidanlari->name }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
            
            {{-- Diğer Kategoriler --}}
            @if($footerCategories->count() > 0)
                @foreach($footerCategories as $category)
                    <div class="col-lg-2 col-md-4 col-6">
                        <div>
                            <h3 class="fs-5 mb-lg-4 mb-3">{{ $category->name }}</h3>
                            <ul class="list-unstyled lh-lg nav flex-column">
                                @if($category->children->count() > 0)
                                    @foreach($category->children as $childCategory)
                                        <li class="nav-item">
                                            <a href="{{ url('/' . $childCategory->slug) }}" class="nav-link p-0 text-link d-inline">
                                                {{ $childCategory->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="nav-item">
                                        <a href="{{ url('/' . $category->slug) }}" class="nav-link p-0 text-link d-inline">
                                            Tüm {{ $category->name }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endforeach
            @endif
            
            <div class="col-lg-4 col-md-6 col-12">
                <div class="text-dark fw-normal">
                    <h3 class="fs-5 mb-lg-4 mb-3">İletişim</h3>
                    @if($settings->address)
                        @php
                            $address = html_entity_decode($settings->address, ENT_QUOTES, 'UTF-8');
                            $breakPos = stripos($address, '<br><br>');
                            if($breakPos !== false) {
                                $firstPart = substr($address, 0, $breakPos);
                                $secondPart = substr($address, $breakPos + 7); // <br><br> = 7 karakter
                                // > işaretini ve başındaki boşlukları temizle
                                $secondPart = ltrim($secondPart, ' >');
                            } else {
                                $firstPart = $address;
                                $secondPart = '';
                            }
                        @endphp
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-2">{!! trim($firstPart) !!}</div>
                            </div>
                            @if($secondPart)
                                <div class="col-6">
                                    <div class="mb-2">{!! trim($secondPart) !!}</div>
                                </div>
                            @endif
                        </div>
                    @endif
                    @if($settings->phone)
                        <p class="mb-1">{{ $settings->phone }}</p>
                    @endif
                    @if($settings->email)
                        <p>{{ $settings->email }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="border-top py-lg-6 py-4 border-secondary">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <p class="mb-0">
                        © {{ date('Y') }}, {{ $settings->site_title ?? config('app.name') }}. Tüm hakları saklıdır.
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
