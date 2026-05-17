@extends('layouts.app')

@section('content')
<div class="px-[80px] py-[80px] text-center">
    <div data-permanent class="w-[90px] h-[90px] bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 text-[2.5rem] text-green-700">
        <i class="fas fa-check"></i>
    </div>
    <h1 class="font-playfair text-[2.2rem] text-[#1a1a1a] mb-3">Pesanan Berhasil!</h1>
    <p class="text-[#777] text-[0.97rem] mb-2">Terima kasih telah berbelanja di Ummilaa Kitchen.</p>
    <p class="text-[#777] text-[0.97rem]">Pesanan Anda sedang kami proses.</p>
    <div class="inline-block bg-maroon-100 text-maroon px-5 py-2 rounded-[20px] font-extrabold text-[0.9rem] mt-4 mb-8">
        Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
    </div>

    <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 max-w-[560px] mx-auto mb-10 text-left">
        <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
            <span class="text-[#999]">Penerima</span><strong class="text-[#1a1a1a] font-bold">{{ $order->nama_penerima }}</strong>
        </div>
        <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
            <span class="text-[#999]">No. Telepon</span><strong class="text-[#1a1a1a] font-bold">{{ $order->no_telepon }}</strong>
        </div>
        <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
            <span class="text-[#999]">Alamat</span><strong class="text-[#1a1a1a] font-bold">{{ $order->alamat }}</strong>
        </div>
        <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
            <span class="text-[#999]">Metode Pembayaran</span><strong class="text-[#1a1a1a] font-bold">{{ $order->metode_pembayaran }}</strong>
        </div>
        <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
            <span class="text-[#999]">Subtotal</span><strong class="text-[#1a1a1a] font-bold">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</strong>
        </div>
        <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
            <span class="text-[#999]">Ongkos Kirim</span><strong class="text-[#1a1a1a] font-bold">Rp{{ number_format($order->ongkir, 0, ',', '.') }}</strong>
        </div>
        <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
            <span class="text-[#999]">Total Pembayaran</span><strong class="text-maroon font-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
        </div>
        @php
            $successStatusLabel = match($order->status) {
                'belum_bayar'  => 'Belum Bayar',
                'pending'      => 'Menunggu',
                'diproses'     => 'Sedang Dimasak',
                'dikirim'      => 'Dikirim',
                'siap_diambil' => 'Siap Diambil',
                'selesai'      => 'Selesai',
                'dibatalkan'   => 'Dibatalkan',
                default        => ucfirst($order->status),
            };
        @endphp
        <div class="flex justify-between py-[10px] text-[0.9rem]">
            <span class="text-[#999]">Status</span><strong class="text-orange-700 font-bold">{{ $successStatusLabel }}</strong>
        </div>
    </div>

    <div class="flex justify-center gap-4">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-maroon text-white px-7 py-[13px] rounded-xl font-bold text-[0.95rem] transition-all duration-200 hover:bg-maroon-dark no-underline">
            <i class="fas fa-home"></i> Kembali ke Home
        </a>
        <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 bg-white text-maroon px-7 py-[13px] rounded-xl font-bold text-[0.95rem] border-2 border-maroon transition-all duration-200 hover:bg-maroon-100 no-underline">
            <i class="fas fa-utensils"></i> Belanja Lagi
        </a>
    </div>
</div>
@endsection
