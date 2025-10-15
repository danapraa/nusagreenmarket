@extends('layouts.customer')

@section('title', 'Produk Favorit Saya')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="fas fa-heart text-danger me-3" style="font-size: 1.8rem;"></i>
        <div>
            <h2 class="mb-1">Produk Favorit Saya</h2>
            <p class="text-muted mb-0">Kelola daftar produk yang Anda sukai</p>
        </div>
    </div>

    @if($favorites->count() > 0)
    <div class="row g-3">
        @foreach($favorites as $favorite)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 position-relative" style="transition: transform 0.2s; overflow: hidden;">
                <form action="{{ route('customer.favorites.remove', $favorite) }}" method="POST" class="position-absolute" style="top: 8px; right: 8px; z-index: 10;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light btn-sm shadow-sm" style="width: 32px; height: 32px; border-radius: 50%; padding: 0; border: 1px solid rgba(0,0,0,0.08);" title="Hapus dari favorit">
                        <i class="fas fa-heart text-danger" style="font-size: 0.85rem;"></i>
                    </button>
                </form>

                @if($favorite->product->badge)
                <div class="position-absolute" style="top: 8px; left: 8px; z-index: 9;">
                    <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.7rem; padding: 4px 10px;">
                        {{ $favorite->product->badge }}
                    </span>
                </div>
                @endif
                
                @if($favorite->product->image)
                <img src="{{ asset('storage/' . $favorite->product->image) }}" class="card-img-top" alt="{{ $favorite->product->name }}" style="height: 180px; object-fit: cover;">
                @else
                <div class="card-img-top d-flex align-items-center justify-content-center" 
                     style="height: 180px; background: linear-gradient(135deg, #4CAF50, #8BC34A); color: white; font-size: 2.5rem;">
                    🥬
                </div>
                @endif

                <div class="card-body p-3">
                    <div class="mb-2">
                        <span class="badge" style="background-color: #f8f9fa; color: #495057; font-weight: 500; font-size: 0.7rem; padding: 3px 8px;">
                            {{ $favorite->product->category->name }}
                        </span>
                    </div>
                    
                    <h6 class="mb-2" style="font-weight: 600; color: #2d3748; line-height: 1.4;">
                        {{ $favorite->product->name }}
                    </h6>
                    
                    <div class="d-flex align-items-center text-muted mb-2" style="font-size: 0.75rem;">
                        <i class="fas fa-map-marker-alt me-1" style="font-size: 0.7rem;"></i>
                        <span>{{ $favorite->product->origin ?? 'Kebun Lokal' }}</span>
                        <span class="mx-2">•</span>
                        <span>Stok: {{ $favorite->product->stock }} {{ $favorite->product->unit }}</span>
                    </div>
                    
                    <p class="mb-3" style="font-size: 0.8rem; color: #718096; line-height: 1.5;">
                        {{ Str::limit($favorite->product->description, 55) }}
                    </p>
                    
                    <div class="mb-3">
                        <h5 class="mb-0" style="color: #38a169; font-weight: 700;">
                            Rp{{ number_format($favorite->product->price, 0, ',', '.') }}
                            <small style="font-size: 0.7rem; color: #718096; font-weight: 400;">/{{ $favorite->product->unit }}</small>
                        </h5>
                    </div>
                    
                    @if($favorite->product->stock > 0)
                    <form action="{{ route('customer.favorites.move-to-cart', $favorite) }}" method="POST" class="mb-2">
                        @csrf
                        <div class="d-flex gap-2 mb-2">
                            <div class="input-group" style="flex: 1;">
                                <button type="button" class="btn btn-outline-secondary" style="border-color: #e2e8f0; color: #4a5568; padding: 0.375rem 0.75rem;" onclick="decrementQty(this)">
                                    <i class="fas fa-minus" style="font-size: 0.7rem;"></i>
                                </button>
                                <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="{{ $favorite->product->stock }}" style="border-color: #e2e8f0; max-width: 50px; font-weight: 600;" required>
                                <button type="button" class="btn btn-outline-secondary" style="border-color: #e2e8f0; color: #4a5568; padding: 0.375rem 0.75rem;" onclick="incrementQty(this)">
                                    <i class="fas fa-plus" style="font-size: 0.7rem;"></i>
                                </button>
                            </div>
                            <button type="submit" class="btn btn-success" style="padding: 0.375rem 1rem; background: linear-gradient(135deg, #38a169 0%, #2f855a 100%); border: none;" title="Pindah ke keranjang">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="alert mb-2 py-2 text-center" style="background-color: #fef5e7; color: #d68910; border: 1px solid #f9e79f; font-size: 0.8rem; border-radius: 6px;">
                        Stok Habis
                    </div>
                    @endif
                    
                    <a href="{{ route('customer.products.show', $favorite->product) }}" class="btn btn-outline-success w-100" style="padding: 0.5rem; font-size: 0.85rem; border-color: #38a169; color: #38a169;">
                        <i class="fas fa-eye me-1"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5" style="margin-top: 60px; margin-bottom: 60px;">
        <div class="mb-4">
            <i class="fas fa-heart" style="font-size: 4rem; color: #cbd5e0;"></i>
        </div>
        <h4 class="mb-2" style="color: #2d3748; font-weight: 600;">Belum Ada Produk Favorit</h4>
        <p class="mb-4" style="color: #718096; max-width: 400px; margin-left: auto; margin-right: auto;">
            Simpan produk favorit Anda untuk memudahkan belanja nanti!
        </p>
        <a href="{{ route('customer.products') }}" class="btn btn-success" style="padding: 0.75rem 2rem; background: linear-gradient(135deg, #38a169 0%, #2f855a 100%); border: none; border-radius: 8px;">
            <i class="fas fa-shopping-bag me-2"></i> Lihat Produk
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

// Hover effect for cards
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px)';
    });
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>
@endpush
@endsection