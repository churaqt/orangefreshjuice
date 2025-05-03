@extends('layouts.app')

@section('content')
<h1 class="page-title">Manajemen Stok</h1>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('stock.create') }}" class="btn btn-primary">Tambah Bahan</a>
            
            <form action="{{ route('stock.search') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari bahan...">
                <button type="submit" class="btn btn-outline-primary">Cari</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Bahan</th>
                        <th>Kategori</th>
                        <th>Kuantitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fruits as $fruit)
                    <tr>
                        <td>{{ $fruit->name }}</td>
                        <td>{{ $fruit->category }}</td>
                        <td>{{ $fruit->quantity }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('stock.edit', $fruit) }}" class="btn btn-secondary">Edit</a>
                                <form action="{{ route('stock.destroy', $fruit) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus bahan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection