@extends('admin.layouts.app')

@section('title', 'Pengaturan Toko')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-[#1e1e2d] mb-1">Status Toko</h2>
        <p class="text-sm text-gray-400 mb-6">Atur apakah toko sedang buka atau tutup. Ketika tutup, pelanggan tidak dapat melakukan checkout.</p>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            {{-- Toggle --}}
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $storeOpen === '1' ? 'bg-green-100' : 'bg-red-100' }}">
                        <svg class="w-5 h-5 {{ $storeOpen === '1' ? 'text-green-600' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-[#1e1e2d]">Toko</div>
                        <div class="text-xs {{ $storeOpen === '1' ? 'text-green-600' : 'text-red-500' }} font-semibold">
                            {{ $storeOpen === '1' ? 'Sedang Buka' : 'Sedang Tutup' }}
                        </div>
                    </div>
                </div>

                {{-- Toggle switch --}}
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="store_open" value="1"
                        class="sr-only peer"
                        {{ $storeOpen === '1' ? 'checked' : '' }}
                        onchange="this.form.submit()">
                    <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                        peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-0.5 after:left-0.5
                        after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all after:shadow-sm
                        peer-checked:bg-green-500 transition-colors duration-200"></div>
                </label>
            </div>

        </form>
    </div>

    {{-- Info box --}}
    <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-700 flex gap-3">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            Saat toko <strong>tutup</strong>, pelanggan masih bisa melihat produk dan katalog, namun tidak dapat melakukan checkout. Notifikasi toko tutup akan ditampilkan di semua halaman.
        </div>
    </div>

    {{-- Zona Berbahaya --}}
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-red-200 p-6">
        <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-red-700 mb-1">Zona Berbahaya</h2>
                <p class="text-sm text-gray-500">Aksi di bawah ini bersifat permanen dan tidak dapat dibatalkan. Pastikan Anda benar-benar yakin sebelum melanjutkan.</p>
            </div>
        </div>

        <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl border border-red-100">
            <div>
                <div class="text-sm font-semibold text-[#1e1e2d]">Hapus Semua Pesanan</div>
                <div class="text-xs text-gray-500 mt-0.5">Menghapus seluruh data pesanan produk (item & testimoni) serta pesanan catering.</div>
            </div>
            <button type="button" onclick="openHapusModal()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Hapus Semua
            </button>
        </div>
    </div>
</div>

{{-- Modal konfirmasi hapus semua pesanan --}}
<div id="hapusModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4"
     onclick="if(event.target===this) closeHapusModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-red-700 mb-2">Konfirmasi Hapus Semua Pesanan</h3>
        <p class="text-sm text-gray-600 mb-4">
            Tindakan ini akan menghapus <strong>seluruh pesanan produk</strong> (beserta item & testimoni) <strong>dan pesanan catering</strong> secara permanen.
            Untuk melanjutkan, ketik <code class="bg-gray-100 px-2 py-0.5 rounded text-red-700 font-mono">HAPUS SEMUA PESANAN</code> di kolom bawah.
        </p>

        <form method="POST" action="{{ route('admin.settings.orders.destroyAll') }}">
            @csrf
            @method('DELETE')
            <input type="text" name="confirmation" id="confirmationInput"
                autocomplete="off"
                oninput="onConfirmInput(this.value)"
                placeholder="Ketik HAPUS SEMUA PESANAN"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeHapusModal()"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit" id="hapusSubmitBtn" disabled
                    class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg transition-colors disabled:bg-red-300 disabled:cursor-not-allowed enabled:hover:bg-red-700">
                    Hapus Semua
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const CONFIRM_PHRASE = 'HAPUS SEMUA PESANAN';
    function openHapusModal() {
        const modal = document.getElementById('hapusModal');
        document.getElementById('confirmationInput').value = '';
        document.getElementById('hapusSubmitBtn').disabled = true;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('confirmationInput').focus(), 50);
    }
    function closeHapusModal() {
        const modal = document.getElementById('hapusModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    function onConfirmInput(val) {
        document.getElementById('hapusSubmitBtn').disabled = (val !== CONFIRM_PHRASE);
    }
</script>
@endpush
