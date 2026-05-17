@extends('layouts.app')

@section('content')
<div class="px-[80px] py-[60px] min-h-[70vh]">

    {{-- HEADER --}}
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
        </div>
        <h1 class="font-playfair text-[2rem] text-[#1a1a1a] mb-2">Pesanan Berhasil Dibuat!</h1>
        <p class="text-[#999] text-[0.9rem]">
            Order <strong class="text-maroon">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>
            &middot; Selesaikan pembayaran Anda
        </p>
    </div>

    <div class="max-w-xl mx-auto">

        @if($order->metode_pembayaran === 'Mandiri Virtual Account')
        {{-- ===== MANDIRI VIRTUAL ACCOUNT ===== --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-8 mb-6">
            <div class="flex items-center gap-3 mb-6 pb-5 border-b border-maroon-200">
                <div class="w-10 h-10 bg-yellow-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-university text-white"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#1a1a1a] text-[1rem]">Mandiri Virtual Account</h2>
                    <p class="text-[0.8rem] text-[#999]">Transfer sesuai nominal yang tertera</p>
                </div>
            </div>

            <p class="text-[0.82rem] text-[#999] mb-1 font-semibold uppercase tracking-wider">Nomor Virtual Account</p>
            <div class="flex items-center justify-between bg-maroon-50 px-5 py-4 rounded-xl mb-6">
                <span class="font-extrabold text-[1.4rem] text-maroon tracking-widest" id="va-number">8800 0012 3456 7890</span>
                <button onclick="copyVA()" class="text-maroon font-bold text-[0.82rem] hover:underline flex-shrink-0">
                    <i class="fas fa-copy mr-1"></i> Salin
                </button>
            </div>

            <div class="flex justify-between text-[0.88rem] mb-6 px-1">
                <span class="text-[#999]">Total yang dibayar</span>
                <strong class="text-maroon text-[1.05rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
            </div>

            <div class="space-y-3 text-[0.85rem] text-[#555]">
                <p class="font-bold text-[#1a1a1a] mb-2">Cara Pembayaran:</p>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-maroon text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">1</span>
                    <span>Buka aplikasi <strong>Livin' by Mandiri</strong> atau ATM Mandiri</span>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-maroon text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">2</span>
                    <span>Pilih menu <strong>Transfer</strong> &rarr; <strong>Virtual Account</strong></span>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-maroon text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">3</span>
                    <span>Masukkan nomor VA di atas</span>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-maroon text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">4</span>
                    <span>Konfirmasi jumlah transfer dan selesaikan pembayaran</span>
                </div>
            </div>
        </div>

        @elseif($order->metode_pembayaran === 'QRIS')
        {{-- ===== QRIS ===== --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-8 mb-6">
            <div class="flex items-center gap-3 mb-6 pb-5 border-b border-maroon-200">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-qrcode text-white"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#1a1a1a] text-[1rem]">Pembayaran QRIS</h2>
                    <p class="text-[0.8rem] text-[#999]">Scan QR Code menggunakan aplikasi e-wallet</p>
                </div>
            </div>

            <div class="flex flex-col items-center mb-6">
                {{-- Placeholder QR Code — ganti dengan <img> ke gambar QRIS asli --}}
                <div class="w-52 h-52 border-2 border-dashed border-maroon-200 rounded-xl flex items-center justify-center bg-maroon-50 mb-3">
                    <div class="text-center text-[#ccc]">
                        <i class="fas fa-qrcode text-5xl mb-2 block"></i>
                        <p class="text-[0.75rem]">QR Code</p>
                    </div>
                </div>
                <p class="text-[0.78rem] text-[#bbb]">Scan dengan GoPay, OVO, Dana, atau aplikasi bank</p>
            </div>

            <div class="flex justify-between text-[0.88rem] mb-6 bg-maroon-50 px-5 py-4 rounded-xl">
                <span class="text-[#999]">Total yang dibayar</span>
                <strong class="text-maroon text-[1.05rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
            </div>

            <div class="space-y-3 text-[0.85rem] text-[#555]">
                <p class="font-bold text-[#1a1a1a] mb-2">Cara Pembayaran:</p>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">1</span>
                    <span>Buka aplikasi e-wallet atau m-banking Anda</span>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">2</span>
                    <span>Pilih menu <strong>Scan QR</strong> atau <strong>QRIS</strong></span>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">3</span>
                    <span>Arahkan kamera ke QR Code di atas</span>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[0.72rem] font-bold flex-shrink-0 mt-0.5">4</span>
                    <span>Pastikan nominal sesuai dan konfirmasi pembayaran</span>
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
            <div class="flex items-center gap-3 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-orange-700 rounded-[20px] text-[0.78rem] font-extrabold">
                    <i class="fas fa-clock"></i> Menunggu Konfirmasi
                </span>
                <span class="text-[0.8rem] text-[#999]">Kami akan segera memproses pesanan Anda</span>
            </div>
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
<script>
function copyVA() {
    const va = document.getElementById('va-number')?.textContent.replace(/\s/g, '');
    if (!va) return;
    navigator.clipboard.writeText(va).then(() => {
        alert('Nomor VA berhasil disalin: ' + va);
    });
}
</script>
@endpush
