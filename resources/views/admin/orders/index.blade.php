@extends('admin.layouts.app')
@section('title', 'Pesanan')

@section('content')
<div class="mb-8">
    <h2 class="font-playfair text-3xl font-bold text-gray-800">Pesanan</h2>
    <p class="text-gray-500 mt-1">Kelola semua pesanan pelanggan</p>
</div>

@php
    $tabs = ['semua','pending','diproses','dikirim','selesai','dibatalkan'];
    $tabColor = [
        'semua'      => 'bg-gray-100 text-gray-700',
        'pending'    => 'bg-yellow-100 text-yellow-700',
        'diproses'   => 'bg-blue-100 text-blue-700',
        'dikirim'    => 'bg-purple-100 text-purple-700',
        'selesai'    => 'bg-green-100 text-green-700',
        'dibatalkan' => 'bg-red-100 text-red-700',
    ];
@endphp

<div class="flex flex-wrap gap-2 mb-6">
    @foreach($tabs as $tab)
    <a href="{{ route('admin.orders.index', ['status' => $tab]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition
              {{ $status === $tab ? $tabColor[$tab] . ' ring-2 ring-offset-1 ring-gray-300' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
        {{ ucfirst($tab) }}
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs bg-black bg-opacity-10">{{ $counts[$tab] }}</span>
    </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="border-b border-gray-100">
            <tr>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">ID</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Pelanggan</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Total</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Pengiriman</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Pembayaran</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Status</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Tanggal</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php
                $sc = match($order->status) {
                    'pending'    => 'bg-yellow-100 text-yellow-700',
                    'diproses'   => 'bg-blue-100 text-blue-700',
                    'dikirim'    => 'bg-purple-100 text-purple-700',
                    'selesai'    => 'bg-green-100 text-green-700',
                    'dibatalkan' => 'bg-red-100 text-red-700',
                    default      => 'bg-gray-100 text-gray-700',
                };
                $isDelivery = ($order->metode_pengiriman ?? 'delivery') === 'delivery';
            @endphp
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="py-4 px-6 font-bold text-gray-700">#{{ $order->id }}</td>
                <td class="py-4 px-6">
                    <p class="font-semibold text-gray-800">{{ $order->user->name ?? $order->nama_penerima ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
                </td>
                <td class="py-4 px-6 font-bold text-[#8B1A1A]">
                    Rp{{ number_format($order->total, 0, ',', '.') }}
                </td>
                <td class="py-4 px-6">
                    @if($isDelivery)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                            Delivery
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Ambil Sendiri
                        </span>
                    @endif
                </td>
                <td class="py-4 px-6 text-gray-600 text-xs font-semibold uppercase">{{ $order->metode_pembayaran }}</td>
                <td class="py-4 px-6">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc }}">{{ ucfirst($order->status) }}</span>
                </td>
                <td class="py-4 px-6 text-gray-400 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</td>
                <td class="py-4 px-6">
                    <a href="{{ route('admin.orders.show', $order->id) }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="py-16 text-center text-gray-400">
                <div class="text-4xl mb-3">📭</div>
                <p class="font-semibold">Tidak ada pesanan</p>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $orders->appends(['status' => $status])->links() }}</div>
</div>
@endsection