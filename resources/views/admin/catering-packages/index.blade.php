@extends('admin.layouts.app')
@section('title', 'Paket Catering')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="font-playfair text-3xl font-bold text-gray-800">Paket Catering</h2>
        <p class="text-gray-500 mt-1">Kelola paket catering yang ditampilkan ke pelanggan</p>
    </div>
    <a href="{{ route('admin.catering-packages.create') }}"
        class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition bg-[#8B1A1A]">
        + Tambah Paket
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('admin.catering-packages.index') }}" class="flex gap-3 items-center">
        <div class="relative flex-1">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari paket..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800">
        </div>
        <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition bg-[#8B1A1A]">Cari</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <span class="text-sm text-gray-500">{{ $packages->total() }} paket ditemukan</span>
    </div>
    <table class="w-full text-sm">
        <thead class="border-b border-gray-100 bg-gray-50">
            <tr>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Paket</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Harga / Pax</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Min. Pax</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Status</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($packages as $package)
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        @if($package->image)
                            <img src="{{ asset('storage/' . $package->image) }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 text-xl flex-shrink-0">🍱</div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800">{{ $package->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($package->description, 50) }}</p>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-6 font-semibold text-gray-800">Rp{{ number_format($package->price_per_pax, 0, ',', '.') }}</td>
                <td class="py-4 px-6 text-gray-600">{{ $package->min_pax ? $package->min_pax . ' pax' : '—' }}</td>
                <td class="py-4 px-6">
                    @if($package->is_active)
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Nonaktif</span>
                    @endif
                </td>
                <td class="py-4 px-6">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.catering-packages.edit', $package) }}"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition">Edit</a>
                        <form method="POST" action="{{ route('admin.catering-packages.destroy', $package) }}"
                              onsubmit="return confirm('Hapus paket ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center">
                    <div class="text-gray-300 text-4xl mb-3">🍱</div>
                    <p class="text-gray-400 text-sm">Belum ada paket catering</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $packages->appends(['search' => $search])->links() }}</div>
</div>
@endsection
