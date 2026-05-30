@extends('admin.layouts.app')
@section('title', 'Edit Paket Catering')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.catering-packages.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Paket Catering
    </a>
    <h2 class="font-playfair text-3xl font-bold text-gray-800 mt-3">Edit Paket Catering</h2>
</div>

<form method="POST" action="{{ route('admin.catering-packages.update', $package) }}" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Informasi Paket</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nama Paket <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition resize-none">{{ old('description', $package->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Harga (Referensi)</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Harga / Pax (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_pax" value="{{ old('price_per_pax', $package->price_per_pax) }}" required min="0"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Minimum Pax</label>
                    <input type="number" name="min_pax" value="{{ old('min_pax', $package->min_pax) }}" min="1"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Maksimum Pax</label>
                    <input type="number" name="max_pax" value="{{ old('max_pax', $package->max_pax) }}" min="1"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition">
                    @error('max_pax')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Foto Paket</h3>
            <div onclick="document.getElementById('inputGambar').click()"
                class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center cursor-pointer hover:border-red-300 hover:bg-red-50 transition">
                <div id="placeholder" class="{{ $package->image ? 'hidden' : '' }}">
                    <p class="text-sm font-semibold text-gray-500">Klik untuk ganti foto</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP maks. 2MB</p>
                </div>
                <img id="previewImg" src="{{ $package->image ? asset('storage/' . $package->image) : '#' }}" alt="preview"
                    class="{{ $package->image ? '' : 'hidden' }} mx-auto max-h-48 rounded-xl object-cover">
            </div>
            <input type="file" name="image" accept="image/*" id="inputGambar" class="hidden" onchange="previewGambar(event)">
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-5 pb-3 border-b border-gray-100">Status</h3>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}
                    class="w-5 h-5 rounded border-gray-300 text-[#8B1A1A] focus:ring-red-200">
                <span class="text-sm font-semibold text-gray-700">Tampilkan paket ke pelanggan</span>
            </label>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <button type="submit"
                class="w-full py-3 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition mb-3 bg-[#8B1A1A]">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.catering-packages.index') }}"
                class="w-full block text-center py-3 rounded-xl border border-gray-200 text-sm font-semibold hover:bg-gray-50 transition">
                Batal
            </a>
        </div>
    </div>
</div>
</form>

@push('scripts')
<script>
function previewGambar(event) {
    const file = event.target.files[0];
    if (!file) return;
    const img = document.getElementById('previewImg');
    img.src = URL.createObjectURL(file);
    img.classList.remove('hidden');
    document.getElementById('placeholder').classList.add('hidden');
}
</script>
@endpush
@endsection
