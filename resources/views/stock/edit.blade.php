<!-- resources/views/stock/edit.blade.php -->

@extends('layouts.app')

@section('content')
<h1 class="page-title">Edit Bahan</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('stock.update', $stock) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bahan</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $stock->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="category" class="form-label">Kategori</label>
                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Buah" {{ old('category', $stock->category) == 'Buah' ? 'selected' : '' }}>Buah</option>
                    <option value="Sayur" {{ old('category', $stock->category) == 'Sayur' ? 'selected' : '' }}>Sayur</option>
                </select>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="quantity" class="form-label">Kuantitas</label>
                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $stock->quantity) }}" min="0" required>
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