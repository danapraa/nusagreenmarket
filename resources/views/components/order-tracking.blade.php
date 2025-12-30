@props(['order'])

<div class="order-tracking">
    <div class="timeline">
        <!-- Order Placed -->
        <div class="timeline-item {{ $order->status ? 'active' : '' }}">
            <div class="timeline-marker">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="timeline-content">
                <h6>Pesanan Dibuat</h6>
                <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
            </div>
        </div>

        <!-- Processing -->
        <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}">
            <div class="timeline-marker">
                <i class="fas fa-{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'check-circle' : 'circle' }}"></i>
            </div>
            <div class="timeline-content">
                <h6>Sedang Diproses</h6>
                <small class="text-muted">
                    {{ $order->status == 'processing' || in_array($order->status, ['shipped', 'delivered']) ? 'Pesanan sedang dikemas' : 'Menunggu proses' }}
                </small>
            </div>
        </div>

        <!-- Shipped -->
        <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }}">
            <div class="timeline-marker">
                <i class="fas fa-{{ in_array($order->status, ['shipped', 'delivered']) ? 'check-circle' : 'circle' }}"></i>
            </div>
            <div class="timeline-content">
                <h6>Dalam Pengiriman</h6>
                @if($order->tracking_number)
                <p class="mb-1">
                    <strong>Resi:</strong> <code>{{ $order->tracking_number }}</code>
                    <button class="btn btn-sm btn-link p-0" onclick="copyResi('{{ $order->tracking_number }}')">
                        <i class="fas fa-copy"></i>
                    </button>
                </p>
                @endif
                @if($order->courier_info)
                <p class="mb-0"><strong>Kurir:</strong> {{ $order->courier_info }}</p>
                @endif
                <small class="text-muted">
                    {{ $order->status == 'shipped' || $order->status == 'delivered' ? 'Paket dalam perjalanan' : 'Belum dikirim' }}
                </small>
            </div>
        </div>

        <!-- Delivered -->
        <div class="timeline-item {{ $order->status == 'delivered' ? 'active' : '' }}">
            <div class="timeline-marker">
                <i class="fas fa-{{ $order->status == 'delivered' ? 'check-circle' : 'circle' }}"></i>
            </div>
            <div class="timeline-content">
                <h6>Pesanan Diterima</h6>
                @if($order->confirmed_at)
                <small class="text-muted">Dikonfirmasi: {{ $order->confirmed_at->format('d M Y, H:i') }}</small>
                @else
                <small class="text-muted">Belum dikonfirmasi</small>
                @endif
            </div>
        </div>
    </div>

    @if($order->status == 'cancelled')
    <div class="alert alert-danger mt-3">
        <i class="fas fa-times-circle"></i> <strong>Pesanan Dibatalkan</strong>
        @if($order->notes)
        <p class="mb-0 mt-2">Alasan: {{ $order->notes }}</p>
        @endif
    </div>
    @endif
</div>

<style>
.order-tracking {
    padding: 20px 0;
}
.timeline {
    position: relative;
    padding-left: 50px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}
.timeline-item {
    position: relative;
    padding-bottom: 30px;
    opacity: 0.5;
}
.timeline-item.active {
    opacity: 1;
}
.timeline-marker {
    position: absolute;
    left: -43px;
    width: 30px;
    height: 30px;
    background: #f5f5f5;
    border: 2px solid #e0e0e0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 14px;
}
.timeline-item.active .timeline-marker {
    background: #4CAF50;
    border-color: #4CAF50;
    color: white;
}
.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}
</style>

<script>
function copyResi(text) {
    navigator.clipboard.writeText(text);
    alert('Nomor resi berhasil disalin: ' + text);
}
</script>