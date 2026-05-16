@extends('admin.layouts.app')
@section('title', 'Detail Pengguna')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Pengguna
    </a>
    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
          onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.')">
        @csrf @method('DELETE')
        <button type="submit"
            class="px-4 py-2 text-sm font-semibold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition">
            Hapus Akun
        </button>
    </form>
</div>

<div class="grid grid-cols-3 gap-6">
    {{-- Info Pengguna --}}
    <div class="col-span-1">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-2xl font-bold mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h3 class="font-playfair text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">No. Telepon</span>
                    <span class="font-semibold text-gray-700">{{ $user->no_telepon ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tgl Lahir</span>
                    <span class="font-semibold text-gray-700">{{ $user->tanggal_lahir ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kode Pos</span>
                    <span class="font-semibold text-gray-700">{{ $user->kode_pos ?? '-' }}</span>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-gray-500">Alamat</span>
                    <p class="font-semibold text-gray-700 mt-1">{{ $user->alamat ?? '-' }}</p>
                </div>
                @if($user->detail_alamat)
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-gray-500">Detail Alamat</span>
                    <p class="font-semibold text-gray-700 mt-1">{{ $user->detail_alamat }}</p>
                </div>
                @endif
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-gray-500">Terdaftar sejak</span>
                    <p class="font-semibold text-gray-700 mt-1">{{ $user->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mt-4">
            <h4 class="font-semibold text-gray-700 mb-4">Statistik Belanja</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Pesanan</span>
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">{{ $orders->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Pesanan Selesai</span>
                    <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold">{{ $orders->where('status','selesai')->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Belanja</span>
                    <span class="font-bold text-gray-800 text-sm">Rp{{ number_format($totalBelanja, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Pesanan --}}
    <div class="col-span-2">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Riwayat Pesanan</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="border-b border-gray-100">
                    <tr>
                        <th class="text-left py-3 px-6 text-gray-500 font-semibold">ID</th>
                        <th class="text-left py-3 px-6 text-gray-500 font-semibold">Total</th>
                        <th class="text-left py-3 px-6 text-gray-500 font-semibold">Metode</th>
                        <th class="text-left py-3 px-6 text-gray-500 font-semibold">Status</th>
                        <th class="text-left py-3 px-6 text-gray-500 font-semibold">Tanggal</th>
                        <th class="text-left py-3 px-6 text-gray-500 font-semibold">Aksi</th>
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
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-6 font-semibold text-gray-700">#{{ $order->id }}</td>
                        <td class="py-3 px-6 font-semibold">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 text-gray-500 text-xs uppercase font-semibold">{{ $order->metode_pembayaran }}</td>
                        <td class="py-3 px-6">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $sc }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="py-3 px-6 text-gray-500 text-xs">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-6">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-8 text-center text-gray-400">Belum ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection