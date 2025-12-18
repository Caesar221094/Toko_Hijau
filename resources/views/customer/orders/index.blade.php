@extends('layouts.customer')

@section('title', 'Pesanan Saya')

@section('content')
<h2 class="mb-4"><i class="bi bi-list-check"></i> Pesanan Saya</h2>

@if($orders->count() > 0)
    @foreach($orders as $order)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>Order #{{ $order->order_number }}</strong>
                    <span class="text-muted ms-2">{{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
                <div>
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
                <div class="row">
                    <div class="col-md-8">
                        <h6>Produk:</h6>
                        <ul class="list-unstyled">
                            @foreach($order->orderProducts as $item)
                                <li class="mb-1">
                                    <i class="bi bi-box"></i> {{ $item->product->nama }} 
                                    <span class="text-muted">({{ $item->quantity }}x)</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-4 text-end">
                        <h5 class="text-primary mb-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5>
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Detail Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Anda belum memiliki pesanan.
        <a href="{{ route('shop.index') }}" class="alert-link">Mulai belanja sekarang!</a>
    </div>
@endif
@endsection
