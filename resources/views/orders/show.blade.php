@extends('layouts.admin')

@section('title', 'Sipariş Detayı')

@section('content')
<div class="row mb-8">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>Sipariş Detayı</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-inherit">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-inherit">Siparişler</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sipariş #{{ $order->order_key }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('orders.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-2"></i>Geri Dön
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Sipariş Bilgileri -->
    <div class="col-lg-8">
        <!-- Sipariş Özeti -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Sipariş Özeti</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Sipariş No:</strong><br>
                        <span class="badge bg-secondary">#{{ $order->order_key }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Sipariş Tarihi:</strong><br>
                        {{ $order->order_date->format('d.m.Y H:i') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Durum:</strong><br>
                        @php
                            $statusColors = [0 => 'warning', 1 => 'info', 2 => 'primary', 3 => 'secondary', 4 => 'success', 5 => 'danger'];
                            $color = $statusColors[$order->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }}">{{ $order->status_text }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Ödeme Yöntemi:</strong><br>
                        {{ $order->payment_method_text }}
                        @if($order->credit_card_paid)
                            <span class="badge bg-success ms-2">Ödendi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sipariş Ürünleri -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sipariş Edilen Ürünler</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ürün</th>
                                <th>Seçenekler</th>
                                <th class="text-center">Adet</th>
                                <th class="text-end">Birim Fiyat</th>
                                <th class="text-end">Toplam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        @if($item->product)
                                            <div class="fw-semibold">{{ $item->product->name }}</div>
                                            <small class="text-muted">ID: {{ $item->product_id }}</small>
                                        @else
                                            <div class="fw-semibold text-muted">Ürün Bulunamadı</div>
                                            <small class="text-muted">ID: {{ $item->product_id ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $options = [];
                                            if ($item->option_text) {
                                                $options[] = strip_tags($item->option_text);
                                            }
                                            if ($item->option1 && trim(strip_tags($item->option1)) !== '' && !in_array(strip_tags($item->option1), $options)) {
                                                $options[] = strip_tags($item->option1);
                                            }
                                            if ($item->option2 && trim(strip_tags($item->option2)) !== '' && !in_array(strip_tags($item->option2), $options)) {
                                                $options[] = strip_tags($item->option2);
                                            }
                                        @endphp
                                        @if(count($options) > 0)
                                            @foreach($options as $index => $option)
                                                @if($index > 0)
                                                    <br>
                                                @endif
                                                <small class="{{ $index === 0 ? '' : 'text-muted' }}">{{ $option }}</small>
                                            @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item->price, 2) }} ₺
                                    </td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format($item->total_price, 2) }} ₺
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Ara Toplam:</strong></td>
                                <td class="text-end">
                                    {{ number_format($order->total_amount - $order->shipping_cost + $order->discount, 2) }} ₺
                                </td>
                            </tr>
                            @if($order->discount > 0)
                                <tr>
                                    <td colspan="4" class="text-end text-success"><strong>İndirim:</strong></td>
                                    <td class="text-end text-success">
                                        -{{ number_format($order->discount, 2) }} ₺
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="text-end"><strong>Kargo:</strong></td>
                                <td class="text-end">
                                    {{ number_format($order->shipping_cost, 2) }} ₺
                                </td>
                            </tr>
                            @if($order->service_fee > 0)
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Hizmet Bedeli:</strong></td>
                                    <td class="text-end">
                                        {{ number_format($order->service_fee, 2) }} ₺
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="text-end"><h5 class="mb-0">Genel Toplam:</h5></td>
                                <td class="text-end"><h5 class="mb-0">{{ number_format($order->total_amount, 2) }} ₺</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Müşteri ve Adres Bilgileri -->
    <div class="col-lg-4">
        <!-- Müşteri Bilgileri -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Müşteri Bilgileri</h6>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Adı Soyadı:</strong><br>{{ $order->customer_name }}</p>
                <p class="mb-2"><strong>Email:</strong><br>{{ $order->email }}</p>
                <p class="mb-2"><strong>Telefon:</strong><br>{{ $order->phone }}</p>
                @if($order->tc_no)
                    <p class="mb-0"><strong>TC No:</strong><br>{{ $order->tc_no }}</p>
                @endif
            </div>
        </div>

        <!-- Fatura Bilgileri -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Fatura Bilgileri</h6>
            </div>
            <div class="card-body">
                @if($order->invoice_title)
                    <p class="mb-2"><strong>Fatura Türü:</strong><br>{{ $order->invoice_type }}</p>
                    <p class="mb-2"><strong>Ünvan:</strong><br>{{ $order->invoice_title }}</p>
                    @if($order->invoice_tax_office && $order->invoice_tax_no)
                        <p class="mb-2"><strong>Vergi Dairesi:</strong><br>{{ $order->invoice_tax_office }}</p>
                        <p class="mb-2"><strong>Vergi No:</strong><br>{{ $order->invoice_tax_no }}</p>
                    @endif
                    @if($order->invoice_address)
                        <p class="mb-2"><strong>Adres:</strong><br>{{ $order->invoice_address }}</p>
                        <p class="mb-0"><strong>İl/İlçe:</strong><br>{{ $order->invoice_city }} / {{ $order->invoice_district }}</p>
                    @endif
                @else
                    <p class="text-muted mb-0">Fatura bilgisi bulunmuyor.</p>
                @endif
            </div>
        </div>

        <!-- Teslimat Bilgileri -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Teslimat Bilgileri</h6>
            </div>
            <div class="card-body">
                @if($order->shipping_name)
                    <p class="mb-2"><strong>Teslim Alacak Kişi:</strong><br>{{ $order->shipping_name }}</p>
                    @if($order->shipping_address)
                        <p class="mb-2"><strong>Adres:</strong><br>{{ $order->shipping_address }}</p>
                        <p class="mb-0"><strong>İl/İlçe:</strong><br>{{ $order->shipping_city }} / {{ $order->shipping_district }}</p>
                    @endif
                @else
                    <p class="text-muted mb-0">Teslimat bilgisi bulunmuyor.</p>
                @endif
            </div>
        </div>

        @if($order->coupon_code)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Kupon Bilgisi</h6>
                </div>
                <div class="card-body">
                    <span class="badge bg-success">{{ $order->coupon_code }}</span>
                </div>
            </div>
        @endif

        @if($order->invoice_no)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Fatura No</h6>
                </div>
                <div class="card-body">
                    <strong>{{ $order->invoice_no }}</strong>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

