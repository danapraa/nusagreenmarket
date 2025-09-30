@extends('layouts.customer')

@section('title', 'NusaGreenMarket - Sayuran Segar')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="min-height: 500px; color: white; position: relative; background-image: url('{{ asset('storage/images/sawah.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.3));"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row align-items-center" style="min-height: 400px;">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Tempat belanja Sayur - Sayuran dan Buah - buahan</h1>
                <p class="lead mb-4">NusaGreenMarket adalah platform penjualan sayur-sayuran dan buah-buahan yang dipetik langsung dari kebun untuk menjamin kesegaran maksimal.</p>
                <a href="#products" class="btn btn-light btn-lg px-5">Belanja Sekarang</a>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="text-center mb-4">Kategori Sayuran</h3>
        <div class="row g-3">
            <div class="col">
                <a href="{{ route('customer.home') }}" class="btn btn-outline-success w-100 {{ !request('category') ? 'active' : '' }}">
                    Semua
                </a>
            </div>
            @foreach($categories as $category)
            <div class="col">
                <a href="{{ route('customer.home', ['category' => $category->id]) }}" 
                   class="btn btn-outline-success w-100 {{ request('category') == $category->id ? 'active' : '' }}">
                    {{ $category->icon }} {{ $category->name }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Products -->
<section id="products" class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Produk Tersedia</h3>
            <form method="GET" class="d-flex">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari produk..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card card h-100">
                    @if($product->badge)
                    <div class="product-badge">{{ $product->badge }}</div>
                    @endif
                    
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top d-flex align-items-center justify-content-center" 
                         style="height: 200px; background: linear-gradient(135deg, #4CAF50, #8BC34A); color: white; font-size: 3rem;">
                        🥬
                    </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-map-marker-alt"></i> {{ $product->origin ?? 'Kebun Lokal' }} • 
                            Panen: {{ $product->harvest_date ? $product->harvest_date->diffForHumans() : 'Hari ini' }}
                        </p>
                        <p class="card-text">{{ Str::limit($product->description, 60) }}</p>
                        <h4 class="text-success mb-3">Rp{{ number_format($product->price, 0, ',', '.') }}/{{ $product->unit }}</h4>
                        
                        @auth
                            @if(auth()->user()->isCustomer())
                            <form action="{{ route('customer.cart.add', $product) }}" method="POST" class="mb-2">
                                @csrf
                                <div class="input-group mb-2">
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-cart-plus"></i> Keranjang
                                    </button>
                                </div>
                            </form>
                            @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-success w-100 mb-2">Login untuk Belanja</a>
                        @endauth
                        
                        <a href="{{ route('customer.products.show', $product) }}" class="btn btn-outline-success w-100">Detail</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Produk tidak ditemukan
                </div>
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection