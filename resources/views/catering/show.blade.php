@extends('layouts.app')

@php
    $statusMeta = [
        'pengajuan'           => ['Menunggu Pengajuan', 'bg-yellow-50 text-yellow-700', 'fa-hourglass-half'],
        'menunggu_pembayaran' => ['Menunggu Pembayaran', 'bg-amber-50 text-orange-700', 'fa-money-bill-wave'],
        'diproses'            => ['Diproses', 'bg-blue-50 text-blue-700', 'fa-utensils'],
        'dikirim'             => ['Sedang Dikirim', 'bg-purple-50 text-purple-700', 'fa-truck'],
        'selesai'             => ['Selesai', 'bg-green-50 text-green-700', 'fa-circle-check'],
        'dibatalkan'          => ['Dibatalkan', 'bg-red-50 text-red-700', 'fa-circle-xmark'],
    ];
    [$label, $color, $icon] = $statusMeta[$order->status] ?? [ucfirst($order->status), 'bg-gray-50 text-gray-700', 'fa-circle'];
@endphp

@section('content')
<div class="px-4 sm:px-10 lg:px-[80px] py-8 lg:py-[50px] min-h-[70vh]">
    <div class="max-w-2xl mx-auto">

        <a href="{{ route('catering.history') }}" class="inline-flex items-center gap-2 text-[0.85rem] text-[#888] hover:text-maroon no-underline mb-4">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
        </a>

        @if(session('success'))
        <div class="bg-green-100 text-green-800 rounded-xl px-4 py-3 text-sm mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="bg-pink-100 border border-red-300 text-red-700 rounded-xl px-4 py-3 text-sm mb-4">{{ session('error') }}</div>
        @endif

        @if($order->status === 'dikirim')
        <div class="bg-purple-50 border border-purple-200 rounded-xl px-4 py-3 mb-4 flex items-center gap-3">
            <i class="fas fa-truck text-purple-500 text-lg"></i>
            <div>
                <p class="font-bold text-purple-700 text-[0.9rem]">Pesanan Anda Sedang Diantarkan!</p>
                <p class="text-[0.8rem] text-purple-600">Pesanan Anda sedang dalam perjalanan ke lokasi acara.</p>
            </div>
        </div>
        @endif

        {{-- HEADER --}}
        <div class="bg-white border border-maroon-200 rounded-[18px] p-5 sm:p-7 mb-5">
            <div class="flex items-start justify-between gap-4 mb-5 pb-4 border-b border-maroon-100">
                <div>
                    <p class="text-[0.72rem] text-[#999] mb-1">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <h1 class="font-playfair text-[1.5rem] text-[#1a1a1a]">{{ $order->nama_acara }}</h1>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[0.75rem] font-extrabold whitespace-nowrap {{ $color }}">
                    <i class="fas {{ $icon }}"></i> {{ $label }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-[0.88rem]">
                <div>
                    <p class="text-[0.72rem] text-[#999] uppercase tracking-wider mb-0.5">Paket</p>
                    <p class="font-bold text-[#1a1a1a]">{{ $order->package->name ?? 'Custom' }}</p>
                </div>
                <div>
                    <p class="text-[0.72rem] text-[#999] uppercase tracking-wider mb-0.5">Tanggal Acara</p>
                    <p class="font-bold text-[#1a1a1a]">{{ $order->tanggal_acara->translatedFormat('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-[0.72rem] text-[#999] uppercase tracking-wider mb-0.5">Jam Pengantaran</p>
                    <p class="font-bold text-[#1a1a1a]">{{ $order->jam_pengantaran ? \Carbon\Carbon::parse($order->jam_pengantaran)->format('H:i') : '-' }}</p>
                </div>
                <div>
                    <p class="text-[0.72rem] text-[#999] uppercase tracking-wider mb-0.5">Jumlah Pax</p>
                    <p class="font-bold text-[#1a1a1a]">{{ $order->jumlah_pax }} porsi</p>
                </div>
                <div>
                    <p class="text-[0.72rem] text-[#999] uppercase tracking-wider mb-0.5">Total</p>
                    <p class="font-bold text-maroon">{{ $order->total ? 'Rp' . number_format($order->total, 0, ',', '.') : 'Menunggu penawaran' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-[0.72rem] text-[#999] uppercase tracking-wider mb-0.5">Lokasi Acara</p>
                    <p class="font-semibold text-[#444]">{{ $order->lokasi_acara }}</p>
                    @if($order->detail_lokasi_acara)
                    <p class="text-[0.82rem] text-[#666] mt-1">{{ $order->detail_lokasi_acara }}</p>
                    @endif
                </div>
                @if($order->catatan)
                <div class="col-span-2">
                    <p class="text-[0.72rem] text-[#999] uppercase tracking-wider mb-0.5">Catatan</p>
                    <p class="text-[#444] italic">"{{ $order->catatan }}"</p>
                </div>
                @endif
            </div>

            @if($order->status === 'dibatalkan' && $order->alasan_pembatalan)
            <div class="mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                <i class="fas fa-circle-xmark mr-1"></i> {{ $order->alasan_pembatalan }}
            </div>
            @endif
        </div>

        {{-- AKSI --}}
        @if($order->status === 'pengajuan')
        <div class="bg-maroon-50 border border-maroon-200 rounded-[18px] p-5 text-center">
            <p class="text-[0.88rem] text-[#666] mb-4">Pesanan Anda sudah diajukan. Lanjutkan diskusi & konfirmasi harga dengan admin via WhatsApp.</p>
            <a href="{{ $waLink }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-extrabold text-white text-[0.9rem] no-underline hover:opacity-90 transition-all"
               style="background-color:#25D366">
                <i class="fab fa-whatsapp text-lg"></i> Buka WhatsApp Admin
            </a>
            <div class="mt-4 border-t border-gray-200 pt-4 text-left">
                <button type="button" onclick="document.getElementById('batalForm').classList.toggle('hidden')"
                    class="text-[0.82rem] text-red-500 hover:underline">
                    Batalkan Pesanan
                </button>
                <div id="batalForm" class="hidden mt-3">
                    <form method="POST" action="{{ route('catering.cancel', $order->id) }}">
                        @csrf @method('DELETE')
                        <label class="block text-[0.78rem] text-[#888] mb-1 font-semibold uppercase tracking-wider">
                            Alasan Pembatalan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan_pembatalan" rows="3" required maxlength="500"
                            placeholder="Tuliskan alasan pembatalan..."
                            class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-red-300 resize-none mb-2">{{ old('alasan_pembatalan') }}</textarea>
                        @error('alasan_pembatalan')
                        <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                            onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                            class="w-full py-2 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition">
                            Konfirmasi Pembatalan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @elseif($order->status === 'menunggu_pembayaran')
        <div class="bg-maroon-50 border border-maroon-200 rounded-[18px] p-5">
            <p class="text-[0.88rem] text-[#666] mb-3 text-center">Total biaya pesanan Anda sudah ditetapkan oleh admin.</p>

            @if($order->costItems->isNotEmpty())
            <div class="mb-4 bg-white rounded-xl p-4">
                @foreach($order->costItems as $item)
                <div class="flex justify-between text-[0.85rem] py-1.5 border-b border-gray-50 last:border-0">
                    <span class="text-[#666]">{{ $item->label }}</span>
                    <span class="font-semibold text-[#444]">Rp{{ number_format($item->amount, 0, ',', '.') }}</span>
                </div>
                @endforeach
                <div class="flex justify-between font-extrabold text-maroon text-[1rem] pt-2 mt-1">
                    <span>Total</span>
                    <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
            @else
            <p class="text-maroon font-extrabold text-[1.3rem] mb-3 text-center">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
            @endif

            @if($order->admin_notes)
            <p class="text-[0.8rem] text-[#888] mb-3 italic text-center">📝 {{ $order->admin_notes }}</p>
            @endif

            <a href="{{ route('catering.payment', $order->id) }}"
               class="flex items-center justify-center gap-2 px-6 py-3 bg-maroon text-white rounded-xl font-extrabold text-[0.9rem] no-underline hover:bg-maroon-dark transition-all">
                <i class="fas fa-credit-card"></i> Bayar Sekarang
            </a>
        </div>
        @endif

        {{-- RIWAYAT PESANAN --}}
        @if($order->histories->isNotEmpty())
        <div class="bg-white border border-maroon-200 rounded-[18px] p-5 mt-5">
            <h3 class="font-playfair text-[1rem] text-[#1a1a1a] mb-4">Riwayat Pesanan</h3>
            @php
                $historyLabels = [
                    'pengajuan'           => 'Pengajuan Diterima',
                    'menunggu_pembayaran' => 'Total Biaya Ditetapkan',
                    'diproses'            => 'Sedang Diproses',
                    'selesai'             => 'Pesanan Selesai',
                    'dibatalkan'          => 'Pesanan Dibatalkan',
                ];
            @endphp
            <div class="space-y-3">
                @foreach($order->histories as $h)
                <div class="flex gap-3 text-[0.85rem]">
                    <span class="text-[#bbb] text-[0.72rem] whitespace-nowrap mt-0.5 w-20 shrink-0">{{ $h->created_at->format('d M H:i') }}</span>
                    <div>
                        <span class="font-bold text-[#333]">{{ $historyLabels[$h->status] ?? ucfirst($h->status) }}</span>
                        @if($h->notes)<p class="text-[#888] text-[0.78rem] mt-0.5">{{ $h->notes }}</p>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
