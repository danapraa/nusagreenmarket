@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Keranjang Belanja</h2>

    @if($cart && $cart->items->count() > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @foreach($cart->items as $item)
                    <div class="row align-items-center border-bottom py-3">
                        <div class="col-md-2">
                            @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid rounded" alt="{{ $item->product->name }}">
                            @else
                            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #4CAF50, #8BC34A); border-radius: 5px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                                🥬
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <p class="text-muted small mb-0">Rp{{ number_format($item->price, 0, ',', '.') }}/{{ $item->product->unit }}</p>
                            <small class="text-muted">Stok: {{ $item->product->stock }} {{ $item->product->unit }}</small>
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('customer.cart.update', $item) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="input-group input-group-sm">
                                    <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" 
                                           min="1" max="{{ $item->product->stock }}" onchange="this.form.submit()">
                                    <span class="input-group-text">{{ $item->product->unit }}</span>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <h6 class="text-success mb-0">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</h6>
                        </div>
                        <div class="col-md-1">
                            <form action="{{ route('customer.cart.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-3">
                <form action="{{ route('customer.cart.clear') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Kosongkan keranjang?')">
                        <i class="fas fa-trash-alt"></i> Kosongkan Keranjang
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Ringkasan Belanja</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal ({{ $cart->itemCount }} item)</span>
                        <strong>Rp{{ number_format($cart->total, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Ongkos Kirim</span>
                        <span class="text-muted">Dihitung saat checkout</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <h4 class="text-success mb-0">Rp{{ number_format($cart->total, 0, ',', '.') }}</h4>
                    </div>
                    <a href="{{ route('customer.checkout') }}" class="btn btn-success btn-lg w-100">
                        Lanjut ke Checkout
                    </a>
                    <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary w-100 mt-2">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
        <h4>Keranjang Anda Kosong</h4>
        <p class="text-muted">Belum ada produk di keranjang. Yuk belanja sayuran segar!</p>
        <a href="{{ route('customer.home') }}" class="btn btn-success mt-3">Mulai Belanja</a>
    </div>
    @endif
</div>
@endsection