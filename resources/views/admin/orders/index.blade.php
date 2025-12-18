@extends('layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> Kelola Pesanan
    </h4>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bx-shopping-bag bx-lg text-primary'></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Pesanan</span>
                    <h3 class="card-title mb-2">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bx-time-five bx-lg text-warning'></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Pending</span>
                    <h3 class="card-title text-warning mb-2">{{ $stats['pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bx-check-circle bx-lg text-success'></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Lunas</span>
                    <h3 class="card-title text-success mb-2">{{ $stats['lunas'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class='bx bx-x-circle bx-lg text-danger'></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Ditolak</span>
                    <h3 class="card-title text-danger mb-2">{{ $stats['ditolak'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="nav-align-top">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">
                            <i class='bx bx-grid-alt'></i> Semua ({{ $stats['total'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}">
                            <i class='bx bx-time-five'></i> Pending ({{ $stats['pending'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index', ['status' => 'lunas']) }}" class="nav-link {{ request('status') == 'lunas' ? 'active' : '' }}">
                            <i class='bx bx-check-circle'></i> Lunas ({{ $stats['lunas'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index', ['status' => 'ditolak']) }}" class="nav-link {{ request('status') == 'ditolak' ? 'active' : '' }}">
                            <i class='bx bx-x-circle'></i> Ditolak ({{ $stats['ditolak'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index', ['status' => 'dibatalkan']) }}" class="nav-link {{ request('status') == 'dibatalkan' ? 'active' : '' }}">
                            <i class='bx bx-block'></i> Dibatalkan ({{ $stats['dibatalkan'] }})
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <h5 class="card-header">Daftar Pesanan</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Bukti Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>
                                <i class='bx bx-user'></i> {{ $order->user->name }}<br>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($order->status_pembayaran === 'pending')
                                    <span class="badge bg-label-warning">Pending</span>
                                @elseif($order->status_pembayaran === 'lunas')
                                    <span class="badge bg-label-success">Lunas</span>
                                @elseif($order->status_pembayaran === 'dibatalkan')
                                    <span class="badge bg-label-secondary">Dibatalkan</span>
                                @else
                                    <span class="badge bg-label-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($order->bukti_pembayaran)
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#buktiModal{{ $order->id }}">
                                        <i class='bx bx-image'></i> Lihat
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class='bx bx-show'></i>
                                    </a>
                                    @if($order->status_pembayaran === 'pending')
                                        <button type="button" class="btn btn-sm btn-icon btn-success" data-bs-toggle="tooltip" title="Setujui" onclick="approveOrder({{ $order->id }}, '{{ $order->order_number }}')">
                                            <i class='bx bx-check'></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip" title="Tolak" onclick="rejectOrder({{ $order->id }}, '{{ $order->order_number }}')">
                                            <i class='bx bx-x'></i>
                                        </button>
                                        
                                        <form id="approve-form-{{ $order->id }}" method="POST" action="{{ route('admin.orders.approve', $order->id) }}" class="d-none">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                        <form id="reject-form-{{ $order->id }}" method="POST" action="{{ route('admin.orders.reject', $order->id) }}" class="d-none">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Bukti Pembayaran -->
                        @if($order->bukti_pembayaran)
                            <div class="modal fade" id="buktiModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Bukti Pembayaran - #{{ $order->order_number }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}" class="img-fluid" alt="Bukti Pembayaran">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class='bx bx-info-circle bx-lg text-muted'></i>
                                <p class="mt-2">Tidak ada pesanan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

function approveOrder(orderId, orderNumber) {
    Swal.fire({
        title: 'Setujui Pesanan?',
        html: `Pesanan <strong>${orderNumber}</strong> akan disetujui dan status berubah menjadi <span class="badge bg-success">Lunas</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bx bx-check"></i> Ya, Setujui',
        cancelButtonText: '<i class="bx bx-x"></i> Batal',
        customClass: {
            confirmButton: 'btn btn-success me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('approve-form-' + orderId).submit();
        }
    });
}

function rejectOrder(orderId, orderNumber) {
    Swal.fire({
        title: 'Tolak Pesanan?',
        html: `Pesanan <strong>${orderNumber}</strong> akan ditolak.<br><br><div class="alert alert-warning mb-0"><i class="bx bx-info-circle"></i> Stok produk akan dikembalikan otomatis</div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bx bx-x"></i> Ya, Tolak',
        cancelButtonText: '<i class="bx bx-block"></i> Batal',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject-form-' + orderId).submit();
        }
    });
}

// Show success/error alerts
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        customClass: {
            confirmButton: 'btn btn-success'
        },
        buttonsStyling: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        customClass: {
            confirmButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });
@endif
</script>
@endpush
