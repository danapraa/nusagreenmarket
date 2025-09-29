@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Pesanan: {{ $order->order_number }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Produk Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Pelanggan & Pengiriman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Pelanggan</h6>
                        <p>
                            <strong>{{ $order->user->name }}</strong><br>
                            {{ $order->user->email }}<br>
                            {{ $order->phone }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Alamat Pengiriman</h6>
                        <p>{{ $order->shipping_address }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Tanggal Pengiriman:</strong> {{ $order->delivery_date ? $order->delivery_date->format('d F Y') : '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Metode Pembayaran:</strong> <span class="text-capitalize">{{ $order->payment_method }}</span>
                    </div>
                </div>
                @if($order->special_requests)
                <hr>
                <strong>Catatan Khusus:</strong>
                <p class="mb-0">{{ $order->special_requests }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ringkasan</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <span>Ongkir</span>
                    <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <strong>Total</strong>
                    <h4 class="text-success mb-0">Rp{{ number_format($order->total, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Status Pesanan</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Update Payment Status -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Status Pembayaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <select name="payment_status" class="form-select" required>
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check"></i> Update Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection