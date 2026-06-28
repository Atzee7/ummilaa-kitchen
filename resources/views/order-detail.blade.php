@extends('layouts.app')

@section('content')
<div class="px-4 md:px-10 lg:px-[80px] py-10 lg:py-[60px]">
    <a href="{{ route('orders') }}" class="inline-flex items-center gap-2 text-maroon font-bold text-[0.9rem] mb-7 transition-all duration-200 hover:gap-3 no-underline">
        <i class="fas fa-arrow-left"></i> Kembali ke Riwayat Katalog
    </a>

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-10">
        <div>
            <h1 class="font-playfair text-[2rem] text-[#1a1a1a]">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-[#999] mt-1.5 text-[0.88rem]"><i class="fas fa-calendar-alt mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-[18px] py-2 rounded-[20px] text-[0.82rem] font-extrabold
            @if($order->status === 'belum_bayar') bg-amber-50 text-orange-700
            @elseif($order->status === 'pending') bg-yellow-50 text-yellow-800
            @elseif($order->status === 'diproses') bg-blue-50 text-blue-800
            @elseif($order->status === 'dikirim') bg-purple-50 text-purple-800
            @elseif($order->status === 'siap_diambil') bg-orange-50 text-orange-700
            @elseif($order->status === 'selesai') bg-green-100 text-green-800
            @elseif($order->status === 'pembayaran') bg-teal-50 text-teal-700
            @else bg-pink-100 text-red-700 @endif">
            @if($order->status === 'belum_bayar') <i class="fas fa-hourglass-half"></i> Belum Bayar
            @elseif($order->status === 'pending') <i class="fas fa-clock"></i> Menunggu
            @elseif($order->status === 'diproses') <i class="fas fa-utensils"></i> Sedang Dimasak
            @elseif($order->status === 'dikirim') <i class="fas fa-truck"></i> Sedang Dikirim
            @elseif($order->status === 'siap_diambil') <i class="fas fa-store"></i> Siap Diambil
            @elseif($order->status === 'selesai') <i class="fas fa-check-circle"></i> Selesai
            @elseif($order->status === 'pembayaran') <i class="fas fa-credit-card"></i> Sudah Bayar
            @else <i class="fas fa-times-circle"></i> Dibatalkan @endif
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 lg:gap-8 items-start">
        <div>
            {{-- PRODUK --}}
            <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 mb-6 shadow-sm">
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

            {{-- PENGIRIMAN / PENGAMBILAN --}}
            @php $isDelivery = ($order->metode_pengiriman ?? 'delivery') === 'delivery'; @endphp
            <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 shadow-sm">
                <div class="font-extrabold text-[#1a1a1a] text-[0.97rem] mb-5 pb-[14px] border-b border-maroon-200 flex items-center gap-[10px]">
                    <i class="fas {{ $isDelivery ? 'fa-map-marker-alt' : 'fa-store' }} text-maroon"></i>
                    {{ $isDelivery ? 'Info Pengiriman' : 'Info Pengambilan' }}
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem] gap-0.5 sm:gap-0">
                    <span class="text-[#999]">Penerima</span><strong class="text-[#1a1a1a] font-bold">{{ $order->nama_penerima }}</strong>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem] gap-0.5 sm:gap-0">
                    <span class="text-[#999]">No. Telepon</span><strong class="text-[#1a1a1a] font-bold">{{ $order->no_telepon }}</strong>
                </div>
                @if($isDelivery)
                <div class="flex flex-col sm:flex-row sm:justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem] gap-0.5 sm:gap-0">
                    <span class="text-[#999] flex-shrink-0">Alamat</span><strong class="text-[#1a1a1a] font-bold sm:text-right">{{ $order->alamat }}</strong>
                </div>
                @if($order->detail_alamat)
                <div class="flex flex-col sm:flex-row sm:justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem] gap-0.5 sm:gap-0">
                    <span class="text-[#999] flex-shrink-0">Detail Alamat</span><strong class="text-[#1a1a1a] font-bold sm:text-right">{{ $order->detail_alamat }}</strong>
                </div>
                @endif
                @else
                <div class="flex flex-col sm:flex-row sm:justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem] gap-0.5 sm:gap-0">
                    <span class="text-[#999] flex-shrink-0">Lokasi Pengambilan</span><strong class="text-[#1a1a1a] font-bold sm:text-right">Toko Ummilaa Kitchen</strong>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem] gap-0.5 sm:gap-0">
                    <span class="text-[#999] flex-shrink-0">Alamat Toko</span><strong class="text-[#1a1a1a] font-bold sm:text-right">Jl. Kapi Anala I 7 No.15M, Sawojajar A, Sekarpuro, Kec. Pakis, Kab. Malang</strong>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-[10px] border-b border-[#f8f0f0] text-[0.9rem] gap-0.5 sm:gap-0">
                    <span class="text-[#999] flex-shrink-0">Jam Operasional</span><strong class="text-[#1a1a1a] font-bold">07.00 – 19.00 WIB</strong>
                </div>
                @endif
                @if($order->tanggal_pengiriman)
                <div class="flex flex-col sm:flex-row sm:justify-between py-[10px] text-[0.9rem] gap-0.5 sm:gap-0">
                    <span class="text-[#999]">{{ $isDelivery ? 'Jadwal Pengiriman' : 'Jadwal Pengambilan' }}</span>
                    <strong class="text-[#1a1a1a] font-bold">
                        {{ $order->tanggal_pengiriman->isToday() ? 'Hari ini' : $order->tanggal_pengiriman->translatedFormat('l, d F Y') }}@if($order->waktu_pengiriman) · {{ $order->waktu_pengiriman }}@endif
                    </strong>
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
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 lg:sticky lg:top-[90px]">
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
            @if($order->status === 'belum_bayar')
            <a href="{{ route('order.payment', $order->id) }}" class="flex items-center justify-center gap-2 w-full mt-5 py-[13px] bg-maroon text-white rounded-xl font-bold text-[0.9rem] transition-all duration-200 hover:bg-maroon-dark no-underline">
                <i class="fas fa-credit-card"></i> Lanjut Pembayaran
            </a>
            @else
            <a href="{{ route('catalogue') }}" class="flex items-center justify-center gap-2 w-full mt-5 py-[13px] bg-maroon text-white rounded-xl font-bold text-[0.9rem] transition-all duration-200 hover:bg-maroon-dark no-underline">
                <i class="fas fa-utensils"></i> Belanja Lagi
            </a>
            @endif
            @if($order->status === 'selesai')
            <a href="{{ route('order.invoice', $order->id) }}" class="flex items-center justify-center gap-2 w-full mt-3 py-[13px] border-[1.5px] border-maroon text-maroon rounded-xl font-bold text-[0.9rem] transition-all duration-200 hover:bg-maroon hover:text-white no-underline">
                <i class="fas fa-file-invoice"></i> Unduh Invoice
            </a>
            @endif
            @if(!in_array($order->status, ['dibatalkan', 'selesai']))
            <button type="button" onclick="document.getElementById('modal-cancel').classList.remove('hidden')"
                class="w-full mt-3 py-[13px] border-[1.5px] border-red-300 text-red-600 rounded-xl font-bold text-[0.9rem] hover:bg-red-50 transition-all duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-times-circle"></i> Batalkan Pesanan
            </button>
            @endif
        </div>
    </div>
</div>

@if(!in_array($order->status, ['dibatalkan', 'selesai']))
<div id="modal-cancel" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl">
        @if($order->status === 'belum_bayar')
        <h3 class="font-bold text-[1rem] text-[#1a1a1a] mb-1">Batalkan Pesanan?</h3>
        <p class="text-[0.8rem] text-[#888] mb-4">Stok produk akan dikembalikan. Tindakan ini tidak dapat dibatalkan.</p>
        <form method="POST" action="{{ route('order.cancel', $order->id) }}">
            @csrf
            <textarea name="alasan_pembatalan" rows="3" required maxlength="500"
                placeholder="Masukkan alasan pembatalan..."
                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-[0.82rem] focus:outline-none focus:border-maroon mb-4 resize-none"></textarea>
            <div class="flex gap-2">
                <button type="button" onclick="document.getElementById('modal-cancel').classList.add('hidden')"
                    class="flex-1 py-2.5 border border-gray-200 text-[#666] rounded-xl text-[0.82rem] font-bold hover:bg-gray-50 transition-all">
                    Kembali
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-red-600 text-white rounded-xl text-[0.82rem] font-bold hover:bg-red-700 transition-all">
                    Konfirmasi Batal
                </button>
            </div>
        </form>
        @else
        <div class="text-center">
            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-amber-500 text-[1.4rem]"></i>
            </div>
            <h3 class="font-bold text-[1rem] text-[#1a1a1a] mb-2">Tidak Dapat Dibatalkan</h3>
            <p class="text-[0.85rem] text-[#888] mb-5">Pesanan ini tidak dapat dibatalkan karena sudah melewati tahap yang diperbolehkan.</p>
            <button type="button" onclick="document.getElementById('modal-cancel').classList.add('hidden')"
                class="w-full py-2.5 bg-maroon text-white rounded-xl text-[0.85rem] font-bold hover:bg-maroon-dark transition-all">
                Mengerti
            </button>
        </div>
        @endif
    </div>
</div>
@endif

@endsection
