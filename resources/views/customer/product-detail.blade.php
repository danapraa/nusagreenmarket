@extends('layouts.customer')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-5">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
            @else
            <div class="d-flex align-items-center justify-content-center rounded shadow" 
                 style="height: 400px; background: linear-gradient(135deg, #4CAF50, #8BC34A); color: white; font-size: 5rem;">
                🥬
            </div>
            @endif
        </div>
        
        <div class="col-lg-7">
            @if($product->badge)
            <span class="badge bg-warning text-dark mb-2">{{ $product->badge }}</span>
            @endif
            
            <h2 class="mb-3">{{ $product->name }}</h2>
            
            <div class="mb-3">
                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                <span class="text-muted ms-2">
                    <i class="fas fa-map-marker-alt"></i> {{ $product->origin ?? 'Kebun Lokal' }}
                </span>
                <span class="text-muted ms-2">
                    <i class="fas fa-calendar"></i> Panen: {{ $product->harvest_date ? $product->harvest_date->diffForHumans() : 'Hari ini' }}
                </span>
            </div>
            
            <h3 class="text-success mb-3">
                Rp{{ number_format($product->price, 0, ',', '.') }}/{{ $product->unit }}
            </h3>
            
            <div class="mb-4">
                <h5>Deskripsi</h5>
                <p class="text-muted">{{ $product->description }}</p>
            </div>
            
            <div class="mb-4">
                <h6>Stok: 
                    <span class="badge bg-{{ $product->stock > 10 ? 'success' : 'warning' }}">
                        {{ $product->stock }} {{ $product->unit }}
                    </span>
                </h6>
            </div>
            
            @auth
                @if(auth()->user()->isCustomer())
                <form action="{{ route('customer.cart.add', $product) }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Jumlah</label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" value="1" 
                                       min="1" max="{{ $product->stock }}" required>
                                <span class="input-group-text">{{ $product->unit }}</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                    </button>
                    <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </form>
                @endif
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Silakan <a href="{{ route('login') }}">login</a> untuk membeli produk ini
            </div>
            @endauth
        </div>
    </div>
    
    <!-- Related Products -->
    @if($related_products->count() > 0)
    <div class="mt-5">
        <h4 class="mb-4">Produk Terkait</h4>
        <div class="row g-4">
            @foreach($related_products as $item)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card card h-100">
                    @if($item->badge)
                    <div class="product-badge">{{ $item->badge }}</div>
                    @endif
                    
                    @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top d-flex align-items-center justify-content-center" 
                         style="height: 200px; background: linear-gradient(135deg, #4CAF50, #8BC34A); color: white; font-size: 3rem;">
                        🥬
                    </div>
                    @endif
                    
                    <div class="card-body">
                        <h6 class="card-title">{{ $item->name }}</h6>
                        <h5 class="text-success">Rp{{ number_format($item->price, 0, ',', '.') }}/{{ $item->unit }}</h5>
                        <a href="{{ route('customer.products.show', $item) }}" class="btn btn-outline-success btn-sm w-100">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection