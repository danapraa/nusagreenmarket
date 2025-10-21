<div class="card mb-3">
    <div class="card-header bg-info text-white">
        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Pembayaran</h6>
    </div>
    <div class="card-body">
        <h6>Transfer ke Rekening Berikut:</h6>
        
        <div class="alert alert-light border">
            <div class="mb-2">
                <strong>Bank BCA</strong><br>
                <span class="fs-5 text-primary">1234567890</span><br>
                a.n. <strong>NusaGreen Market</strong>
            </div>
            <hr>
            <div class="mb-2">
                <strong>Bank Mandiri</strong><br>
                <span class="fs-5 text-primary">9876543210</span><br>
                a.n. <strong>NusaGreen Market</strong>
            </div>
            <hr>
            <div>
                <strong>Bank BRI</strong><br>
                <span class="fs-5 text-primary">5555666677</span><br>
                a.n. <strong>NusaGreen Market</strong>
            </div>
        </div>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> 
            <strong>Penting:</strong> Transfer sejumlah <strong class="text-danger">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
        </div>

        <h6 class="mt-3">Cara Pembayaran:</h6>
        <ol>
            <li>Transfer ke salah satu rekening di atas</li>
            <li>Simpan bukti transfer</li>
            <li>Upload bukti transfer melalui form di bawah</li>
            <li>Tunggu konfirmasi dari admin (maksimal 1x24 jam)</li>
        </ol>
    </div>
</div>