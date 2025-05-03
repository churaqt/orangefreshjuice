<!-- resources/views/stock/create.blade.php -->

@extends('layouts.app')

@section('content')
<h1 class="page-title">Tambah Bahan Baru</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('stock.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bahan</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="category" class="form-label">Kategori</label>
                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Buah" {{ old('category') == 'Buah' ? 'selected' : '' }}>Buah</option>
                    <option value="Sayur" {{ old('category') == 'Sayur' ? 'selected' : '' }}>Sayur</option>
                    <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="quantity" class="form-label">Kuantitas</label>
                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 0) }}" min="0" required>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection