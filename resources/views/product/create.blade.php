@extends('layouts.app')
@section('title','Tambah Produk')
@section('content')
<div class="card">
  <div class="card-header"><h5>Tambah Produk</h5></div>
  <div class="card-body">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama Produk</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        @error('nama') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-control" required>
          <option value="">-- Pilih --</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ old('category_id')==$c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
          @endforeach
        </select>
        @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Harga</label>
        <input type="number" name="harga" class="form-control" value="{{ old('harga') }}">
        @error('harga') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stok" class="form-control" value="{{ old('stok', 0) }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Foto (optional)</label>
        <input type="file" name="foto" class="form-control">
        @error('foto') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <button class="btn btn-primary">Simpan</button>
      <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
@endsection
