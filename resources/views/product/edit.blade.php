@extends('layouts.app')
@section('title','Edit Produk')
@section('content')
<div class="card">
  <div class="card-header"><h5>Edit Produk</h5></div>
  <div class="card-body">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama Produk</label>
        <input type="text" name="nama" value="{{ old('nama', $product->nama) }}" class="form-control" />
        @error('nama') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-control">
          <option value="">-- Pilih --</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ (old('category_id', $product->category_id) == $c->id) ? 'selected' : '' }}>{{ $c->nama }}</option>
          @endforeach
        </select>
        @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Harga</label>
        <input type="number" name="harga" value="{{ old('harga', $product->harga) }}" class="form-control" />
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $product->deskripsi) }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stok" value="{{ old('stok', $product->stok) }}" class="form-control" />
      </div>

      <div class="mb-3">
        <label class="form-label">Foto (opsional)</label>
        @if($product->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->foto))
          <div class="mb-2">
            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}" style="max-width:200px; border-radius:6px; border:1px solid #e6e6e6;">
            <p class="text-muted small mt-1">Foto saat ini: {{ basename($product->foto) }}</p>
          </div>
        @endif
        <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif" />
        <small class="text-muted">Upload foto baru untuk mengganti foto lama (Max: 2MB)</small>
        @error('foto') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <button class="btn btn-primary">Update</button>
      <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
@endsection
