{{-- Footer content will be added here --}}
<footer class="bg-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0">&copy; {{ date('Y') }} {{ $settings->site_title ?? config('app.name') }}. Tüm hakları saklıdır.</p>
                @if($settings->address)
                    <p class="mb-0 mt-2"><small>{{ $settings->address }}</small></p>
                @endif
                @if($settings->phone)
                    <p class="mb-0"><small>Tel: {{ $settings->phone }}</small></p>
                @endif
                @if($settings->email)
                    <p class="mb-0"><small>Email: {{ $settings->email }}</small></p>
                @endif
            </div>
        </div>
    </div>
</footer>
