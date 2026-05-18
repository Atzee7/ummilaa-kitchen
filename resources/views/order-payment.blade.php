@extends('layouts.app')

@section('content')
<div class="px-[80px] py-[60px] min-h-[70vh]">

    {{-- HEADER --}}
    <div class="text-center mb-10">
        <div data-permanent class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
        </div>
        <h1 class="font-playfair text-[2rem] text-[#1a1a1a] mb-2">Pesanan Berhasil Dibuat!</h1>
        <p class="text-[#999] text-[0.9rem]">
            Order <strong class="text-maroon">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>
            &middot; Selesaikan pembayaran Anda
        </p>
    </div>

    <div class="max-w-xl mx-auto">

        @if($order->metode_pembayaran === 'Bayar Online')
        {{-- ===== BAYAR ONLINE via MIDTRANS SNAP ===== --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-8 mb-6">
            <div class="flex items-center gap-3 mb-6 pb-5 border-b border-maroon-200">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-credit-card text-white"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#1a1a1a] text-[1rem]">Pembayaran Online</h2>
                    <p class="text-[0.8rem] text-[#999]">Kartu kredit, GoPay, OVO, ShopeePay, QRIS, VA semua bank</p>
                </div>
            </div>

            <div class="flex justify-between text-[0.88rem] mb-6 bg-maroon-50 px-5 py-4 rounded-xl">
                <span class="text-[#999]">Total yang dibayar</span>
                <strong class="text-maroon text-[1.2rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
            </div>

            @if($order->status === 'belum_bayar')
                @if($order->snap_token)
                <button type="button" id="btn-snap-pay"
                        data-snap-token="{{ $order->snap_token }}"
                        class="w-full py-[14px] bg-maroon text-white rounded-xl font-extrabold text-[0.95rem] hover:bg-maroon-dark transition-all flex items-center justify-center gap-2 mb-4">
                    <i class="fas fa-lock"></i> Bayar Sekarang
                </button>
                <p class="text-[0.78rem] text-[#999] text-center">
                    Anda akan diarahkan ke halaman pembayaran aman Midtrans. Pilih metode pembayaran favorit Anda di sana.
                </p>
                @else
                <div class="bg-pink-100 border-[1.5px] border-red-300 text-red-700 rounded-xl px-5 py-4 text-[0.85rem]">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    Gagal memuat halaman pembayaran. Silakan refresh halaman ini atau hubungi admin.
                </div>
                @endif
            @endif

            <div class="space-y-3 text-[0.85rem] text-[#555] mt-6 pt-5 border-t border-[#f0eaea]">
                <p class="font-bold text-[#1a1a1a] mb-2">Cara Pembayaran:</p>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">1</span>
                    <span>Klik tombol <strong>Bayar Sekarang</strong></span>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">2</span>
                    <span>Pilih metode pembayaran yang Anda inginkan</span>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">3</span>
                    <span>Selesaikan pembayaran sesuai instruksi di layar</span>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">4</span>
                    <span>Status pesanan akan otomatis diperbarui setelah pembayaran berhasil</span>
                </div>
            </div>
        </div>

        @else
        {{-- ===== COD ===== --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-8 mb-6">
            <div class="flex items-center gap-3 mb-6 pb-5 border-b border-maroon-200">
                <div class="w-10 h-10 bg-green-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-money-bill-wave text-white"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#1a1a1a] text-[1rem]">Cash on Delivery (COD)</h2>
                    <p class="text-[0.8rem] text-[#999]">Bayar saat pesanan diterima</p>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-5 text-center">
                <i class="fas fa-hand-holding-usd text-green-600 text-3xl mb-2 block"></i>
                <p class="font-bold text-green-800 text-[0.95rem]">Siapkan uang pas</p>
                <p class="text-green-700 text-[1.3rem] font-extrabold mt-1">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
            </div>

            <p class="text-[0.85rem] text-[#777] leading-relaxed">
                Pesanan Anda sedang menunggu konfirmasi dari kami. Pembayaran dilakukan kepada kurir saat pesanan tiba,
                atau kepada kasir saat Anda mengambil pesanan di toko.
            </p>
        </div>
        @endif

        {{-- STATUS PESANAN --}}
        <div class="bg-maroon-50 border-[1.5px] border-maroon-200 rounded-[20px] p-6 mb-6">
            <p class="text-[0.82rem] font-bold text-maroon mb-3 uppercase tracking-wider">Status Pesanan</p>

            @if($order->status === 'belum_bayar')
                <div class="flex items-center gap-3 flex-wrap mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-orange-700 rounded-[20px] text-[0.78rem] font-extrabold">
                        <i class="fas fa-hourglass-half"></i> Belum Bayar
                    </span>
                    <span class="text-[0.8rem] text-[#999]">Selesaikan pembayaran sebelum waktu habis</span>
                </div>

                <div class="flex items-center justify-between bg-white border-[1.5px] border-amber-300 rounded-xl px-5 py-4">
                    <div>
                        <p class="text-[0.78rem] text-[#999] mb-0.5">Sisa waktu pembayaran</p>
                        <p class="text-[0.75rem] text-amber-700">Pesanan dibatalkan otomatis jika lewat waktu</p>
                    </div>
                    <strong class="font-mono text-[1.6rem] text-maroon payment-countdown" data-expires-at="{{ $order->paymentExpiresAt()->toIso8601String() }}">--:--</strong>
                </div>
            @else
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 text-yellow-800 rounded-[20px] text-[0.78rem] font-extrabold">
                        <i class="fas fa-clock"></i> Menunggu
                    </span>
                    <span class="text-[0.8rem] text-[#999]">Kami akan segera memproses pesanan Anda</span>
                </div>
            @endif
        </div>

        {{-- TOMBOL NAVIGASI --}}
        <div class="flex gap-3">
            <a href="{{ route('orders') }}"
               class="flex-1 flex items-center justify-center gap-2 py-[14px] border-[1.5px] border-maroon text-maroon rounded-xl font-bold text-[0.9rem] no-underline hover:bg-maroon-50 transition-all">
                <i class="fas fa-list"></i> Pesanan Saya
            </a>
            <a href="{{ route('home') }}"
               class="flex-1 flex items-center justify-center gap-2 py-[14px] bg-maroon text-white rounded-xl font-bold text-[0.9rem] no-underline hover:bg-maroon-dark transition-all">
                <i class="fas fa-home"></i> Kembali ke Home
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
@if($order->metode_pembayaran === 'Bayar Online' && $order->status === 'belum_bayar' && $order->snap_token)
<script src="https://app.{{ config('services.midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
(function () {
    const btn = document.getElementById('btn-snap-pay');
    if (!btn || typeof snap === 'undefined') return;

    btn.addEventListener('click', function () {
        const token = btn.dataset.snapToken;
        if (!token) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuka pembayaran...';

        const syncUrl = '{{ route('orders.syncPayment', $order->id) }}';
        const ordersUrl = '{{ route('orders') }}';
        const csrfToken = '{{ csrf_token() }}';

        // Sync status order dari Midtrans sebelum redirect — fallback kalau webhook miss.
        function syncThenRedirect() {
            fetch(syncUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            }).catch(() => {}).finally(() => {
                window.location.href = ordersUrl;
            });
        }

        snap.pay(token, {
            onSuccess: syncThenRedirect,
            onPending: syncThenRedirect,
            onError: function () {
                alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
            },
            onClose: function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-lock"></i> Bayar Sekarang';
            }
        });
    });
})();
</script>
@endif

<script>
// Countdown pembayaran untuk pesanan belum_bayar
(function () {
    const elements = document.querySelectorAll('.payment-countdown');
    if (!elements.length) return;
    let needReload = false;
    function tick() {
        const now = Date.now();
        elements.forEach(el => {
            const target = new Date(el.dataset.expiresAt).getTime();
            const diff = Math.max(0, Math.floor((target - now) / 1000));
            const mm = String(Math.floor(diff / 60)).padStart(2, '0');
            const ss = String(diff % 60).padStart(2, '0');
            el.textContent = `${mm}:${ss}`;
            if (diff === 0 && !needReload) {
                needReload = true;
                setTimeout(() => window.location.reload(), 1500);
            }
        });
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
@endpush
