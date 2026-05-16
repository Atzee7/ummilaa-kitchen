@extends('admin.layouts.app')

@section('title', 'Pengaturan Toko')

@section('content')
<div class="max-w-xl">
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
</div>
@endsection
