@extends('admin.layouts.app')
@section('title', 'Pesanan Catering')

@php
    $statusMeta = [
        'pengajuan'           => ['Pengajuan', 'bg-yellow-100 text-yellow-700'],
        'menunggu_pembayaran' => ['Menunggu Pembayaran', 'bg-amber-100 text-amber-700'],
        'diproses'            => ['Diproses', 'bg-blue-100 text-blue-700'],
        'selesai'             => ['Selesai', 'bg-green-100 text-green-700'],
        'dibatalkan'          => ['Dibatalkan', 'bg-red-100 text-red-700'],
    ];
    $tabs = [
        'semua'               => 'Semua',
        'pengajuan'           => 'Pengajuan',
        'menunggu_pembayaran' => 'Menunggu Bayar',
        'diproses'            => 'Diproses',
        'selesai'             => 'Selesai',
        'dibatalkan'          => 'Dibatalkan',
    ];
@endphp

@section('content')
<div class="mb-8">
    <h2 class="font-playfair text-3xl font-bold text-gray-800">Pesanan Catering</h2>
    <p class="text-gray-500 mt-1">Kelola pengajuan & pembayaran catering pelanggan</p>
</div>

{{-- Tabs status --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach($tabs as $key => $label)
    <a href="{{ route('admin.catering-orders.index', ['status' => $key]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition border
           {{ $status === $key ? 'bg-[#8B1A1A] text-white border-[#8B1A1A]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
        {{ $label }}
        <span class="ml-1 text-xs {{ $status === $key ? 'text-white/80' : 'text-gray-400' }}">{{ $counts[$key] ?? 0 }}</span>
    </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="border-b border-gray-100 bg-gray-50">
            <tr>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">ID</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Acara</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Pemesan</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Tanggal Acara</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Pax</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Total</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Status</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            @php [$label, $color] = $statusMeta[$order->status] ?? [ucfirst($order->status), 'bg-gray-100 text-gray-700']; @endphp
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="py-4 px-6 font-semibold text-gray-700">#{{ $order->id }}</td>
                <td class="py-4 px-6">
                    <p class="font-semibold text-gray-800">{{ $order->nama_acara }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $order->package->name ?? 'Custom' }}</p>
                </td>
                <td class="py-4 px-6 text-gray-600">{{ $order->nama_pemesan }}</td>
                <td class="py-4 px-6 text-gray-600">{{ $order->tanggal_acara->translatedFormat('d M Y') }}</td>
                <td class="py-4 px-6 text-gray-600">{{ $order->jumlah_pax }}</td>
                <td class="py-4 px-6 font-semibold text-gray-800">{{ $order->total ? 'Rp' . number_format($order->total, 0, ',', '.') : '—' }}</td>
                <td class="py-4 px-6"><span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $color }}">{{ $label }}</span></td>
                <td class="py-4 px-6">
                    <a href="{{ route('admin.catering-orders.show', $order->id) }}"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-16 text-center">
                    <div class="text-gray-300 text-4xl mb-3">🍱</div>
                    <p class="text-gray-400 text-sm">Belum ada pesanan catering</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $orders->links() }}</div>
</div>
@endsection
