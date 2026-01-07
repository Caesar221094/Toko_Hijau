@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<h2 class="mb-4"><i class="bi bi-credit-card"></i> Checkout & Pembayaran Produk</h2>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <a href="{{ route('cart.index') }}" class="alert-link">Kembali ke keranjang</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Ringkasan Pesanan</h5>
            </div>
            <div class="card-body">
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
                        @foreach($cart as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pengiriman & Pembayaran</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('checkout.process') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                        <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" 
                                  rows="3" required>{{ old('shipping_address', auth()->user()->alamat) }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>

                    <hr>

                    <div class="alert alert-info">
                        <h6 class="text-capitalize"><i class="bi bi-info-circle"></i> Informasi Pembayaran</h6>
                        <p class="mb-1 text-capitalize">Silakan Transfer Ke Rekening Berikut:</p>
                        <p class="mb-0"><strong>Bank BCA: 1234567890 A.n. Toko Hijau</strong></p>
                        <p class="mb-0"><strong>Total: Rp {{ number_format($total, 0, ',', '.') }}</strong></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                        <input type="file" name="bukti_pembayaran" class="form-control @error('bukti_pembayaran') is-invalid @enderror" 
                               accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                        @error('bukti_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Proses Pesanan
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Total Pembayaran</h5>
            </div>
            <div class="card-body">
                <h3 class="text-primary mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
