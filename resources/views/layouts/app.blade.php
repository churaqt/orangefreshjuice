<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orange Fresh Juice Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --orange-primary: #FF7043;
            --orange-secondary: #FF9E80;
            --orange-light: #FFD0B0;
            --orange-bg: #FFF3E0;
            --blue-light: #E3F2FD;
            --blue: #2196F3;
            --yellow-light: #FFF8E1;
            --green-light: #E8F5E9;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background-color: var(--orange-primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand, .nav-link {
            color: white !important;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            background-color: var(--orange-primary);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border-bottom: none;
            padding: 15px 20px;
        }
        
        .btn-primary {
            background-color: var(--orange-primary);
            border-color: var(--orange-primary);
        }
        
        .btn-primary:hover {
            background-color: #E86237;
            border-color: #E86237;
        }
        
        .btn-secondary {
            background-color: #D9A566;
            border-color: #D9A566;
        }
        
        .btn-secondary:hover {
            background-color: #C89555;
            border-color: #C89555;
        }
        
        .btn-danger {
            background-color: #FF5252;
            border-color: #FF5252;
        }
        
        .table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border-top: none;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .page-title {
            color: var(--orange-primary);
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .dashboard-header {
            margin-bottom: 25px;
        }
        
        .dashboard-card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .dashboard-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .bg-orange-light {
            background-color: var(--orange-light);
            color: var(--orange-primary);
        }
        
        .bg-blue-light {
            background-color: var(--blue-light);
            color: var(--blue);
        }
        
        .bg-yellow-light {
            background-color: var(--yellow-light);
            color: #FFC107;
        }
        
        .bg-green-light {
            background-color: var(--green-light);
            color: #4CAF50;
        }
        
        .bg-orange {
            background-color: var(--orange-primary);
        }
        
        .progress {
            background-color: #f8f9fa;
        }
        
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }
        
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('logo.png') }}" alt="Orange Fresh Juice" height="40" style="background-color:rgb(252, 254, 255); border-radius: 10px; padding: 5px;">
                Orange Fresh Juice Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('stock.index') }}">
                            <i class="fas fa-boxes me-1"></i> Manajemen Stok
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i> Pemesanan
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>