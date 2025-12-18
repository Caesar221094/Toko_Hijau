@extends('layouts.customer')

@section('title', 'Katalog Produk')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold">
            <i class='bx bx-store'></i> Katalog Produk
        </h4>
        <p class="text-muted">Temukan produk yang Anda butuhkan</p>
    </div>
</div>

<!-- Search -->
<div class="row mb-4">
    <div class="col-md-12">
        <form method="GET" action="{{ route('shop.index') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class='bx bx-search'></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Category Filter - Modern Card Style -->
<div class="mb-4">
    <h6 class="text-muted mb-3">Filter Kategori</h6>
    <div class="row g-3">
        <div class="col-md-2 col-sm-4 col-6">
            <a href="{{ route('shop.index') }}" class="text-decoration-none">
                <div class="card {{ !request('category') ? 'border-primary' : 'border-light' }} h-100 text-center" style="cursor: pointer;">
                    <div class="card-body py-3">
                        <i class='bx bx-grid-alt bx-lg {{ !request('category') ? 'text-primary' : 'text-muted' }}'></i>
                        <h6 class="mt-2 mb-0 {{ !request('category') ? 'text-primary fw-bold' : 'text-muted' }}">Semua</h6>
                    </div>
                </div>
            </a>
        </div>
        @foreach($categories as $cat)
            <div class="col-md-2 col-sm-4 col-6">
                <a href="{{ route('shop.index', ['category' => $cat->id]) }}" class="text-decoration-none">
                    <div class="card {{ request('category') == $cat->id ? 'border-primary' : 'border-light' }} h-100 text-center" style="cursor: pointer;">
                        <div class="card-body py-3">
                            @php
                                $icons = [
                                    'Elektronik' => 'bx-laptop',
                                    'Fashion' => 'bx-shopping-bag',
                                    'Makanan' => 'bx-food-menu',
                                    'Olahraga' => 'bx-football',
                                    'Buku' => 'bx-book',
                                    'Mainan' => 'bx-game',
                                    'Peralatan Rumah' => 'bx-home',
                                    'Kesehatan' => 'bx-plus-medical'
                                ];
                                $icon = $icons[$cat->nama] ?? 'bx-category';
                            @endphp
                            <i class='bx {{ $icon }} bx-lg {{ request('category') == $cat->id ? 'text-primary' : 'text-muted' }}'></i>
                            <h6 class="mt-2 mb-0 small {{ request('category') == $cat->id ? 'text-primary fw-bold' : 'text-muted' }}">{{ $cat->nama }}</h6>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<hr class="my-4">

<!-- Products Grid -->
@if($products->count() > 0)
    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100">
                    @if($product->foto)
                        <img src="{{ asset('storage/' . $product->foto) }}" class="card-img-top" alt="{{ $product->nama }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class='bx bx-image text-muted' style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-label-primary mb-2 align-self-start">{{ $product->category->nama }}</span>
                        <h5 class="card-title">{{ $product->nama }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($product->deskripsi, 60) }}</p>
                        <div class="mt-auto">
                            <h4 class="text-primary mb-2">Rp {{ number_format($product->harga, 0, ',', '.') }}</h4>
                            <p class="mb-3 text-muted small">
                                <i class='bx bx-package'></i> Stok: <strong>{{ $product->stok }}</strong> unit
                            </p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('shop.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class='bx bx-show'></i> Detail
                                </a>
                                @if($product->stok > 0)
                                    <form method="POST" action="{{ route('cart.add', $product->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                            <i class='bx bx-cart-add'></i> Tambah ke Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary w-100" disabled>
                                        <i class='bx bx-x'></i> Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@else
    <div class="alert alert-info">
        <i class='bx bx-info-circle'></i> Tidak ada produk yang ditemukan.
    </div>
@endif
@endsection
