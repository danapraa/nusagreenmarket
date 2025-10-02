<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin NusaGreenMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2d5a27;
            --secondary: #4CAF50;
            --accent: #8BC34A;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0;
            position: fixed;
            width: 250px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 10px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: -20px -20px 20px -20px;
        }
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            border-radius: 10px;
        }
        .stat-card {
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .badge-custom {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="p-4">
                <h4 class="mb-0"><img src="/storage/images/logocompro.png" alt="NusaGreenMarket Logo" width="50" height="50"> NusaGreenMarket</h4>
                <small class="text-white-50">Admin Panel</small>
            </div>
            <nav class="nav flex-column mt-3">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line me-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-folder me-2"></i> Kategori
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag me-2"></i> Produk
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-box me-2"></i> Pesanan
                </a>
                <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Customer
                </a>
                <hr class="text-white-50 mx-3">
                <a href="{{ route('customer.home') }}" class="nav-link" target="_blank">
                    <i class="fas fa-store me-2"></i> Lihat Toko
                </a>
                <a href="{{ route('logout') }}" class="nav-link" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Topbar -->
            <div class="topbar">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">@yield('title')</h5>
                    <div>
                        <span class="me-3">{{ auth()->user()->name }}</span>
                        <span class="badge bg-success">Admin</span>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>