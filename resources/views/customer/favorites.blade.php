@extends('layouts.customer')

@section('title', 'Produk Favorit Saya')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-heart text-danger"></i> Produk Favorit Saya</h2>

    @if($favorites->count() > 0)
    <div class="row g-4">
        @foreach($favorites as $favorite)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="product-card card h-100 position-relative">
                <!-- Remove Button -->
                <form action="{{ route('customer.favorites.remove', $favorite) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Hapus dari favorit">
                        <i class="fas fa-times"></i>
                    </button>
                </form>

                @if($favorite->product->badge)
                <div class="product-badge">{{ $favorite->product->badge }}</div>
                @endif
                
                @if($favorite->product->image)
                <img src="{{ asset('storage/' . $favorite->product->image) }}" class="card-img-top" alt="{{ $favorite->product->name }}" style="height: 200px; object-fit: cover;">
                @else
                <div class="card-img-top d-flex align-items-center justify-content-center" 
                     style="height: 200px; background: linear-gradient(135deg, #4CAF50, #8BC34A); color: white; font-size: 3rem;">
                    🥬
                </div>
                @endif

                <div class="card-body">
                    <span class="badge bg-secondary mb-2">{{ $favorite->product->category->name }}</span>
                    <h5 class="card-title">{{ $favorite->product->name }}</h5>
                    <p class="text-muted small mb-2">
                        <i class="fas fa-map-marker-alt"></i> {{ $favorite->product->origin ?? 'Kebun Lokal' }} • 
                        Stok: {{ $favorite->product->stock }} {{ $favorite->product->unit }}
                    </p>
                    <p class="card-text">{{ Str::limit($favorite->product->description, 60) }}</p>
                    <h4 class="text-success mb-3">Rp{{ number_format($favorite->product->price, 0, ',', '.') }}/{{ $favorite->product->unit }}</h4>
                    
                    @if($favorite->product->stock > 0)
                    <form action="{{ route('customer.favorites.move-to-cart', $favorite) }}" method="POST" class="mb-2">
                        @csrf
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="decrementQty(this)">-</button>
                            <input type="number" name="quantity" class="form-control form-control-sm text-center" value="1" min="1" max="{{ $favorite->product->stock }}" style="max-width: 60px;" required>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="incrementQty(this)">+</button>
                            <button type="submit" class="btn btn-success btn-sm" title="Pindah ke keranjang">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-warning py-2 mb-2 text-center">
                        <small>Stok Habis</small>
                    </div>
                    @endif
                    
                    <a href="{{ route('customer.products.show', $favorite->product) }}" class="btn btn-outline-success w-100 btn-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-heart fa-4x text-muted mb-3"></i>
        <h4>Belum Ada Produk Favorit</h4>
        <p class="text-muted">Simpan produk favorit Anda untuk memudahkan belanja nanti!</p>
        <a href="{{ route('customer.products') }}" class="btn btn-success mt-3">
            <i class="fas fa-shopping-bag"></i> Lihat Produk
        </a>
    </div>
    @endif
</div>

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