@extends('layouts.customer')

@section('title', 'Detail Pesanan')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}">Pesanan Saya</a></li>
        <li class="breadcrumb-item active">{{ $order->order_number }}</li>
    </ol>
</nav>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detail Pesanan #{{ $order->order_number }}</h4>
            @if($order->status_pembayaran === 'pending')
                <span class="badge bg-warning">Menunggu Verifikasi</span>
            @elseif($order->status_pembayaran === 'lunas')
                <span class="badge bg-success">Lunas</span>
            @elseif($order->status_pembayaran === 'dibatalkan')
                <span class="badge bg-secondary">Dibatalkan</span>
            @else
                <span class="badge bg-danger">Ditolak</span>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Informasi Pesanan</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="150">Tanggal Pesanan:</td>
                        <td><strong>{{ $order->created_at->format('d M Y H:i') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td>
                            @if($order->status_pembayaran === 'pending')
                                <span class="text-warning">Menunggu Verifikasi</span>
                            @elseif($order->status_pembayaran === 'lunas')
                                <span class="text-success">Lunas</span>
                            @elseif($order->status_pembayaran === 'dibatalkan')
                                <span class="text-secondary">Dibatalkan</span>
                            @else
                                <span class="text-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Alamat Pengiriman:</td>
                        <td>{{ $order->shipping_address }}</td>
                    </tr>
                    @if($order->notes)
                        <tr>
                            <td>Catatan:</td>
                            <td>{{ $order->notes }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-6">
                <h6>Bukti Pembayaran</h6>
                @if($order->bukti_pembayaran)
                    <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}" class="img-fluid rounded" alt="Bukti Pembayaran" style="max-height: 300px;">
                @else
                    <p class="text-muted">Tidak ada bukti pembayaran</p>
                @endif
            </div>
        </div>

        <hr>

        <h6>Daftar Produk</h6>
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderProducts as $item)
                    <tr>
                        <td>{{ $item->product->nama }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex gap-2">
            <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Kembali ke Daftar Pesanan
            </a>
            
            @if($order->status_pembayaran === 'pending')
                <form method="POST" action="{{ route('customer.orders.cancel', $order->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan.')">
                        <i class='bx bx-x-circle'></i> Batalkan Pesanan
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
