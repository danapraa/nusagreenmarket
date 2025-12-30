@extends('layouts.customer')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Pesanan</h2>
        <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Order Tracking Timeline -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-map-marked-alt"></i> Status Pengiriman</h5>
        </div>
        <div class="card-body">
            <x-order-tracking :order="$order" />
            
            @if($order->status == 'shipped' && !$order->confirmed_at)
            <div class="alert alert-warning mt-3">
                <i class="fas fa-info-circle"></i> 
                <strong>Pesanan sedang dikirim!</strong> Jika Anda sudah menerima pesanan, silakan konfirmasi di bawah.
            </div>
            <form action="{{ route('customer.orders.confirm-received', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Konfirmasi bahwa Anda sudah menerima pesanan ini?')">
                    <i class="fas fa-check-circle"></i> Konfirmasi Pesanan Diterima
                </button>
            </form>
            @elseif($order->status == 'delivered')
            <div class="alert alert-success mt-3">
                <i class="fas fa-check-circle"></i> 
                <strong>Pesanan telah diterima!</strong> Terima kasih atas konfirmasinya.
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Info -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $order->order_number }}</h5>
                            <small class="text-muted">{{ $order->created_at->format('d F Y, H:i') }}</small>
                        </div>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$order->status] }} px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Produk yang Dipesan:</h6>
                    @foreach($order->items as $item)
                    <div class="row align-items-center border-bottom py-3">
                        <div class="col-md-2">
                            @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid rounded" alt="{{ $item->product_name }}">
                            @else
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4CAF50, #8BC34A); border-radius: 5px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                                🥬
                            </div>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <strong>{{ $item->product_name }}</strong><br>
                            <small class="text-muted">Rp{{ number_format($item->price, 0, ',', '.') }}/{{ $item->product->unit ?? 'kg' }}</small>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge bg-secondary">{{ $item->quantity }} {{ $item->product->unit ?? 'kg' }}</span>
                        </div>
                        <div class="col-md-3 text-end">
                            <strong class="text-success">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Status & Upload -->
            @if($order->payment_status == 'unpaid')
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-circle"></i> Menunggu Pembayaran</h5>
                </div>
                <div class="card-body">
                    @include('customer.payment-info')
                    @if($order->payment_proof)
                    <div class="alert alert-info">
                        <i class="fas fa-check-circle"></i> Bukti pembayaran sudah diupload. Menunggu verifikasi admin.
                    </div>
                    <div class="mb-3">
                        <strong>Bukti Transfer yang Diupload:</strong><br>
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" class="img-thumbnail mt-2" style="max-width: 300px;">
                        <br>
                        <small class="text-muted">Diupload: {{ $order->payment_proof_uploaded_at->format('d M Y H:i') }}</small>
                    </div>
                    @endif
                    <form action="{{ route('customer.orders.upload-payment', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ $order->payment_proof ? 'Upload Ulang' : 'Upload' }} Bukti Transfer</label>
                            <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept="image/*" required>
                            @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> {{ $order->payment_proof ? 'Upload Ulang' : 'Upload' }} Bukti
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle"></i> <strong>Pembayaran Sudah Dikonfirmasi</strong>
            </div>
            @endif

            <!-- Shipping Info -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Alamat Pengiriman</small><br>
                            <strong>{{ $order->shipping_address }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">No. Telepon</small><br>
                            <strong>{{ $order->phone }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Pengiriman</small><br>
                            <strong>{{ $order->delivery_date ? $order->delivery_date->format('d F Y') : '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Metode Pembayaran</small><br>
                            <strong class="text-capitalize">{{ $order->payment_method }}</strong>
                        </div>
                    </div>
                    @if($order->special_requests)
                    <hr>
                    <small class="text-muted">Catatan Khusus</small><br>
                    <p class="mb-0">{{ $order->special_requests }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Ongkos Kirim</span>
                        <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <h4 class="text-success mb-0">Rp{{ number_format($order->total, 0, ',', '.') }}</h4>
                    </div>
                    <div class="alert alert-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }} mb-0">
                        <i class="fas fa-info-circle"></i> 
                        Status: <strong>{{ $order->payment_status == 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if(in_array($order->status, ['pending', 'processing']))
            <div class="card">
                <div class="card-body">
                    <h6>Aksi</h6>
                    <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            <i class="fas fa-times-circle"></i> Batalkan Pesanan
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>  
</div>
@endsection