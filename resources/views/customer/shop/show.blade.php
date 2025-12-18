@extends('layouts.customer')

@section('title', $product->name)

@section('content')
<div class="row">
    <div class="col-md-5">
        @if($product->foto)
            <img src="{{ asset('storage/' . $product->foto) }}" class="img-fluid rounded" alt="{{ $product->nama }}">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
            </div>
        @endif
    </div>
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Katalog</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category_id]) }}">{{ $product->category->nama }}</a></li>
                <li class="breadcrumb-item active">{{ $product->nama }}</li>
            </ol>
        </nav>

        <h2>{{ $product->nama }}</h2>
        <p class="text-muted">Kategori: {{ $product->category->nama }}</p>
        
        <h3 class="text-primary mb-3">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>
        
        <div class="mb-3">
            <p><i class="bi bi-box-seam"></i> <strong>Stok:</strong> {{ $product->stok }} unit</p>
        </div>

        <div class="mb-4">
            <h5>Deskripsi:</h5>
            <p>{{ $product->deskripsi }}</p>
        </div>

        @if($product->stok > 0)
            <form method="POST" action="{{ route('cart.add', $product->id) }}" class="mb-3">
                @csrf
                <div class="input-group mb-3" style="max-width: 200px;">
                    <span class="input-group-text">Jumlah</span>
                    <input type="number" name="quantity" id="productQuantity" class="form-control" 
                           value="1" min="1" max="{{ $product->stok }}"
                           data-max="{{ $product->stok }}"
                           oninput="validateProductQuantity(this)">
                </div>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                </button>
            </form>
        @else
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Produk ini sedang tidak tersedia (stok habis).
            </div>
        @endif

        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Katalog
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function validateProductQuantity(input) {
    const max = parseInt(input.getAttribute('data-max'));
    const min = parseInt(input.getAttribute('min')) || 1;
    let value = parseInt(input.value);
    
    if (isNaN(value) || input.value === '') {
        return;
    }
    
    if (value > max) {
        input.value = max;
        alert(`Jumlah tidak bisa melebihi stok yang tersedia (${max} unit)!`);
        input.classList.add('border-danger');
        setTimeout(() => {
            input.classList.remove('border-danger');
        }, 2000);
    }
    
    if (value < min) {
        input.value = min;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('productQuantity');
    if (quantityInput) {
        quantityInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                validateProductQuantity(this);
            }, 10);
        });
        
        quantityInput.addEventListener('wheel', function(e) {
            e.preventDefault();
        });
    }
});
</script>
@endpush
