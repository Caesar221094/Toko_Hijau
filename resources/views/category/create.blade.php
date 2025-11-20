@extends('layouts.app')

@section('title','Tambah Kategori')

@section('content')
<div class="card">
  <div class="card-header"><h5 class="mb-0">Tambah Kategori</h5></div>
  <div class="card-body">
    <form method="POST" action="{{ route('categories.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control">
        @error('nama') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <button class="btn btn-primary">Simpan</button>
      <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
