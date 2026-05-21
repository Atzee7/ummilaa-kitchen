@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-10 lg:px-[80px] py-8 lg:py-[60px] min-h-[70vh]">

    <div class="text-center mb-8 max-w-xl mx-auto">
        <div data-permanent class="w-14 h-14 bg-maroon-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-credit-card text-maroon text-xl"></i>
        </div>
        <h1 class="font-playfair text-[1.7rem] sm:text-[2rem] text-[#1a1a1a] mb-1.5">Pembayaran Catering</h1>
        <p class="text-[#999] text-[0.85rem]">
            Pesanan <strong class="text-maroon">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong> · {{ $order->nama_acara }}
        </p>
    </div>

    <div class="max-w-xl mx-auto">
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[18px] p-5 sm:p-7 mb-5">
            <div class="flex justify-between items-center text-[0.85rem] mb-5 bg-maroon-50 px-4 py-3 rounded-xl">
                <span class="text-[#999]">Total yang dibayar</span>
                <strong class="text-maroon text-[1.05rem] sm:text-[1.2rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
            </div>

            @if($order->paymentExpiresAt())
            <div class="flex items-center justify-between bg-white border-[1.5px] border-amber-300 rounded-xl px-4 py-3 mb-5">
                <div>
                    <p class="text-[0.72rem] text-[#999] mb-0.5">Sisa waktu pembayaran</p>
                    <p class="text-[0.7rem] text-amber-700">Dibatalkan otomatis jika lewat waktu</p>
                </div>
                <strong class="font-mono text-[1.4rem] sm:text-[1.6rem] text-maroon payment-countdown" data-expires-at="{{ $order->paymentExpiresAt()->toIso8601String() }}">--:--</strong>
            </div>
            @endif

            @if($order->snap_token)
            <button type="button" id="btn-snap-pay" data-snap-token="{{ $order->snap_token }}"
                    class="w-full py-[12px] sm:py-[14px] bg-maroon text-white rounded-xl font-extrabold text-[0.88rem] sm:text-[0.95rem] hover:bg-maroon-dark transition-all flex items-center justify-center gap-2 mb-3">
                <i class="fas fa-lock text-[0.8rem]"></i> Bayar Sekarang
            </button>
            <p class="text-[0.75rem] text-[#bbb] text-center">Anda akan diarahkan ke halaman pembayaran aman Midtrans.</p>
            @else
            <div class="bg-pink-100 border-[1.5px] border-red-300 text-red-700 rounded-xl px-4 py-3 text-[0.82rem]">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Gagal memuat halaman pembayaran. Silakan refresh atau hubungi admin.
            </div>
            @endif
        </div>

        <a href="{{ route('catering.history') }}"
           class="flex items-center justify-center gap-2 py-[11px] sm:py-[14px] border-[1.5px] border-maroon text-maroon rounded-xl font-bold text-[0.82rem] sm:text-[0.9rem] no-underline hover:bg-maroon-50 transition-all">
            <i class="fas fa-list text-[0.78rem]"></i> Kembali ke Riwayat Catering
        </a>
    </div>
</div>
@endsection

@push('scripts')
@if($order->snap_token)
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

        const historyUrl = '{{ route('catering.history') }}';
        function redirect() { window.location.href = historyUrl; }

        snap.pay(token, {
            onSuccess: redirect,
            onPending: redirect,
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
// Countdown pembayaran catering — reload saat habis agar controller membatalkan & redirect.
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
