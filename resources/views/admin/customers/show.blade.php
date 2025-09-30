@extends('layouts.admin')

@section('title', 'Detail Customer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Customer</h2>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Customer</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Nama</small>
                    <h6>{{ $user->name }}</h6>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Email</small>
                    <h6>{{ $user->email }}</h6>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Telepon</small>
                    <h6>{{ $user->phone ?? '-' }}</h6>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Alamat</small>
                    <p>{{ $user->address ?? '-' }}</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Terdaftar Sejak</small>
                    <h6>{{ $user->created_at->format('d F Y') }}</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Riwayat Pesanan ({{ $user->orders->count() }})</h5>
            </div>
            <div class="card-body">
                @forelse($user->orders as $order)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>{{ $order->order_number }}</strong>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$order->status] }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal</small><br>
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Total</small><br>
                            <strong class="text-success">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Produk: {{ $order->items->count() }} item</small>
                    </div>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary mt-2">
                        Lihat Detail Pesanan
                    </a>
                </div>
                @empty
                <p class="text-center text-muted">Belum ada pesanan</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection