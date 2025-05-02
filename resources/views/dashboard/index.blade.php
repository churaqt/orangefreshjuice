@extends('layouts.app')

@section('content')
<div class="dashboard-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="text-muted">Selamat datang di sistem admin Orange Fresh Juice</p>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="dashboard-icon bg-orange-light">
                        <i class="fas fa-apple-alt"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Stok Buah</h6>
                        <h2 class="mb-0">{{ $totalFruits }}</h2>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 5px;">
                    <div class="progress-bar bg-orange" style="width: 75%"></div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-primary">Lihat Stok</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="dashboard-icon bg-blue-light">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Pesanan</h6>
                        <h2 class="mb-0">{{ $totalOrders }}</h2>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 5px;">
                    <div class="progress-bar bg-blue" style="width: 65%"></div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat Pesanan</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="dashboard-icon bg-yellow-light">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Pesanan Pending</h6>
                        <h2 class="mb-0">{{ $pendingOrders }}</h2>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 5px;">
                    <div class="progress-bar bg-warning" style="width: {{ ($pendingOrders / max($totalOrders, 1)) * 100 }}%"></div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-warning">Proses Pesanan</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="dashboard-icon bg-green-light">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Pendapatan</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 5px;">
                    <div class="progress-bar bg-success" style="width: 80%"></div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-success">
                        <i class="fas fa-arrow-up"></i> Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i> Pesanan Terbaru</h5>
                <a href="{{ route('orders.index') }}" class="btn btn-sm fw-bolder text-white bg-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($order->status == 'processing')
                                        <span class="badge bg-info">Processing</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @if(count($recentOrders) == 0)
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada pesanan terbaru</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Stok Menipis</h5>
                <a href="{{ route('stock.index') }}" class="btn btn-sm fw-bolder text-white bg-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
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
                            @foreach($lowStockItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->category == 'Buah' ? 'success' : ($item->category == 'Sayur' ? 'info' : 'secondary') }}">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->quantity <= 5 ? 'danger' : 'warning' }}">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('stock.edit', $item) }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @if(count($lowStockItems) == 0)
                                <tr>
                                    <td colspan="4" class="text-center">Semua stok dalam jumlah cukup</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection