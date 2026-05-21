@extends('layouts.app')

@php
    $statusMeta = [
        'pengajuan'           => ['Menunggu Konfirmasi', 'bg-yellow-50 text-yellow-700', 'fa-hourglass-half'],
        'menunggu_pembayaran' => ['Menunggu Pembayaran', 'bg-amber-50 text-orange-700', 'fa-money-bill-wave'],
        'diproses'            => ['Diproses', 'bg-blue-50 text-blue-700', 'fa-utensils'],
        'selesai'             => ['Selesai', 'bg-green-50 text-green-700', 'fa-circle-check'],
        'dibatalkan'          => ['Dibatalkan', 'bg-red-50 text-red-700', 'fa-circle-xmark'],
    ];
@endphp

@section('content')
<div class="px-4 sm:px-10 lg:px-[80px] py-8 lg:py-[50px] min-h-[70vh]">

    <div class="flex items-center justify-between mb-6">
        <h1 class="font-playfair text-[1.7rem] sm:text-[2.2rem] text-[#1a1a1a]">Riwayat Catering</h1>
        <a href="{{ route('catering.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-maroon text-white rounded-xl font-bold text-[0.85rem] no-underline hover:bg-maroon-dark transition-all whitespace-nowrap">
            <i class="fas fa-plus"></i> Pesan Baru
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-20 bg-maroon-50 rounded-2xl">
            <div class="text-5xl mb-3">🍱</div>
            <p class="text-[#888] mb-4">Anda belum punya pesanan catering.</p>
            <a href="{{ route('catering.index') }}" class="text-maroon font-bold no-underline hover:underline">Mulai pesan catering →</a>
        </div>
    @else
    <div class="space-y-4">
        @foreach($orders as $order)
        @php [$label, $color, $icon] = $statusMeta[$order->status] ?? [ucfirst($order->status), 'bg-gray-50 text-gray-700', 'fa-circle']; @endphp
        <div class="bg-white border border-maroon-200 rounded-[16px] p-5">
            <div class="flex items-start justify-between gap-4 mb-3">
                <div>
                    <p class="text-[0.72rem] text-[#999] mb-0.5">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} · {{ $order->created_at->translatedFormat('d M Y') }}</p>
                    <h3 class="font-playfair text-[1.15rem] text-[#1a1a1a]">{{ $order->nama_acara }}</h3>
                    <p class="text-[0.8rem] text-[#888]">{{ $order->package->name ?? 'Custom' }} · {{ $order->jumlah_pax }} pax</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[0.72rem] font-extrabold whitespace-nowrap {{ $color }}">
                    <i class="fas {{ $icon }}"></i> {{ $label }}
                </span>
            </div>

            <div class="flex items-center justify-between gap-3 pt-3 border-t border-maroon-100">
                <div class="text-[0.85rem]">
                    <span class="text-[#999]">Total: </span>
                    <strong class="text-maroon">{{ $order->total ? 'Rp' . number_format($order->total, 0, ',', '.') : 'Menunggu penawaran' }}</strong>
                </div>
                <div class="flex gap-2">
                    @if($order->status === 'menunggu_pembayaran')
                    <a href="{{ route('catering.payment', $order->id) }}"
                       class="px-4 py-2 bg-maroon text-white rounded-lg font-bold text-[0.8rem] no-underline hover:bg-maroon-dark transition-all">
                        <i class="fas fa-credit-card mr-1"></i> Bayar
                    </a>
                    @endif
                    <a href="{{ route('catering.show', $order->id) }}"
                       class="px-4 py-2 border-[1.5px] border-maroon text-maroon rounded-lg font-bold text-[0.8rem] no-underline hover:bg-maroon-50 transition-all">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
