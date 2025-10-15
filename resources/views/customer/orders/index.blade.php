@extends('layouts.customer')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Pesanan Saya</h2>

    @forelse($orders as $order)
    <div class="card mb-3">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <small class="text-muted">No. Pesanan</small><br>
                    <strong>{{ $order->order_number }}</strong>
                </div>
                <div class="col-md-3">
                    <small class="text-muted">Tanggal</small><br>
                    {{ $order->created_at->format('d M Y') }}
                </div>
                <div class="col-md-3">
                    <small class="text-muted">Total</small><br>
                    <strong class="text-success">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
                </div>
                <div class="col-md-3 text-end">
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'processing' => 'info',
                            'shipped' => 'primary',
                            'delivered' => 'success',
                            'cancelled' => 'danger'
                        ];
                        $statusLabels = [
                            'pending' => 'Menunggu',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'delivered' => 'Selesai',
                            'cancelled' => 'Dibatalkan'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$order->status] }} px-3 py-2">
                        {{ $statusLabels[$order->status] }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h6>Produk yang dibeli:</h6>
            @foreach($order->items as $item)
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <div>
                        {{ $item->product_name }} ({{ $item->quantity }} {{ $item->product->unit ?? 'kg' }})
                        <br>
                        <small class="text-muted">Rp{{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</small>
                    </div>
                </div>
                
                @if($order->status === 'delivered')
                    @php
                        $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                            ->where('product_id', $item->product_id)
                            ->where('order_id', $order->id)
                            ->exists();
                    @endphp
                    @if($hasReviewed)
                        <span class="badge bg-success">
                            <i class="fas fa-star"></i> Sudah diulas
                        </span>
                    @else
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $item->id }}">
                            <i class="fas fa-star"></i> Nilai Produk Kami
                        </button>
                    @endif
                @endif
            </div>
            @endforeach
            
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Total {{ $order->items->count() }} produk</small>
                </div>
                <div>
                    <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                    @if(in_array($order->status, ['pending', 'processing']))
                    <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                onclick="return confirm('Yakin ingin membatalkan pesanan?')">
                            <i class="fas fa-times"></i> Batalkan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Review untuk setiap item -->
    @if($order->status === 'delivered')
        @foreach($order->items as $item)
            @php
                $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                    ->where('product_id', $item->product_id)
                    ->where('order_id', $order->id)
                    ->exists();
            @endphp
            @if(!$hasReviewed)
            <div class="modal fade" id="reviewModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tulis Ulasan - {{ $item->product_name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('customer.reviews.store', $item->product) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="rating-input" id="rating{{ $item->id }}">
                                        @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $item->id }}_{{ $i }}" required>
                                        <label for="star{{ $item->id }}_{{ $i }}"><i class="fas fa-star"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Komentar (Opsional)</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Upload Foto Produk (Opsional)</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewModalImage{{ $item->id }}(event)">
                                    <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                                    <div id="imagePreviewModal{{ $item->id }}" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-paper-plane"></i> Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    @endif
    @empty
    <div class="text-center py-5">
        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
        <h4>Belum Ada Pesanan</h4>
        <p class="text-muted">Anda belum melakukan pemesanan</p>
        <a href="{{ route('customer.home') }}" class="btn btn-success mt-3">Mulai Belanja</a>
    </div>
    @endforelse

    <div class="mt-4">
        {{ $orders->links() }}
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
// Generate preview functions for each item
@foreach($orders as $order)
    @if($order->status === 'delivered')
        @foreach($order->items as $item)
            @php
                $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                    ->where('product_id', $item->product_id)
                    ->where('order_id', $order->id)
                    ->exists();
            @endphp
            @if(!$hasReviewed)
function previewModalImage{{ $item->id }}(event) {
    const preview = document.getElementById('imagePreviewModal{{ $item->id }}');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">`;
        }
        reader.readAsDataURL(file);
    }
}
            @endif
        @endforeach
    @endif
@endforeach
</script>
@endpush
@endsection