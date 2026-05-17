@extends('layouts.app')

@section('content')
<div class="px-[80px] py-[60px]">
    <a href="{{ route('orders') }}" class="inline-flex items-center gap-2 text-maroon font-bold text-[0.9rem] mb-7 transition-all duration-200 hover:gap-3 no-underline">
        <i class="fas fa-arrow-left"></i> Kembali ke Pesanan Saya
    </a>

    <div class="flex justify-between items-start mb-10">
        <div>
            <h1 class="font-playfair text-[2rem] text-[#1a1a1a]">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-[#999] mt-1.5 text-[0.88rem]"><i class="fas fa-calendar-alt mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-[18px] py-2 rounded-[20px] text-[0.82rem] font-extrabold
            @if($order->status === 'pending') bg-amber-50 text-orange-700
            @elseif($order->status === 'diproses') bg-blue-50 text-blue-800
            @elseif($order->status === 'dikirim') bg-purple-50 text-purple-800
            @elseif($order->status === 'siap_diambil') bg-orange-50 text-orange-700
            @elseif($order->status === 'selesai') bg-green-100 text-green-800
            @else bg-pink-100 text-red-700 @endif">
            @if($order->status === 'pending') <i class="fas fa-clock"></i> Menunggu Konfirmasi
            @elseif($order->status === 'diproses') <i class="fas fa-cog fa-spin"></i> Sedang Diproses
            @elseif($order->status === 'dikirim') <i class="fas fa-truck"></i> Sedang Dikirim
            @elseif($order->status === 'siap_diambil') <i class="fas fa-store"></i> Siap Diambil
            @elseif($order->status === 'selesai') <i class="fas fa-check-circle"></i> Selesai
            @else <i class="fas fa-times-circle"></i> Dibatalkan @endif
        </span>
    </div>

    <div class="grid grid-cols-[1fr_360px] gap-8 items-start">
        <div>
            {{-- PRODUK --}}
            <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 mb-6">
                <div class="font-extrabold text-[#1a1a1a] text-[0.97rem] mb-5 pb-[14px] border-b border-maroon-200 flex items-center gap-[10px]">
                    <i class="fas fa-box text-maroon"></i> Detail Produk
                </div>
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 py-[14px] border-b border-[#f8f0f0] last:border-0 last:pb-0">
                    <img class="w-[70px] h-[70px] object-cover rounded-xl"
                        src="{{ $item->product->image && Str::startsWith($item->product->image, 'products/') ? asset('storage/' . $item->product->image) : $item->product->image }}"
                        alt="{{ $item->product->name }}">
                    <div class="flex-1">
                        <h4 class="font-bold text-[#1a1a1a] text-[0.95rem] mb-1">{{ $item->product->name }}</h4>
                        <p class="text-[0.82rem] text-[#999]">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <div class="font-extrabold text-maroon text-[0.95rem]">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                        <span class="text-[0.78rem] text-[#bbb] font-normal">{{ $item->quantity }} item</span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- PENGIRIMAN --}}
            <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7">
                <div class="font-extrabold text-[#1a1a1a] text-[0.97rem] mb-5 pb-[14px] border-b border-maroon-200 flex items-center gap-[10px]">
                    <i class="fas fa-map-marker-alt text-maroon"></i> Info Pengiriman
                </div>
                <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
                    <span class="text-[#999]">Penerima</span><strong class="text-[#1a1a1a] font-bold">{{ $order->nama_penerima }}</strong>
                </div>
                <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
                    <span class="text-[#999]">No. Telepon</span><strong class="text-[#1a1a1a] font-bold">{{ $order->no_telepon }}</strong>
                </div>
                <div class="flex justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem]">
                    <span class="text-[#999]">Alamat</span><strong class="text-[#1a1a1a] font-bold text-right max-w-[200px]">{{ $order->alamat }}</strong>
                </div>
                @if($order->detail_alamat)
                <div class="flex justify-between py-[10px] text-[0.9rem]">
                    <span class="text-[#999]">Detail Alamat</span><strong class="text-[#1a1a1a] font-bold text-right max-w-[200px]">{{ $order->detail_alamat }}</strong>
                </div>
                @endif
            </div>

            @if($order->status === 'dibatalkan' && $order->alasan_pembatalan)
            <div class="bg-red-50 border-[1.5px] border-red-200 rounded-[20px] p-7 mt-6">
                <div class="font-extrabold text-red-700 text-[0.97rem] mb-3 flex items-center gap-[10px]">
                    <i class="fas fa-times-circle"></i> Alasan Pembatalan
                </div>
                <p class="text-[0.9rem] text-red-800 leading-relaxed">{{ $order->alasan_pembatalan }}</p>
            </div>
            @endif
        </div>

        {{-- SUMMARY --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 sticky top-[90px]">
            <div class="font-extrabold text-[#1a1a1a] text-[0.97rem] mb-5 pb-[14px] border-b border-maroon-200">Ringkasan Pembayaran</div>
            <div class="flex justify-between text-[0.9rem] mb-3">
                <span class="text-[#777]">Subtotal</span><strong class="text-[#1a1a1a] font-bold">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</strong>
            </div>
            <div class="flex justify-between text-[0.9rem] mb-3">
                <span class="text-[#777]">Ongkos Kirim</span><strong class="text-[#1a1a1a] font-bold">Rp{{ number_format($order->ongkir, 0, ',', '.') }}</strong>
            </div>
            <div class="h-px bg-maroon-200 my-[14px]"></div>
            <div class="flex justify-between">
                <span class="font-extrabold text-[#1a1a1a]">Total</span>
                <strong class="font-extrabold text-maroon text-[1.15rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
            </div>
            <div class="mt-5 px-4 py-[14px] bg-maroon-50 rounded-xl flex items-center gap-[10px]">
                <i class="fas fa-credit-card text-maroon"></i>
                <span class="text-[0.85rem] text-[#555] font-semibold">{{ $order->metode_pembayaran }}</span>
            </div>
            <a href="{{ route('catalogue') }}" class="flex items-center justify-center gap-2 w-full mt-5 py-[13px] bg-maroon text-white rounded-xl font-bold text-[0.9rem] transition-all duration-200 hover:bg-maroon-dark no-underline">
                <i class="fas fa-utensils"></i> Belanja Lagi
            </a>
        </div>
    </div>
</div>
@endsection
