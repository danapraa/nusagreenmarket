@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Checkout</h2>

    <form action="{{ route('customer.checkout.process') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Informasi Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap*</label>
                            <textarea name="shipping_address" rows="3" class="form-control @error('shipping_address') is-invalid @enderror" 
                                      required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon/WhatsApp*</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', auth()->user()->phone) }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Pengiriman*</label>
                                <input type="date" name="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                       value="{{ old('delivery_date', now()->addDay()->format('Y-m-d')) }}" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                                @error('delivery_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran*</label>
                            <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="cod">Bayar di Tempat (COD)</option>
                                <option value="ewallet">E-Wallet</option>
                            </select>
                            @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Khusus (Opsional)</label>
                            <textarea name="special_requests" rows="3" class="form-control" 
                                      placeholder="Contoh: Pilih yang paling segar, potong kecil-kecil, dll.">{{ old('special_requests') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cart->items as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item->product->name }} ({{ $item->quantity }} {{ $item->product->unit }})</span>
                            <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong>Rp{{ number_format($cart->total, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span>Ongkos Kirim</span>
                            <span class="text-{{ $cart->total >= 50000 ? 'success' : 'muted' }}">
                                @if($cart->total >= 50000)
                                GRATIS
                                @else
                                Rp10.000
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <h4 class="text-success mb-0">
                                Rp{{ number_format($cart->total >= 50000 ? $cart->total : $cart->total + 10000, 0, ',', '.') }}
                            </h4>
                        </div>
                        
                        @if($cart->total < 50000)
                        <div class="alert alert-info small">
                            Belanja minimal Rp50.000 untuk gratis ongkir!
                        </div>
                        @endif
                        
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check-circle"></i> Konfirmasi Pesanan
                        </button>
                        <a href="{{ route('customer.cart') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection