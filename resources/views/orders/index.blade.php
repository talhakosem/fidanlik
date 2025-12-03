@extends('layouts.admin')

@section('title', 'Geçmiş Siparişler')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Geçmiş Siparişler</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filtreler -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Arama</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Sipariş no, müşteri adı, email, telefon...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Durum</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tümü</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Beklemede</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Onaylandı</option>
                            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Hazırlanıyor</option>
                            <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Kargoya Verildi</option>
                            <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Teslim Edildi</option>
                            <option value="5" {{ request('status') === '5' ? 'selected' : '' }}>İptal Edildi</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Filtrele
                        </button>
                    </div>
                    @if(request()->hasAny(['search', 'status']))
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x-circle me-2"></i>Temizle
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sipariş No</th>
                                <th>Müşteri</th>
                                <th>Email</th>
                                <th>Telefon</th>
                                <th>Tarih</th>
                                <th>Tutar</th>
                                <th>Durum</th>
                                <th>Ödeme</th>
                                <th class="text-end">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $order->order_key }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                                    </td>
                                    <td>
                                        <small>{{ $order->email }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $order->phone }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $order->order_date->format('d.m.Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ number_format($order->total_amount, 2) }} ₺</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                0 => 'warning',
                                                1 => 'info',
                                                2 => 'primary',
                                                3 => 'secondary',
                                                4 => 'success',
                                                5 => 'danger',
                                            ];
                                            $color = $statusColors[$order->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ $order->status_text }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $order->payment_method_text }}</small>
                                        @if($order->credit_card_paid)
                                            <br><span class="badge bg-success">Ödendi</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Detay">
                                            <i class="bi bi-eye"></i> Detay
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="bi bi-inbox display-4 text-muted"></i>
                                        <p class="text-muted mb-0 mt-2">Henüz sipariş yok.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($orders->hasPages())
        <div class="mt-4">
            <nav>
                <ul class="pagination mb-0">
                    {{-- Previous Page Link --}}
                    @if ($orders->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}">Previous</a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $currentPage = $orders->currentPage();
                        $lastPage = $orders->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp

                    @if($startPage > 1)
                        <li class="page-item"><a class="page-link" href="{{ $orders->url(1) }}">1</a></li>
                        @if($startPage > 2)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endif

                    @for ($page = $startPage; $page <= $endPage; $page++)
                        @if ($page == $currentPage)
                            <li class="page-item"><a class="page-link active" href="#!">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $orders->url($page) }}">{{ $page }}</a></li>
                        @endif
                    @endfor

                    @if($endPage < $lastPage)
                        @if($endPage < $lastPage - 1)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item"><a class="page-link" href="{{ $orders->url($lastPage) }}">{{ $lastPage }}</a></li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($orders->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}">Next</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection

