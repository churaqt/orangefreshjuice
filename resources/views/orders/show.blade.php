@extends('layouts.app')

@section('content')
<h1 class="page-title">Detail Pesanan #{{ $order->id }}</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">ID Pesanan:</div>
                    <div class="col-md-8">#{{ $order->id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Nama Pelanggan:</div>
                    <div class="col-md-8">{{ $order->customer_name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Tanggal Pesanan:</div>
                    <div class="col-md-8">{{ $order->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Total Harga:</div>
                    <div class="col-md-8">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>
                <div class="row">
                    <div class="col-md-4 fw-bold">Status:</div>
                    <div class="col-md-8">
                        <form action="{{ route('orders.update.status', $order) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i> Item Pesanan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Tipe Juice</th>
                        <th>Jumlah</th>
                        <th>Level Gula</th>
                        <th>Level Es</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>
                            @if($item->juice_type == 'fresh')
                                <span class="badge bg-success">Fresh Juice</span>
                            @elseif($item->juice_type == 'mix')
                                <span class="badge bg-primary">Mix Juice</span>
                            @else
                                <span class="badge bg-info">Berry Series</span>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->sugar_level }}</td>
                        <td>{{ $item->ice_level }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Total:</td>
                        <td class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<div class="mt-4">
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>
@endsection