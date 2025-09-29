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
            <div class="row">
                <div class="col-md-8">
                    <h6>Produk:</h6>
                    @foreach($order->items->take(3) as $item)
                    <div class="mb-1">
                        <i class="fas fa-check-circle text-success"></i> 
                        {{ $item->product_name }} ({{ $item->quantity }} {{ $item->product->unit ?? 'kg' }})
                    </div>
                    @endforeach
                    @if($order->items->count() > 3)
                    <small class="text-muted">+{{ $order->items->count() - 3 }} produk lainnya</small>
                    @endif
                </div>
                <div class="col-md-4 text-end">
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
@endsection