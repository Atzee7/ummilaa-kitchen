@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="font-playfair text-3xl font-bold text-gray-800">Dashboard</h2>
    <p class="text-gray-500 mt-1">Selamat datang kembali, {{ auth()->user()->name }}!</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-gray-500">Total Produk</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: rgba(139,26,26,0.1);">
                <svg class="w-5 h-5" style="color: #8B1A1A;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $totalProduk }}</p>
        <p class="text-xs text-gray-400 mt-1">Produk aktif</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-gray-500">Total Pesanan</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $totalPesanan }}</p>
        <p class="text-xs text-gray-400 mt-1">Semua pesanan</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-gray-500">Total Pengguna</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-50">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $totalPengguna }}</p>
        <p class="text-xs text-gray-400 mt-1">Pelanggan terdaftar</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-gray-500">Total Penjualan</p>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-yellow-50">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Dari pesanan selesai</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-playfair text-xl font-bold text-gray-800">Pesanan Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold hover:underline" style="color: #8B1A1A;">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-gray-500 font-semibold">ID</th>
                    <th class="text-left py-3 px-4 text-gray-500 font-semibold">Pelanggan</th>
                    <th class="text-left py-3 px-4 text-gray-500 font-semibold">Total</th>
                    <th class="text-left py-3 px-4 text-gray-500 font-semibold">Metode</th>
                    <th class="text-left py-3 px-4 text-gray-500 font-semibold">Status</th>
                    <th class="text-left py-3 px-4 text-gray-500 font-semibold">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesananTerbaru as $order)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-3 px-4 font-semibold text-gray-700">#{{ $order->id }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $order->user->name ?? '-' }}</td>
                    <td class="py-3 px-4 font-semibold">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-gray-600 uppercase">{{ $order->metode_pembayaran }}</td>
                    <td class="py-3 px-4">
                        @php
                            $sc = match($order->status) {
                                'pending'    => 'bg-yellow-100 text-yellow-700',
                                'diproses'   => 'bg-blue-100 text-blue-700',
                                'dikirim'    => 'bg-purple-100 text-purple-700',
                                'selesai'    => 'bg-green-100 text-green-700',
                                'dibatalkan' => 'bg-red-100 text-red-700',
                                default      => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $sc }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td class="py-3 px-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-gray-400">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection