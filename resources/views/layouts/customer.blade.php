<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NusaGreenMarket')</title>
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
        }
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary) !important;
        }
        .nav-link {
            color: #333 !important;
            font-weight: 500;
            margin: 0 10px;
        }
        .nav-link:hover {
            color: var(--secondary) !important;
        }
        .btn-primary {
            background: var(--secondary);
            border: none;
        }
        .btn-primary:hover {
            background: var(--primary);
        }
        .product-card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            border-radius: 10px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #FF6B35;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }
        .footer {
            background: #1a2f1a;
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #FF6B35;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('customer.home') }}">
                <i class="fas fa-leaf"></i> NusaGreenMarket
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.home') }}">Beranda</a>
                    </li>
                    @auth
                        @if(auth()->user()->isCustomer())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.orders.index') }}">Pesanan Saya</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('customer.cart') }}">
                                    <i class="fas fa-shopping-cart"></i> Keranjang
                                    @php
                                        $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
                                        $itemCount = $cart ? $cart->items->sum('quantity') : 0;
                                    @endphp
                                    @if($itemCount > 0)
                                        <span class="cart-badge">{{ $itemCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-leaf"></i> NusaGreenMarket</h5>
                    <p class="text-white-50">Sayuran segar langsung dari petani ke meja Anda.</p>
                </div>
                <div class="col-md-4">
                    <h6>Kontak</h6>
                    <p class="text-white-50 mb-1"><i class="fas fa-phone me-2"></i> +62 812-3456-7890</p>
                    <p class="text-white-50"><i class="fas fa-envelope me-2"></i> info@nusagreen.com</p>
                </div>
                <div class="col-md-4">
                    <h6>Jam Operasional</h6>
                    <p class="text-white-50">Senin - Sabtu: 06:00 - 18:00<br>Minggu: 06:00 - 12:00</p>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="text-center text-white-50 mb-0">&copy; 2025 NusaGreenMarket. All rights reserved.</p>
        </div>
    </footer>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>