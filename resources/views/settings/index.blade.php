@extends('layouts.admin')

@section('title', 'Site Ayarları')

@push('styles')
<link href="{{ admin_asset('libs/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row mb-8">
    <div class="col-md-12">
        <div>
            <h2>Site Ayarları</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-inherit">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Site Ayarları</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                Genel Bilgiler
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                İletişim
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
                Sosyal Medya
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                SEO & Analytics
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                Ödeme & Kargo
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="design-tab" data-bs-toggle="tab" data-bs-target="#design" type="button" role="tab">
                Tasarım
            </button>
        </li>
    </ul>

    <div class="tab-content" id="settingsTabsContent">
        <!-- Genel Bilgiler Tab -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            <div class="card card-lg">
                <div class="card-body p-6">
                    <h5 class="mb-4">Genel Bilgiler</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="site_title" class="form-label">Site Başlığı *</label>
                            <input type="text" class="form-control @error('site_title') is-invalid @enderror" 
                                   id="site_title" name="site_title" 
                                   value="{{ old('site_title', $setting->site_title) }}" required>
                            @error('site_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="top_link" class="form-label">Üst Link</label>
                            <input type="text" class="form-control @error('top_link') is-invalid @enderror" 
                                   id="top_link" name="top_link" 
                                   value="{{ old('top_link', $setting->top_link) }}">
                            @error('top_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="site_description" class="form-label">Site Açıklaması *</label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror" 
                                      id="site_description" name="site_description" rows="3" required>{{ old('site_description', $setting->site_description) }}</textarea>
                            @error('site_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="logo" class="form-label">Logo</label>
                            @if($setting->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="favicon" class="form-label">Favicon</label>
                            @if($setting->favicon)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $setting->favicon) }}" alt="Favicon" class="img-thumbnail" style="max-width: 50px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('favicon') is-invalid @enderror" 
                                   id="favicon" name="favicon" accept="image/*">
                            @error('favicon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- İletişim Tab -->
        <div class="tab-pane fade" id="contact" role="tabpanel">
            <div class="card card-lg">
                <div class="card-body p-6">
                    <h5 class="mb-4">İletişim Bilgileri</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-posta *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email', $setting->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telefon *</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" 
                                   value="{{ old('phone', $setting->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="whatsapp" class="form-label">WhatsApp</label>
                            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" 
                                   id="whatsapp" name="whatsapp" 
                                   value="{{ old('whatsapp', $setting->whatsapp) }}">
                            @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="order_email" class="form-label">Sipariş E-postası</label>
                            <input type="email" class="form-control @error('order_email') is-invalid @enderror" 
                                   id="order_email" name="order_email" 
                                   value="{{ old('order_email', $setting->order_email) }}">
                            @error('order_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Adres *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="4" required>{{ old('address', $setting->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="google_map" class="form-label">Google Harita Kodu</label>
                            <textarea class="form-control @error('google_map') is-invalid @enderror" 
                                      id="google_map" name="google_map" rows="3">{{ old('google_map', $setting->google_map) }}</textarea>
                            @error('google_map')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sosyal Medya Tab -->
        <div class="tab-pane fade" id="social" role="tabpanel">
            <div class="card card-lg">
                <div class="card-body p-6">
                    <h5 class="mb-4">Sosyal Medya Linkleri</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="facebook_url" class="form-label">Facebook URL</label>
                            <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" 
                                   id="facebook_url" name="facebook_url" 
                                   value="{{ old('facebook_url', $setting->facebook_url) }}">
                            @error('facebook_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="instagram_url" class="form-label">Instagram URL</label>
                            <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" 
                                   id="instagram_url" name="instagram_url" 
                                   value="{{ old('instagram_url', $setting->instagram_url) }}">
                            @error('instagram_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="twitter_url" class="form-label">Twitter URL</label>
                            <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" 
                                   id="twitter_url" name="twitter_url" 
                                   value="{{ old('twitter_url', $setting->twitter_url) }}">
                            @error('twitter_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="youtube_url" class="form-label">YouTube URL</label>
                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                   id="youtube_url" name="youtube_url" 
                                   value="{{ old('youtube_url', $setting->youtube_url) }}">
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO & Analytics Tab -->
        <div class="tab-pane fade" id="seo" role="tabpanel">
            <div class="card card-lg">
                <div class="card-body p-6">
                    <h5 class="mb-4">SEO & Analytics</h5>
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="google_verification_code" class="form-label">Google Doğrulama Kodu</label>
                            <input type="text" class="form-control @error('google_verification_code') is-invalid @enderror" 
                                   id="google_verification_code" name="google_verification_code" 
                                   value="{{ old('google_verification_code', $setting->google_verification_code) }}">
                            @error('google_verification_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="analytics_code" class="form-label">Analytics Kodu</label>
                            <textarea class="form-control @error('analytics_code') is-invalid @enderror" 
                                      id="analytics_code" name="analytics_code" rows="6">{{ old('analytics_code', $setting->analytics_code) }}</textarea>
                            @error('analytics_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ödeme & Kargo Tab -->
        <div class="tab-pane fade" id="payment" role="tabpanel">
            <div class="card card-lg">
                <div class="card-body p-6">
                    <h5 class="mb-4">Ödeme & Kargo Ayarları</h5>
                    <div class="row g-4">
                        <div class="col-12">
                            <h6 class="mb-3">Ödeme Yöntemleri</h6>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="bank_transfer_enabled" 
                                       name="bank_transfer_enabled" value="1" 
                                       {{ old('bank_transfer_enabled', $setting->bank_transfer_enabled) ? 'checked' : '' }}>
                                <label class="form-check-label" for="bank_transfer_enabled">Banka Havalesi</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="online_payment_enabled" 
                                       name="online_payment_enabled" value="1" 
                                       {{ old('online_payment_enabled', $setting->online_payment_enabled) ? 'checked' : '' }}>
                                <label class="form-check-label" for="online_payment_enabled">Online Ödeme</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="cash_on_delivery_card_enabled" 
                                       name="cash_on_delivery_card_enabled" value="1" 
                                       {{ old('cash_on_delivery_card_enabled', $setting->cash_on_delivery_card_enabled) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cash_on_delivery_card_enabled">Kapıda Ödeme (Kredi Kartı)</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="cash_on_delivery_cash_enabled" 
                                       name="cash_on_delivery_cash_enabled" value="1" 
                                       {{ old('cash_on_delivery_cash_enabled', $setting->cash_on_delivery_cash_enabled) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cash_on_delivery_cash_enabled">Kapıda Ödeme (Nakit)</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="bank_account_info" class="form-label">Banka Hesap Bilgileri</label>
                            <textarea class="form-control @error('bank_account_info') is-invalid @enderror" 
                                      id="bank_account_info" name="bank_account_info" rows="3">{{ old('bank_account_info', $setting->bank_account_info) }}</textarea>
                            @error('bank_account_info')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="free_shipping_limit" class="form-label">Ücretsiz Kargo Limiti (₺)</label>
                            <input type="text" class="form-control @error('free_shipping_limit') is-invalid @enderror" 
                                   id="free_shipping_limit" name="free_shipping_limit" 
                                   value="{{ old('free_shipping_limit', $setting->free_shipping_limit) }}">
                            @error('free_shipping_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="shipping_cost" class="form-label">Kargo Ücreti (₺)</label>
                            <input type="text" class="form-control @error('shipping_cost') is-invalid @enderror" 
                                   id="shipping_cost" name="shipping_cost" 
                                   value="{{ old('shipping_cost', $setting->shipping_cost) }}">
                            @error('shipping_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="cash_on_delivery_shipping_cost" class="form-label">Kapıda Ödeme Kargo Ücreti (₺)</label>
                            <input type="text" class="form-control @error('cash_on_delivery_shipping_cost') is-invalid @enderror" 
                                   id="cash_on_delivery_shipping_cost" name="cash_on_delivery_shipping_cost" 
                                   value="{{ old('cash_on_delivery_shipping_cost', $setting->cash_on_delivery_shipping_cost) }}">
                            @error('cash_on_delivery_shipping_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasarım Tab -->
        <div class="tab-pane fade" id="design" role="tabpanel">
            <div class="card card-lg">
                <div class="card-body p-6">
                    <h5 class="mb-4">Tasarım Ayarları</h5>
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="top_text" class="form-label">Üst Yazı</label>
                            <input type="text" class="form-control @error('top_text') is-invalid @enderror" 
                                   id="top_text" name="top_text" 
                                   value="{{ old('top_text', $setting->top_text) }}">
                            @error('top_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="top_text_color" class="form-label">Üst Yazı Rengi</label>
                            <input type="color" class="form-control form-control-color @error('top_text_color') is-invalid @enderror" 
                                   id="top_text_color" name="top_text_color" 
                                   value="{{ old('top_text_color', $setting->top_text_color ?? '#FFFFFF') }}">
                            @error('top_text_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="top_background_color" class="form-label">Üst Arka Plan Rengi</label>
                            <input type="color" class="form-control form-control-color @error('top_background_color') is-invalid @enderror" 
                                   id="top_background_color" name="top_background_color" 
                                   value="{{ old('top_background_color', $setting->top_background_color ?? '#000000') }}">
                            @error('top_background_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="top_image" class="form-label">Üst Görsel</label>
                            @if($setting->top_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $setting->top_image) }}" alt="Üst Görsel" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('top_image') is-invalid @enderror" 
                                   id="top_image" name="top_image" accept="image/*">
                            @error('top_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>Ayarları Kaydet
        </button>
    </div>
</form>
@endsection

