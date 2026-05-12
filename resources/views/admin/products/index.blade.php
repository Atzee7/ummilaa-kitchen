@extends('admin.layouts.app')
@section('title', 'Produk')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="font-playfair text-3xl font-bold text-gray-800">Produk</h2>
        <p class="text-gray-500 mt-1">Kelola semua produk Ummilaa Kitchen</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
        class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition"
        style="background-color: #8B1A1A;">
        + Tambah Produk
    </a>
</div>

{{-- Filter & Search --}}
<div class="bg-white rounded-2xl shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-center">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-3 items-center w-full">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari produk..."
                id="searchInput"
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800">
        </div>

        <select name="category_id" onchange="this.form.submit()"
            class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 bg-white min-w-[160px]">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <button type="submit"
            class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition"
            style="background-color: #8B1A1A;">
            Cari
        </button>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <span class="text-sm text-gray-500">{{ $products->total() }} produk ditemukan</span>
        @if($search || $categoryId)
        <span class="text-xs text-gray-400">
            Filter aktif: {{ $search ? '"'.$search.'"' : '' }} {{ $categoryId ? '| '.$categories->find($categoryId)?->name : '' }}
        </span>
        @endif
    </div>
    <table class="w-full text-sm">
        <thead class="border-b border-gray-100 bg-gray-50">
            <tr>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Produk</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Kategori</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Harga</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Stok</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Status</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Badge</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                        @if($product->image && Str::startsWith($product->image, 'products/'))
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                        @elseif($product->image)
                            <img src="{{ $product->image }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 text-xl flex-shrink-0">🍽️</div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($product->description, 40) }}</p>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">
                        {{ $product->categoryRelation->name ?? $product->category }}
                    </span>
                </td>
                <td class="py-4 px-6 font-semibold text-gray-800">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="py-4 px-6">
                    <span class="{{ $product->stock == 0 ? 'text-red-500 font-semibold' : 'text-gray-600' }}">
                        {{ $product->stock }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    @php
                        $sc = match($product->status) {
                            'ready'     => 'bg-green-100 text-green-700',
                            'habis'     => 'bg-red-100 text-red-700',
                            default     => 'bg-gray-100 text-gray-700',
                        };
                        $sl = match($product->status) {
                            'ready'     => 'Ready Stock',
                            'habis'     => 'Habis',
                            default     => $product->status,
                        };
                    @endphp
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc }}">{{ $sl }}</span>
                </td>
                <td class="py-4 px-6">
                    @if($product->badge)
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $product->badge == 'new' ? 'bg-blue-100 text-blue-700' : ($product->badge == 'terlaris' ? 'bg-orange-100 text-orange-700' : 'bg-pink-100 text-pink-700') }}">
                            {{ $product->badge == 'new' ? 'New' : ($product->badge == 'terlaris' ? 'Terlaris' : 'Unggulan') }}
                        </span>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="py-4 px-6">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                              onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-16 text-center">
                    <div class="text-gray-300 text-4xl mb-3">🍽️</div>
                    <p class="text-gray-400 text-sm">Tidak ada produk ditemukan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $products->appends(['search' => $search, 'category_id' => $categoryId])->links() }}</div>
</div>

@push('scripts')
<script>
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            this.closest('form').submit();
        }, 500);
    });
</script>
@endpush
@endsection