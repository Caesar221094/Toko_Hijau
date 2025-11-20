{{-- resources/views/category/index.blade.php --}}
@extends('layouts.app')

@section('title','Daftar Kategori')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Kategori</h5>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">Tambah Kategori</a>
  </div>

  <div class="card-body">
    {{-- pesan sukses --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- pencarian --}}
    <form method="GET" action="{{ route('categories.index') }}" class="mb-3 d-flex" style="gap:8px;">
      <input type="text" name="keyword" class="form-control" placeholder="Cari kategori..." value="{{ request('keyword') }}">
      <button class="btn btn-outline-secondary">Search</button>
    </form>

    <div class="table-responsive">
      <table class="table table-borderless">
        <thead>
          <tr>
            <th style="width:60px">#</th>
            <th>NAMA</th>
            <th style="width:180px">AKSI</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $c)
          <tr>
            <td class="align-middle text-center">{{ $loop->iteration + (method_exists($categories,'currentPage') ? ($categories->currentPage()-1) * $categories->perPage() : 0) }}</td>
            <td class="align-middle">{{ $c->nama }}</td>
            <td class="align-middle">
              <a href="{{ route('categories.edit', $c->id) }}" class="btn btn-sm btn-warning">Edit</a>

              {{-- form hapus: gunakan class dan data-* agar JS bisa menangani --}}
              <form action="{{ route('categories.destroy', $c->id) }}" method="POST" class="d-inline form-delete">
                @csrf
                @method('DELETE')
                <button type="button"
                        class="btn btn-sm btn-danger btn-delete"
                        data-name="{{ $c->nama }}">
                  Hapus
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="3" class="text-center">Tidak ada data</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- pagination --}}
    <div class="mt-3">
      {{ method_exists($categories,'links') ? $categories->links() : '' }}
    </div>
  </div>
</div>

@endsection

@section('scripts')
  {{-- pastikan layouts.app sudah memuat SweetAlert2 (kamu sudah add CDN). 
      Script berikut akan menangkap klik tombol .btn-delete lalu memunculkan konfirmasi.
  --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // tangkap semua tombol hapus
      const deleteButtons = document.querySelectorAll('.btn-delete');

      deleteButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
          const name = this.dataset.name || 'data ini';
          const form = this.closest('form');

          // gunakan SweetAlert2 jika tersedia, kalau tidak fallback ke confirm()
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              title: 'Yakin ingin menghapus?',
              text: name,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Ya, hapus',
              cancelButtonText: 'Batal',
            }).then((result) => {
              if (result.isConfirmed) {
                form.submit();
              }
            });
          } else {
            if (confirm('Yakin ingin menghapus: ' + name + ' ?')) {
              form.submit();
            }
          }
        });
      });
    });
  </script>
  
@endsection
