@extends('layouts.customer')

@section('title', 'Semua Produk - NusaGreenMarket')

@section('content')
<!-- Page Header -->
<div class="bg-light py-4">
    <div class="container">
        <h2 class="mb-0">Semua Produk Sayuran</h2>
        <p class="text-muted mb-0">Temukan sayuran segar pilihan Anda</p>
    </div>
</div>

<section class="py-4">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-filter"></i> Filter Produk</h6>
                    </div>
                    <div class="card-body">
                        <!-- Search -->
                        <form method="GET" action="{{ route('customer.products') }}">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Cari Produk</label>
                                <input type="text" name="search" class="form-control" placeholder="Nama produk..." value="{{ request('search') }}">
                            </div>

                            <!-- Categories -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <div class="list-group">
                                    <a href="{{ route('customer.products') }}" 
                                       class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                        Semua Kategori
                                        <span class="badge bg-secondary float-end">{{ $categories->sum('products_count') }}</span>
                                    </a>
                                    @foreach($categories as $cat)
                                    <a href="{{ route('customer.products', ['category' => $cat->id]) }}" 
                                       class="list-group-item list-group-item-action {{ request('category') == $cat->id ? 'active' : '' }}">
                                        {{ $cat->icon }} {{ $cat->name }}
                                        <span class="badge bg-secondary float-end">{{ $cat->products_count }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sorting -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Urutkan</label>
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="">Terbaru</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                                </select>
                            </div>

                            <input type="hidden" name="category" value="{{ request('category') }}">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-search"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="fas fa-redo"></i> Reset Filter
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Result Info -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>{{ $products->total() }} Produk</strong> ditemukan
                        @if(request('search'))
                        untuk "<strong>{{ request('search') }}</strong>"
                        @endif
                    </div>
                </div>

                <!-- Products -->
                <div class="row g-4">
                    @forelse($products as $product)
                    <div class="col-lg-4 col-md-6">
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
                                <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt"></i> {{ $product->origin ?? 'Kebun Lokal' }} • 
                                    Stok: {{ $product->stock }} {{ $product->unit }}
                                </p>
                                <p class="card-text">{{ Str::limit($product->description, 60) }}</p>
                                <h4 class="text-success mb-3">Rp{{ number_format($product->price, 0, ',', '.') }}/{{ $product->unit }}</h4>
                                
                                @auth
                                    @if(auth()->user()->isCustomer())
                                    <form action="{{ route('customer.cart.add', $product) }}" method="POST">
                                        @csrf
                                        <div class="input-group mb-2">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decrementQty(this)">-</button>
                                            <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}" style="max-width: 60px;">
                                            <button type="button" class="btn btn-outline-secondary" onclick="incrementQty(this)">+</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                    @endif
                                @else
                                <a href="{{ route('login') }}" class="btn btn-success w-100 mb-2">Login untuk Belanja</a>
                                @endauth
                                
                                <a href="{{ route('customer.products.show', $product) }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Produk tidak ditemukan
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function incrementQty(btn) {
    const input = btn.parentElement.querySelector('input[type="number"]');
    const max = parseInt(input.getAttribute('max'));
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
    }
}

function decrementQty(btn) {
    const input = btn.parentElement.querySelector('input[type="number"]');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
@endpush
@endsection