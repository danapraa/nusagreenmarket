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
            
            <!-- Rating Display -->
            <div class="mb-3">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        @php
                            $avgRating = $product->averageRating();
                            $fullStars = floor($avgRating);
                            $halfStar = ($avgRating - $fullStars) >= 0.5;
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $fullStars)
                                <i class="fas fa-star text-warning"></i>
                            @elseif($i == $fullStars + 1 && $halfStar)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-muted">{{ number_format($avgRating, 1) }} ({{ $product->totalReviews() }} ulasan)</span>
                </div>
            </div>
            
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
    
    <!-- Reviews Section -->
    <div class="mt-5">
        <h4 class="mb-4">Ulasan Pelanggan ({{ $product->totalReviews() }})</h4>
        
        @auth
            @if(auth()->user()->isCustomer())
                @php
                    // Cek apakah user pernah beli produk ini
                    $purchasedOrders = auth()->user()->orders()
                        ->where('status', 'delivered')
                        ->whereHas('items', function($q) use ($product) {
                            $q->where('product_id', $product->id);
                        })
                        ->get();
                    
                    // Cek order mana saja yang belum di-review
                    $canReview = $purchasedOrders->filter(function($order) use ($product) {
                        return !$product->reviews()
                            ->where('user_id', auth()->id())
                            ->where('order_id', $order->id)
                            ->exists();
                    });
                @endphp
                
                @if($canReview->count() > 0)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Tulis Ulasan Anda</h5>
                        <form action="{{ route('customer.reviews.store', $product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Pilih Pesanan</label>
                                <select name="order_id" class="form-select" required>
                                    <option value="">-- Pilih Pesanan --</option>
                                    @foreach($canReview as $order)
                                    <option value="{{ $order->id }}">
                                        {{ $order->order_number }} - {{ $order->created_at->format('d M Y') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <div class="rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                    <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Komentar (Opsional)</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Foto Produk (Opsional)</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewReviewImage(event)">
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                                <div id="reviewImagePreview" class="mt-2"></div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane"></i> Kirim Ulasan
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endif
        @endauth
        
        <!-- List Reviews -->
        <div class="reviews-list">
            @forelse($product->reviews()->with('user')->latest()->get() as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <strong>{{ $review->user->name }}</strong>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : ' text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            @if(auth()->check() && auth()->id() == $review->user_id)
                            <form action="{{ route('customer.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger" onclick="return confirm('Hapus review?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    
                    @if($review->comment)
                    <p class="mb-2">{{ $review->comment }}</p>
                    @endif
                    
                    @if($review->image)
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $review->image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $review->image) }}" 
                                 alt="Review image" 
                                 class="img-thumbnail" 
                                 style="max-width: 200px; max-height: 200px; cursor: pointer;"
                                 onclick="showImageModal(this.src)">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-4">
                <i class="fas fa-comment-slash fa-3x mb-3"></i>
                <p>Belum ada ulasan untuk produk ini</p>
            </div>
            @endforelse
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Review image">
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.rating-input input {
    display: none;
}
.rating-input label {
    cursor: pointer;
    font-size: 2rem;
    color: #ddd;
    transition: color 0.2s;
}
.rating-input input:checked ~ label,
.rating-input label:hover,
.rating-input label:hover ~ label {
    color: #ffc107;
}
</style>
@endpush

@push('scripts')
<script>
function previewReviewImage(event) {
    const preview = document.getElementById('reviewImagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                <p class="text-muted small mt-1">Preview foto yang akan diupload</p>
            `;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
}

function showImageModal(src) {
    document.getElementById('modalImage').src = src;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endpush
@endsection