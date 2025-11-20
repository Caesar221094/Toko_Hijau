@extends('layouts.app')

@section('title','Kategori')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Kategori</h5>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">Tambah Kategori</a>
  </div>

  <div class="card-body">
    <form method="GET" action="{{ route('categories.index') }}" class="mb-3">
      <div class="input-group">
        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Cari kategori...">
        <button class="btn btn-outline-primary">Search</button>
      </div>
    </form>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th style="width:50px">#</th>
            <th>Nama</th>
            <th style="width:160px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $c)
            <tr>
              <td>{{ $loop->iteration + ($categories->currentPage()-1) * $categories->perPage() }}</td>
              <td>{{ $c->nama }}</td>
              <td>
                <a href="{{ route('categories.edit', $c->id) }}" class="btn btn-sm btn-warning">Edit</a>

                <form id="delete-form-{{ $c->id }}" action="{{ route('categories.destroy', $c->id) }}" method="POST" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="button" onclick="confirmDelete({{ $c->id }})" class="btn btn-sm btn-danger">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="3">Tidak ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $categories->links() }}
    </div>
  </div>
</div>
@endsection
