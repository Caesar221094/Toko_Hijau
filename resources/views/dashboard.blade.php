@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12 mb-4">
            <h4 class="fw-bold text-capitalize">Dashboard Toko Hijau Seller</h4>
            <p class="text-muted">Ringkasan Pesanan Dan Omset Toko</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-5 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class='bx bx-money bx-lg'></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Total Omset</small>
                            <h4 class="mb-0 text-success">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                <div class="card border-start border-5 border-primary cursor-pointer" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class='bx bx-shopping-bag bx-lg'></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">Total Pesanan</small>
                                <h4 class="mb-0">{{ $stats['total_orders'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-decoration-none">
                <div class="card border-start border-5 border-warning cursor-pointer" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class='bx bx-time-five bx-lg'></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">Menunggu Validasi</small>
                                <h4 class="mb-0">{{ $stats['pending_orders'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Completed Orders -->
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.orders.index', ['status' => 'lunas']) }}" class="text-decoration-none">
                <div class="card border-start border-5 border-info cursor-pointer" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class='bx bx-check-circle bx-lg'></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block">Pesanan Lunas</small>
                                <h4 class="mb-0">{{ $stats['lunas_orders'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class='bx bx-x-circle bx-sm'></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Ditolak</small>
                            <h5 class="mb-0">{{ $stats['ditolak_orders'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class='bx bx-block bx-sm'></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Dibatalkan</small>
                            <h5 class="mb-0">{{ $stats['dibatalkan_orders'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class='bx bx-box bx-sm'></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Total Produk</small>
                            <h5 class="mb-0">{{ $stats['total_products'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class='bx bx-user bx-sm'></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Total Customer</small>
                            <h5 class="mb-0">{{ $stats['total_customers'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pesanan Terbaru</h5>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                <i class='bx bx-list-ul'></i> Lihat Semua
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->user->name }}</td>
                            <td><strong class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($order->status_pembayaran === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->status_pembayaran === 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($order->status_pembayaran === 'dibatalkan')
                                    <span class="badge bg-secondary">Dibatalkan</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class='bx bx-show'></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class='bx bx-info-circle bx-lg d-block mb-2'></i>
                                Belum Ada Pesanan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
@endpush
