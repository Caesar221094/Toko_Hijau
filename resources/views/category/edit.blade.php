@extends('layouts.app')

@section('title','Edit Kategori')

@section('content')
<div class="card">
  <div class="card-header"><h5 class="mb-0">Edit Kategori</h5></div>
  <div class="card-body">
    <form method="POST" action="{{ route('categories.update', $category->id) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $category->nama) }}" class="form-control">
        @error('nama') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <button class="btn btn-primary">Update</button>
      <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
