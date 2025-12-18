@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<h4 class="fw-bold mb-4">
    <i class='bx bx-cart'></i> Keranjang Belanja
</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class='bx bx-check-circle'></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class='bx bx-error'></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class='bx bx-error-circle'></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(empty($cart))
    <div class="card">
        <div class="card-body text-center py-5">
            <i class='bx bx-cart-alt bx-lg text-muted mb-3' style="font-size: 4rem;"></i>
            <h5 class="text-muted">Keranjang belanja Anda kosong</h5>
            <p class="text-muted mb-4">Yuk mulai belanja sekarang!</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">
                <i class='bx bx-store'></i> Mulai Belanja
            </a>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $productId => $item)
                                    <tr class="{{ isset($item['stok']) && $item['quantity'] > $item['stok'] ? 'table-danger' : '' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(isset($item['photo']) && $item['photo'])
                                                    <img src="{{ asset('storage/' . $item['photo']) }}" width="60" height="60" class="rounded me-3" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                        <i class='bx bx-image text-muted'></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $item['name'] }}</strong>
                                                    @if(isset($item['stok']) && $item['quantity'] > $item['stok'])
                                                        <br><span class="badge bg-danger">Stok tidak cukup!</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="align-middle">
                                            <form method="POST" action="{{ route('cart.update', $productId) }}" class="d-flex align-items-center">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                                       min="1" max="{{ $item['stok'] ?? 999 }}" 
                                                       class="form-control form-control-sm quantity-input" 
                                                       data-max="{{ $item['stok'] ?? 999 }}"
                                                       data-product="{{ $item['name'] }}"
                                                       style="width: 80px;"
                                                       oninput="validateQuantity(this)">
                                                <button type="submit" class="btn btn-sm btn-primary ms-2">
                                                    <i class='bx bx-refresh'></i>
                                                </button>
                                            </form>
                                            @if(isset($item['stok']) && $item['quantity'] > $item['stok'])
                                                <small class="text-danger d-block mt-1">Melebihi stok!</small>
                                            @endif
                                            @if(isset($item['stok']) && $item['stok'] < 10)
                                                <small class="text-warning d-block mt-1">Stok tinggal {{ $item['stok'] }}</small>
                                            @endif
                                        </td>
                                        <td class="align-middle"><strong>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</strong></td>
                                        <td class="align-middle">
                                            <form method="POST" action="{{ route('cart.remove', $productId) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini dari keranjang?')">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <form method="POST" action="{{ route('cart.clear') }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Kosongkan semua keranjang?')">
                            <i class='bx bx-trash'></i> Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">Ringkasan Belanja</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Item:</span>
                        <strong>{{ array_sum(array_column($cart, 'quantity')) }} item</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <h5>Total Harga:</h5>
                        <h4 class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                    </div>
                    
                    @if(!$canCheckout)
                        <div class="alert alert-danger mb-3">
                            <i class='bx bx-error'></i> <strong>Tidak bisa checkout!</strong><br>
                            <small>Ada produk dengan jumlah melebihi stok. Silakan sesuaikan terlebih dahulu.</small>
                        </div>
                    @endif
                    
                    <div class="d-grid gap-2">
                        @if($canCheckout)
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                                <i class='bx bx-credit-card'></i> Lanjut ke Pembayaran
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class='bx bx-x'></i> Tidak Bisa Checkout
                            </button>
                        @endif
                        <a href="{{ route('shop.index') }}" class="btn btn-label-secondary">
                            <i class='bx bx-left-arrow-alt'></i> Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
function validateQuantity(input) {
    const max = parseInt(input.getAttribute('data-max'));
    const min = parseInt(input.getAttribute('min')) || 1;
    const productName = input.getAttribute('data-product');
    let value = parseInt(input.value);
    
    // Jika input kosong atau NaN
    if (isNaN(value) || input.value === '') {
        return;
    }
    
    // Jika melebihi max (stok)
    if (value > max) {
        input.value = max;
        
        // Tampilkan notifikasi
        showAlert('danger', `Jumlah "${productName}" tidak bisa melebihi stok yang tersedia (${max} unit)!`);
        
        // Highlight input
        input.classList.add('border-danger');
        setTimeout(() => {
            input.classList.remove('border-danger');
        }, 2000);
    }
    
    // Jika kurang dari min
    if (value < min) {
        input.value = min;
        showAlert('warning', `Jumlah minimal adalah ${min} unit.`);
    }
}

function showAlert(type, message) {
    // Hapus alert lama jika ada
    const oldAlert = document.querySelector('.quantity-alert');
    if (oldAlert) {
        oldAlert.remove();
    }
    
    // Buat alert baru
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show quantity-alert position-fixed`;
    alertDiv.style.cssText = 'top: 80px; right: 20px; z-index: 9999; max-width: 400px;';
    alertDiv.innerHTML = `
        <i class='bx bx-error-circle'></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove setelah 4 detik
    setTimeout(() => {
        alertDiv.remove();
    }, 4000);
}

// Prevent paste yang melebihi stok
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    quantityInputs.forEach(input => {
        // Validate on paste
        input.addEventListener('paste', function(e) {
            setTimeout(() => {
                validateQuantity(this);
            }, 10);
        });
        
        // Validate on keyup (untuk copy-paste via keyboard)
        input.addEventListener('keyup', function() {
            validateQuantity(this);
        });
        
        // Prevent scrolling to change value unintentionally
        input.addEventListener('wheel', function(e) {
            e.preventDefault();
        });
    });
});
</script>
@endpush
