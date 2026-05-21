@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-10 lg:px-[80px] py-8 lg:py-[50px] min-h-[70vh]">

    <div class="max-w-2xl mx-auto">
        <a href="{{ route('catering.index') }}" class="inline-flex items-center gap-2 text-[0.85rem] text-[#888] hover:text-maroon no-underline mb-4">
            <i class="fas fa-arrow-left"></i> Kembali ke Catering
        </a>
        <h1 class="font-playfair text-[1.7rem] sm:text-[2rem] text-[#1a1a1a] mb-1">Form Pemesanan Catering</h1>
        <p class="text-[#888] text-[0.88rem] mb-6">Isi detail acara Anda. Setelah submit, Anda akan diarahkan ke WhatsApp admin untuk konfirmasi & negosiasi harga.</p>

        @if($errors->any())
        <div class="p-4 bg-pink-100 border border-red-300 text-red-700 rounded-xl text-sm mb-5">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('catering.store') }}" class="bg-white border border-maroon-200 rounded-[18px] p-5 sm:p-7 space-y-5">
            @csrf

            <div>
                <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Paket Catering</label>
                <select name="catering_package_id"
                    class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon bg-white">
                    <option value="">Custom (tanpa paket)</option>
                    @foreach($packages as $p)
                        <option value="{{ $p->id }}"
                            {{ (string) old('catering_package_id', $package?->id) === (string) $p->id ? 'selected' : '' }}>
                            {{ $p->name }} — Rp{{ number_format($p->price_per_pax, 0, ',', '.') }}/pax
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Nama Acara <span class="text-red-500">*</span></label>
                <input type="text" name="nama_acara" value="{{ old('nama_acara') }}" required placeholder="contoh: Syukuran Pernikahan"
                    class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Tanggal Acara <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_acara" value="{{ old('tanggal_acara') }}" required min="{{ now()->format('Y-m-d') }}"
                        class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Jumlah Pax (porsi) <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_pax" value="{{ old('jumlah_pax') }}" required min="1" placeholder="contoh: 50"
                        class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
                </div>
            </div>

            <div>
                <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Lokasi Acara <span class="text-red-500">*</span></label>
                <textarea name="lokasi_acara" rows="2" required placeholder="Alamat lengkap lokasi acara"
                    class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon resize-none">{{ old('lokasi_acara') }}</textarea>
            </div>

            <div>
                <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" placeholder="Permintaan khusus, menu, dll (opsional)"
                    class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon resize-none">{{ old('catatan') }}</textarea>
            </div>

            <div class="border-t border-maroon-200 pt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Nama Pemesan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan', auth()->user()->name) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">No. WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', auth()->user()->no_telepon) }}" required placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
                </div>
            </div>

            <button type="submit"
                class="w-full py-3.5 rounded-xl font-extrabold text-[0.95rem] text-white flex items-center justify-center gap-2 hover:opacity-90 transition-all"
                style="background-color:#25D366">
                <i class="fab fa-whatsapp text-lg"></i> Pesan via WhatsApp
            </button>
        </form>
    </div>

</div>
@endsection
