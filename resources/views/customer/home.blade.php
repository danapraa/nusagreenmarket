@extends('layouts.customer')

@section('title', 'NusaGreenMarket - Sayuran Segar')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="min-height: 580px; color: white; position: relative; background-image: url('{{ asset('storage/images/sawah.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; backdrop-filter: blur(1.5px); background: rgba(0, 0, 0, 0.35);"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row align-items-center" style="min-height: 480px;">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.6); line-height: 1.2;">Tempat Belanja Sayur & Buah Segar</h1>
                <p class="lead mb-4" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.6); font-size: 1.15rem;">Platform penjualan sayur-sayuran dan buah-buahan yang dipetik langsung dari kebun untuk menjamin kesegaran maksimal.</p>
                <a href="#products" class="btn btn-light btn-lg px-4 py-2" style="border-radius: 8px; font-weight: 500;">
                    <i class="fas fa-shopping-basket me-2"></i>Belanja Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="mb-4">
            <h2 class="fw-bold mb-2" style="color: #1a202c;">Kategori Pilihan</h2>
            <p class="text-muted">Pilih kategori sesuai kebutuhan Anda</p>
        </div>
        
        <div class="row g-3">
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('customer.home') }}" class="text-decoration-none">
                    <div class="card h-100 {{ !request('category') ? 'border-success shadow-sm' : 'border-0' }}" style="border-radius: 12px; border-width: 2px;">
                        <div class="card-body text-center p-3">
                            <div class="mb-2" style="font-size: 2.5rem;">🌿</div>
                            <h6 class="fw-bold mb-1" style="color: #1a202c;">Semua Kategori</h6>
                            <small class="text-muted">Lihat semua produk</small>
                        </div>
                    </div>
                </a>
            </div>
            
            @foreach($categories->take(5) as $category)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('customer.home', ['category' => $category->id]) }}" class="text-decoration-none">
                    <div class="card h-100 {{ request('category') == $category->id ? 'border-success shadow-sm' : 'border-0' }}" style="border-radius: 12px; border-width: 2px;">
                        <div class="card-body text-center p-3">
                            <div class="mb-2" style="font-size: 2.5rem;">{{ $category->icon }}</div>
                            <h6 class="fw-bold mb-1" style="color: #1a202c;">{{ $category->name }}</h6>
                            <small class="text-muted">Pilihan terbaik</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        @if($categories->count() > 5)
        <div class="text-center mt-3">
            <a href="#" class="btn btn-outline-success px-4">
                Lihat Semua Kategori <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Products Section -->
<section id="products" class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-2" style="color: #1a202c;">Produk Pilihan Kami</h2>
                <p class="text-muted mb-lg-0">Sayur dan buah segar setiap hari</p>
            </div>
            <div class="col-lg-6">
                <form method="GET" class="d-flex justify-content-lg-end mt-3 mt-lg-0">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <div class="input-group" style="max-width: 380px;">
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-success px-3">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3">
            @forelse($products->take(6) as $product)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 10px;">
                    @if($product->badge)
                    <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                        <span class="badge bg-danger px-2 py-1">
                            {{ $product->badge }}
                        </span>
                    </div>
                    @endif
                    
                    @if($product->image)
                    <div style="overflow: hidden; height: 220px; border-radius: 10px 10px 0 0;">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 100%; width: 100%; object-fit: cover;">
                    </div>
                    @else
                    <div class="d-flex align-items-center justify-content-center" 
                         style="height: 220px; background: linear-gradient(to bottom right, #48bb78, #68d391); color: white; font-size: 3.5rem; border-radius: 10px 10px 0 0;">
                        🥬
                    </div>
                    @endif

                    <div class="card-body p-3">
                        <h5 class="card-title fw-bold mb-2" style="color: #1a202c; font-size: 1.1rem;">{{ $product->name }}</h5>
                        <p class="text-muted small mb-2" style="font-size: 0.85rem;">
                            <i class="fas fa-map-marker-alt text-success"></i> {{ $product->origin ?? 'Kebun Lokal' }} 
                            <span class="mx-1">•</span>
                            <i class="fas fa-clock text-success"></i> {{ $product->harvest_date ? $product->harvest_date->diffForHumans() : 'Hari ini' }}
                        </p>
                        <p class="card-text text-muted small mb-3">{{ Str::limit($product->description, 60) }}</p>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="mb-0 fw-bold text-success">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </h5>
                                <small class="text-muted">per {{ $product->unit }}</small>
                            </div>
                            <span class="badge bg-light text-success border border-success" style="font-size: 0.75rem;">Stok: {{ $product->stock }}</span>
                        </div>
                        
                        @auth
                            @if(auth()->user()->isCustomer())
                            <form action="{{ route('customer.cart.add', $product) }}" method="POST" class="mb-2">
                                @csrf
                                <div class="d-flex gap-2 mb-2">
                                    <input type="number" name="quantity" class="form-control form-control-sm" value="1" min="1" max="{{ $product->stock }}" style="max-width: 70px;">
                                    <button type="submit" class="btn btn-success btn-sm flex-grow-1">
                                        <i class="fas fa-cart-plus me-1"></i>Tambah
                                    </button>
                                </div>
                            </form>
                            @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-success btn-sm w-100 mb-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Login untuk Belanja
                        </a>
                        @endauth
                        
                        <a href="{{ route('customer.products.show', $product) }}" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-info-circle me-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-search fa-2x mb-2"></i>
                    <h6 class="fw-bold mb-1">Produk tidak ditemukan</h6>
                    <small>Coba cari dengan kata kunci lain atau pilih kategori berbeda</small>
                </div>
            </div>
            @endforelse
        </div>

        @if($products->count() > 6)
        <div class="text-center mt-4">
            <a href="{{ route('customer.home') }}#all-products" class="btn btn-success px-4">
                Lihat Semua Produk <i class="fas fa-arrow-down ms-1"></i>
            </a>
        </div>
        @endif
        
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-success text-white">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2">Cara Berbelanja</h2>
            <p class="mb-0" style="opacity: 0.95;">Mudah dan cepat dalam 4 langkah sederhana</p>
        </div>
        
        <div class="row g-4 mt-2">
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center bg-white text-success fw-bold" 
                         style="width: 70px; height: 70px; font-size: 1.8rem;">
                        1
                    </div>
                    <h6 class="fw-bold mb-2">Pilih Produk</h6>
                    <p class="small mb-0" style="opacity: 0.9;">Jelajahi katalog sayuran dan buah segar kami</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center bg-white text-success fw-bold" 
                         style="width: 70px; height: 70px; font-size: 1.8rem;">
                        2
                    </div>
                    <h6 class="fw-bold mb-2">Tambah ke Keranjang</h6>
                    <p class="small mb-0" style="opacity: 0.9;">Pilih jumlah yang diinginkan dan masukkan ke keranjang</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center bg-white text-success fw-bold" 
                         style="width: 70px; height: 70px; font-size: 1.8rem;">
                        3
                    </div>
                    <h6 class="fw-bold mb-2">Checkout</h6>
                    <p class="small mb-0" style="opacity: 0.9;">Lakukan pembayaran dengan metode yang tersedia</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center bg-white text-success fw-bold" 
                         style="width: 70px; height: 70px; font-size: 1.8rem;">
                        4
                    </div>
                    <h6 class="fw-bold mb-2">Terima Pesanan</h6>
                    <p class="small mb-0" style="opacity: 0.9;">Produk segar diantar langsung ke rumah Anda</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2" style="color: #1a202c;">Mengapa Memilih Kami?</h2>
            <p class="text-muted">Komitmen kami untuk memberikan produk terbaik dan pelayanan berkualitas</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle bg-success text-white" 
                             style="width: 70px; height: 70px; font-size: 2rem;">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #1a202c;">100% Segar</h5>
                        <p class="text-muted mb-0">Dipetik langsung dari kebun untuk menjamin kesegaran maksimal</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle bg-success text-white" 
                             style="width: 70px; height: 70px; font-size: 2rem;">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #1a202c;">Pengiriman Cepat</h5>
                        <p class="text-muted mb-0">Diantar langsung ke rumah Anda dengan cepat dan aman</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle bg-success text-white" 
                             style="width: 70px; height: 70px; font-size: 2rem;">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color: #1a202c;">Harga Terjangkau</h5>
                        <p class="text-muted mb-0">Langsung dari petani, tanpa perantara, harga lebih terjangkau</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

.btn {
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>
@endsection