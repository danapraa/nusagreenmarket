blade<!-- Review Form Section -->
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

<!-- Display Reviews with Images -->
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