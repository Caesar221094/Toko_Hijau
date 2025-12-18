@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin / <a href="{{ route('admin.orders.index') }}">Pesanan</a> /</span> Detail #{{ $order->order_number }}
    </h4>

    <div class="row">
        <!-- Informasi Pesanan -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                    @if($order->status_pembayaran === 'pending')
                        <span class="badge bg-label-warning">Pending</span>
                    @elseif($order->status_pembayaran === 'lunas')
                        <span class="badge bg-label-success">Lunas</span>
                    @elseif($order->status_pembayaran === 'dibatalkan')
                        <span class="badge bg-label-secondary">Dibatalkan</span>
                    @else
                        <span class="badge bg-label-danger">Ditolak</span>
                    @endif
                </div>
                <div class="card-body">
                    <h6 class="text-muted">Informasi Customer</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nama:</strong> {{ $order->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                            @if($order->user->telepon)
                                <p class="mb-1"><strong>Telepon:</strong> {{ $order->user->telepon }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                            <p class="mb-1"><strong>Status Pembayaran:</strong> 
                                @if($order->status_pembayaran === 'pending')
                                    <span class="text-warning">Menunggu Verifikasi</span>
                                @elseif($order->status_pembayaran === 'lunas')
                                    <span class="text-success">Lunas</span>
                                @elseif($order->status_pembayaran === 'dibatalkan')
                                    <span class="text-secondary">Dibatalkan</span>
                                @else
                                    <span class="text-danger">Ditolak</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-muted">Alamat Pengiriman</h6>
                    <p>{{ $order->shipping_address }}</p>

                    @if($order->notes)
                        <hr>
                        <h6 class="text-muted">Catatan</h6>
                        <p>{{ $order->notes }}</p>
                    @endif

                    <hr>

                    <h6 class="text-muted mb-3">Daftar Produk</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderProducts as $item)
                                    <tr>
                                        <td>{{ $item->product->nama }}</td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><h5 class="text-primary mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($order->status_pembayaran === 'pending')
                <div class="card border-2 border-warning">
                    <div class="card-header bg-label-warning">
                        <h5 class="mb-0"><i class='bx bx-time-five'></i> Menunggu Validasi</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Periksa bukti pembayaran di sebelah kanan, kemudian setujui atau tolak pesanan ini.</p>
                        <div class="row g-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-success w-100" onclick="approveOrderDetail()">
                                    <i class='bx bx-check-circle me-1'></i> Setujui Pesanan
                                </button>
                                <form id="approve-form" method="POST" action="{{ route('admin.orders.approve', $order->id) }}" class="d-none">
                                    @csrf
                                    @method('PATCH')
                                </form>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger w-100" onclick="rejectOrderDetail()">
                                    <i class='bx bx-x-circle me-1'></i> Tolak Pesanan
                                </button>
                                <form id="reject-form" method="POST" action="{{ route('admin.orders.reject', $order->id) }}" class="d-none">
                                    @csrf
                                    @method('PATCH')
                                </form>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class='bx bx-info-circle'></i> <strong>Info:</strong> Jika pesanan ditolak, stok produk akan dikembalikan secara otomatis.
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bukti Pembayaran -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Bukti Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($order->bukti_pembayaran)
                        <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}" class="img-fluid rounded mb-3" alt="Bukti Pembayaran">
                        <a href="{{ asset('storage/' . $order->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                            <i class='bx bx-download'></i> Download / Lihat Full
                        </a>
                    @else
                        <div class="text-center py-4">
                            <i class='bx bx-image-add bx-lg text-muted mb-2'></i>
                            <p class="text-muted">Tidak ada bukti pembayaran</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-label-secondary w-100">
                        <i class='bx bx-arrow-back'></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
<script>
function approveOrderDetail() {
    Swal.fire({
        title: 'Setujui Pesanan?',
        html: `
            <div class="text-start">
                <p>Pesanan <strong>{{ $order->order_number }}</strong> akan disetujui dengan detail:</p>
                <table class="table table-sm">
                    <tr>
                        <td>Customer:</td>
                        <td><strong>{{ $order->user->name }}</strong></td>
                    </tr>
                    <tr>
                        <td>Total:</td>
                        <td><strong class="text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Status Baru:</td>
                        <td><span class="badge bg-success">Lunas</span></td>
                    </tr>
                </table>
            </div>
        `,
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
        buttonsStyling: false,
        width: '500px'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('approve-form').submit();
        }
    });
}

function rejectOrderDetail() {
    Swal.fire({
        title: 'Tolak Pesanan?',
        html: `
            <div class="text-start">
                <p>Pesanan <strong>{{ $order->order_number }}</strong> akan ditolak:</p>
                <div class="alert alert-danger">
                    <i class="bx bx-error-circle"></i> <strong>Perhatian!</strong><br>
                    Status akan berubah menjadi <span class="badge bg-danger">Ditolak</span>
                </div>
                <div class="alert alert-warning mb-0">
                    <i class="bx bx-info-circle"></i> Stok produk akan dikembalikan otomatis:
                    <ul class="mb-0 mt-2">
                        @foreach($order->orderProducts as $item)
                        <li>{{ $item->product->nama }} (+{{ $item->quantity }} stok)</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        `,
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
        buttonsStyling: false,
        width: '500px'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject-form').submit();
        }
    });
}

// Show success/error alerts
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
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
