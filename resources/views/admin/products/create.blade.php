@extends('admin.layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Produk
    </a>
    <h2 class="font-playfair text-3xl font-bold text-gray-800 mt-3">Tambah Produk</h2>
    <p class="text-gray-500 mt-1">Isi informasi produk baru</p>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
@csrf
<div class="grid grid-cols-3 gap-6">

    {{-- KOLOM KIRI --}}
    <div class="col-span-2 space-y-6">

        @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        {{-- Info Dasar --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Informasi Dasar</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="contoh: Dimsum Kukus Spesial"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4" placeholder="Deskripsikan produk Anda..."
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition resize-none">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Harga & Stok --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Harga & Stok</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-semibold">Rp</span>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0" placeholder="0"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition">
                </div>
            </div>
        </div>

        {{-- Foto Produk --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Foto Produk <span class="text-red-500">*</span></h3>
            <div id="dropzone" onclick="document.getElementById('inputGambar').click()"
                class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center cursor-pointer hover:border-red-300 hover:bg-red-50 transition">
                <div id="dropzonePlaceholder">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm font-semibold text-gray-500">Klik untuk upload foto</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP maks. 2MB</p>
                </div>
                <img id="previewImg" src="#" alt="preview" class="hidden mx-auto max-h-48 rounded-xl object-cover">
            </div>
            <input type="file" name="image" accept="image/*" id="inputGambar" class="hidden" onchange="previewGambar(event)">
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-span-1 space-y-6">

        {{-- Kategori, Status & Badge --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Kategori & Status</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition bg-white">
                        <option value="ready"     {{ old('status') == 'ready'     ? 'selected' : '' }}>✅ Ready Stock</option>
                        <option value="habis"     {{ old('status') == 'habis'     ? 'selected' : '' }}>❌ Habis</option>
                    </select>
                </div>
                <div>
    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
        Badge Produk
        <span class="text-gray-400 font-normal normal-case">({{ $badgeCount }}/4 terpakai)</span>
    </label>

    @if($badgeCount >= 4)
        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-xl text-xs text-yellow-700 mb-2">
            ⚠️ Badge sudah penuh (4/4). Hapus badge produk lain dulu untuk mengatur badge di sini.
        </div>
        <input type="hidden" name="badge" value="">
        <select disabled
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm bg-gray-100 text-gray-400 cursor-not-allowed">
            <option>-- Tidak Ada (Penuh) --</option>
        </select>
    @else
        <select name="badge"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition bg-white">
            <option value="">-- Tidak Ada --</option>
            <option value="new"      {{ old('badge') == 'new'      ? 'selected' : '' }}>New</option>
            <option value="terlaris" {{ old('badge') == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
            <option value="unggulan" {{ old('badge') == 'unggulan' ? 'selected' : '' }}>Unggulan</option>
        </select>
    @endif

    @error('badge')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <button type="submit"
                class="w-full py-3 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition mb-3 bg-[#8B1A1A]">
                Simpan Produk
            </button>
            <a href="{{ route('admin.products.index') }}"
                class="w-full block text-center py-3 rounded-xl border border-gray-200 text-sm font-semibold hover:bg-gray-50 transition">
                Batal
            </a>
        </div>

    </div>
</div>
</form>

<script>
function previewGambar(event) {
    const img = document.getElementById('previewImg');
    const placeholder = document.getElementById('dropzonePlaceholder');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.classList.remove('hidden');
    placeholder.classList.add('hidden');
}
</script>
@endsection