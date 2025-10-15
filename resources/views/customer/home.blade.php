@extends('layouts.customer')

@section('title', 'NusaGreenMarket')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="min-height: 600px; color: white; position: relative; background-image: url('{{ asset('storage/images/sawah.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; backdrop-filter: blur(2px); background: linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.3));"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row align-items-center" style="min-height: 500px;">
            <div class="col-lg-7">
                <div style="animation: fadeInUp 1s ease;">
                    <h1 class="display-3 fw-bold mb-4" style="text-shadow: 3px 3px 6px rgba(0,0,0,0.5);">Tempat Belanja Sayur & Buah Segar</h1>
                    <p class="lead mb-4 fs-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Platform penjualan sayur-sayuran dan buah-buahan yang dipetik langsung dari kebun untuk menjamin kesegaran maksimal.</p>
                    <a href="#products" class="btn btn-light btn-lg px-5 py-3 shadow-lg" style="border-radius: 50px; font-weight: 600; transition: all 0.3s;">
                        <i class="fas fa-shopping-basket me-2"></i>Belanja Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5" style="background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #2d3748; font-size: 2.5rem;">Kategori Pilihan</h2>
            <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #4CAF50, #8BC34A); margin: 0 auto;"></div>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('customer.home') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 {{ !request('category') ? 'border-success' : '' }}" style="border-radius: 15px; transition: all 0.3s; {{ !request('category') ? 'box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3) !important;' : '' }}">
                        <div class="card-body text-center p-4">
                            <div class="mb-3" style="font-size: 3rem;">🌿</div>
                            <h5 class="fw-bold mb-2" style="color: #2d3748;">Semua Kategori</h5>
                            <p class="text-muted small mb-0">Lihat semua produk</p>
                        </div>
                    </div>
                </a>
            </div>
            
            @foreach($categories->take(5) as $category)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('customer.home', ['category' => $category->id]) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 {{ request('category') == $category->id ? 'border-success' : '' }}" style="border-radius: 15px; transition: all 0.3s; {{ request('category') == $category->id ? 'box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3) !important;' : '' }}">
                        <div class="card-body text-center p-4">
                            <div class="mb-3" style="font-size: 3rem;">{{ $category->icon }}</div>
                            <h5 class="fw-bold mb-2" style="color: #2d3748;">{{ $category->name }}</h5>
                            <p class="text-muted small mb-0">Pilihan terbaik</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        @if($categories->count() > 5)
        <div class="text-center mt-4">
            <a href="#" class="btn btn-outline-success btn-lg px-5" style="border-radius: 50px; border-width: 2px;">
                Lihat Semua Kategori <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Products Section -->
<section id="products" class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.05; background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%234CAF50\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-3" style="color: #2d3748; font-size: 2.5rem;">Produk Pilihan Kami</h2>
                <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #4CAF50, #8BC34A);"></div>
            </div>
            <div class="col-lg-6">
                <form method="GET" class="d-flex justify-content-lg-end mt-3 mt-lg-0">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <div class="input-group shadow-sm" style="max-width: 400px; border-radius: 50px; overflow: hidden;">
                        <input type="text" name="search" class="form-control border-0 ps-4" placeholder="Cari produk segar..." value="{{ request('search') }}" style="height: 50px;">
                        <button type="submit" class="btn btn-success border-0 px-4" style="background: linear-gradient(135deg, #4CAF50, #66BB6A);">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4">
            @forelse($products->take(6) as $product)
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; overflow: hidden; transition: all 0.3s;">
                    @if($product->badge)
                    <div class="position-absolute top-0 start-0 m-3" style="z-index: 10;">
                        <span class="badge" style="background: linear-gradient(135deg, #FF6B6B, #FF8E53); padding: 8px 15px; border-radius: 20px; font-weight: 600;">
                            {{ $product->badge }}
                        </span>
                    </div>
                    @endif
                    
                    @if($product->image)
                    <div style="position: relative; overflow: hidden; height: 250px;">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 100%; object-fit: cover; transition: transform 0.3s;">
                    </div>
                    @else
                    <div class="d-flex align-items-center justify-content-center" 
                         style="height: 250px; background: linear-gradient(135deg, #4CAF50, #8BC34A); color: white; font-size: 4rem;">
                        🥬
                    </div>
                    @endif

                    <div class="card-body p-4">
                        <!-- Rating Display -->
                        <div class="mb-2">
                            @php
                                $avgRating = $product->averageRating();
                                $totalReviews = $product->totalReviews();
                                $fullStars = floor($avgRating);
                                $halfStar = ($avgRating - $fullStars) >= 0.5;
                            @endphp
                            <div class="d-flex align-items-center">
                                <div class="me-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                            <i class="fas fa-star text-warning" style="font-size: 0.85rem;"></i>
                                        @elseif($i == $fullStars + 1 && $halfStar)
                                            <i class="fas fa-star-half-alt text-warning" style="font-size: 0.85rem;"></i>
                                        @else
                                            <i class="far fa-star text-warning" style="font-size: 0.85rem;"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">
                                    {{ number_format($avgRating, 1) }} 
                                    @if($totalReviews > 0)
                                        ({{ $totalReviews }})
                                    @else
                                        (0)
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <h5 class="card-title fw-bold mb-2" style="color: #2d3748;">{{ $product->name }}</h5>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-map-marker-alt text-success"></i> {{ $product->origin ?? 'Kebun Lokal' }} 
                            <span class="mx-1">•</span>
                            <i class="fas fa-clock text-success"></i> {{ $product->harvest_date ? $product->harvest_date->diffForHumans() : 'Hari ini' }}
                        </p>
                        <p class="card-text text-muted mb-3">{{ Str::limit($product->description, 60) }}</p>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="mb-0 fw-bold" style="color: #4CAF50;">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                <small class="text-muted fs-6">/{{ $product->unit }}</small>
                            </h4>
                            <span class="badge bg-light text-success border border-success">Stok: {{ $product->stock }}</span>
                        </div>
                        
                        @auth
                            @if(auth()->user()->isCustomer())
                            <form action="{{ route('customer.cart.add', $product) }}" method="POST" class="mb-2">
                                @csrf
                                <div class="d-flex gap-2 mb-3">
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" style="border-radius: 10px; max-width: 80px;">
                                    <button type="submit" class="btn btn-success flex-grow-1 shadow-sm" style="border-radius: 10px; font-weight: 600;">
                                        <i class="fas fa-cart-plus me-2"></i>Tambah
                                    </button>
                                </div>
                            </form>
                            @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-success w-100 mb-3 shadow-sm" style="border-radius: 10px; font-weight: 600;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login untuk Belanja
                        </a>
                        @endauth
                        
                        <a href="{{ route('customer.products.show', $product) }}" class="btn btn-outline-success w-100" style="border-radius: 10px; border-width: 2px; font-weight: 600;">
                            <i class="fas fa-info-circle me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm text-center p-5" style="border-radius: 20px;">
                    <i class="fas fa-search fa-3x mb-3 text-info"></i>
                    <h5 class="fw-bold">Produk tidak ditemukan</h5>
                    <p class="text-muted mb-0">Coba cari dengan kata kunci lain atau pilih kategori berbeda</p>
                </div>
            </div>
            @endforelse
        </div>

        @if($products->count() > 6)
        <div class="text-center mt-5">
            <a href="{{ route('customer.home') }}#all-products" class="btn btn-success btn-lg px-5 shadow-lg" style="border-radius: 50px; font-weight: 600; background: linear-gradient(135deg, #4CAF50, #66BB6A);">
                Lihat Semua Produk <i class="fas fa-arrow-down ms-2"></i>
            </a>
        </div>
        @endif
        
        <div class="mt-5">
            {{ $products->links() }}
        </div>
    </div>
</section>

<section class="py-5" style="background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%); color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255, 255, 255, 0.1);"></div>
    <div style="position: absolute; bottom: -100px; left: -100px; width: 300px; height: 300px; border-radius: 50%; background: rgba(255, 255, 255, 0.1);"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3" style="font-size: 2.5rem;">Cara Berbelanja</h2>
            <p class="fs-5 mb-4" style="opacity: 0.9;">Mudah dan cepat dalam 4 langkah sederhana</p>
            <div style="width: 60px; height: 4px; background: white; margin: 0 auto;"></div>
        </div>
        
        <div class="row g-4 align-items-center">
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-4">
                    <div class="rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center position-relative" 
                         style="width: 100px; height: 100px; background: white; color: #4CAF50; font-size: 2.5rem; font-weight: bold; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);">
                        1
                        <div class="position-absolute" style="top: -10px; right: -10px; width: 30px; height: 30px; background: #FF6B6B; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <i class="fas fa-mouse-pointer" style="font-size: 0.8rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Pilih Produk</h5>
                    <p style="opacity: 0.9;">Jelajahi katalog sayuran dan buah segar kami</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-4">
                    <div class="rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center position-relative" 
                         style="width: 100px; height: 100px; background: white; color: #4CAF50; font-size: 2.5rem; font-weight: bold; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);">
                        2
                        <div class="position-absolute" style="top: -10px; right: -10px; width: 30px; height: 30px; background: #FFB84D; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <i class="fas fa-cart-plus" style="font-size: 0.8rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Tambah ke Keranjang</h5>
                    <p style="opacity: 0.9;">Pilih jumlah yang diinginkan dan masukkan ke keranjang</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-4">
                    <div class="rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center position-relative" 
                         style="width: 100px; height: 100px; background: white; color: #4CAF50; font-size: 2.5rem; font-weight: bold; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);">
                        3
                        <div class="position-absolute" style="top: -10px; right: -10px; width: 30px; height: 30px; background: #A78BFA; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <i class="fas fa-credit-card" style="font-size: 0.8rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Checkout</h5>
                    <p style="opacity: 0.9;">Lakukan pembayaran dengan metode yang tersedia</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="text-center p-4">
                    <div class="rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center position-relative" 
                         style="width: 100px; height: 100px; background: white; color: #4CAF50; font-size: 2.5rem; font-weight: bold; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);">
                        4
                        <div class="position-absolute" style="top: -10px; right: -10px; width: 30px; height: 30px; background: #34D399; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <i class="fas fa-box" style="font-size: 0.8rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Terima Pesanan</h5>
                    <p style="opacity: 0.9;">Produk segar diantar langsung ke rumah Anda</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); position: relative;">
    <div style="position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: radial-gradient(circle, rgba(76, 175, 80, 0.1) 0%, transparent 70%);"></div>
    <div style="position: absolute; bottom: 0; left: 0; width: 400px; height: 400px; background: radial-gradient(circle, rgba(139, 195, 74, 0.1) 0%, transparent 70%);"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #2d3748; font-size: 2.5rem;">Mengapa Memilih Kami?</h2>
            <p class="text-muted fs-5 mb-4">Komitmen kami untuk memberikan produk terbaik dan pelayanan berkualitas</p>
            <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #4CAF50, #8BC34A); margin: 0 auto;"></div>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: all 0.3s;">
                    <div class="card-body text-center p-4">
                        <div class="mb-4 mx-auto d-flex align-items-center justify-content-center" 
                             style="width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #4CAF50, #66BB6A); color: white; font-size: 2.5rem; box-shadow: 0 10px 25px rgba(76, 175, 80, 0.3);">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #2d3748;">100% Segar</h5>
                        <p class="text-muted">Dipetik langsung dari kebun untuk menjamin kesegaran maksimal</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: all 0.3s;">
                    <div class="card-body text-center p-4">
                        <div class="mb-4 mx-auto d-flex align-items-center justify-content-center" 
                             style="width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #66BB6A, #81C784); color: white; font-size: 2.5rem; box-shadow: 0 10px 25px rgba(102, 187, 106, 0.3);">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #2d3748;">Pengiriman Cepat</h5>
                        <p class="text-muted">Diantar langsung ke rumah Anda dengan cepat dan aman</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; transition: all 0.3s;">
                    <div class="card-body text-center p-4">
                        <div class="mb-4 mx-auto d-flex align-items-center justify-content-center" 
                             style="width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #81C784, #A5D6A7); color: white; font-size: 2.5rem; box-shadow: 0 10px 25px rgba(129, 199, 132, 0.3);">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #2d3748;">Harga Terjangkau</h5>
                        <p class="text-muted">Langsung dari petani, tanpa perantara, harga lebih terjangkau</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.card img {
    transition: transform 0.3s ease;
}

.card:hover img {
    transform: scale(1.1);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}
</style>
@endsection