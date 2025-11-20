@extends('layouts.app')
@section('title','Daftar Produk')
@section('content')

@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Storage;
@endphp

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Produk</h5>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
  </div>
  <div class="card-body">
    <form method="GET" action="{{ route('products.index') }}" class="mb-3 d-flex" style="gap:8px;">
      <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
      <button class="btn btn-outline-secondary">Search</button>
    </form>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th style="width:40px">#</th>
            <th style="width:90px">Foto</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th style="width:160px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $p)
          <tr>
            <td>{{ $loop->iteration + (method_exists($products,'currentPage') ? ($products->currentPage()-1) * $products->perPage() : 0) }}</td>

            <td style="vertical-align:middle;">
              @if(!empty($p->foto) && Storage::disk('public')->exists($p->foto))
                <img src="{{ Storage::disk('public')->url($p->foto) }}" alt="{{ $p->nama }}" style="height:56px; width:56px; object-fit:cover; border-radius:6px; border:1px solid #e6e6e6;">
              @else
                <img src="{{ asset('assets/img/placeholder-56.png') }}" alt="no-photo" style="height:56px; width:56px; object-fit:cover; border-radius:6px; border:1px solid #e6e6e6;">
              @endif
            </td>

            <td>
              <strong>{{ $p->nama }}</strong>
              @if($p->deskripsi)
                <div class="text-muted small">{{ Str::limit($p->deskripsi, 80) }}</div>
              @endif
              @if(isset($p->stok))
                <div class="text-muted small">Stok: {{ $p->stok }}</div>
              @endif
            </td>

            <td>{{ $p->category?->nama }}</td>
            <td>{{ $p->harga ? number_format($p->harga,2,',','.') : '-' }}</td>
            <td>
              <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>

              <form action="{{ route('products.destroy', $p->id) }}" method="POST" class="d-inline" id="delete-form-{{ $p->id }}">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $p->id }}" data-name="{{ $p->nama }}">Hapus</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6">Tidak ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ method_exists($products,'links') ? $products->links() : '' }}
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.btn-delete').forEach(function(btn){
    btn.addEventListener('click', function(){
      const id = this.dataset.id;
      const name = this.dataset.name || 'data ini';
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: name,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, hapus',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
          }
        });
      } else {
        if (confirm('Yakin ingin menghapus: ' + name + ' ?')) {
          document.getElementById('delete-form-' + id).submit();
        }
      }
    });
  });
});
</script>
@endpush

@endsection
