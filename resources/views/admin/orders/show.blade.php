@extends('admin.layouts.app')
@section('title', 'Detail Pesanan #' . $order->id)

@section('content')

@php
    $sc = match($order->status) {
        'pending'      => 'bg-yellow-100 text-yellow-700',
        'diproses'     => 'bg-blue-100 text-blue-700',
        'dikirim'      => 'bg-purple-100 text-purple-700',
        'siap_diambil' => 'bg-orange-100 text-orange-700',
        'selesai'      => 'bg-green-100 text-green-700',
        'dibatalkan'   => 'bg-red-100 text-red-700',
        default        => 'bg-gray-100 text-gray-700',
    };
    $isDelivery = ($order->metode_pengiriman ?? 'delivery') === 'delivery';
    $statusLabel = $order->status === 'siap_diambil' ? 'Siap Diambil' : ucfirst($order->status);
@endphp

{{-- HEADER --}}
<div class="mb-8">
    <a href="{{ route('admin.orders.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Pesanan
    </a>
    <div class="flex items-start justify-between">
        <div>
            <h2 class="font-playfair text-3xl font-bold text-gray-800">
                Detail Pesanan <span class="text-[#8B1A1A]">#{{ $order->id }}</span>
            </h2>
            <p class="text-gray-400 text-sm mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Badge pengiriman --}}
            @if($isDelivery)
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-blue-50 text-blue-700 border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    Delivery
                </span>
            @else
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-orange-50 text-orange-700 border border-orange-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Ambil Sendiri
                </span>
            @endif
            {{-- Badge status --}}
            <span class="px-4 py-2 rounded-xl text-sm font-bold {{ $sc }}">{{ $statusLabel }}</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- KIRI --}}
    <div class="xl:col-span-2 flex flex-col gap-6">

        {{-- INFO PEMESAN --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100">
                Informasi Pemesan
            </h3>
            <div class="grid grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Penerima</p>
                    <p class="font-bold text-gray-800 text-base">{{ $order->nama_penerima }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">No. Telepon</p>
                    <p class="font-bold text-gray-800 text-base">{{ $order->no_telepon }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Akun</p>
                    <p class="font-semibold text-gray-700">{{ $order->user->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Metode Pembayaran</p>
                    <p class="font-semibold text-gray-700 uppercase">{{ $order->metode_pembayaran }}</p>
                </div>
            </div>
        </div>

        {{-- PENGIRIMAN --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100">
                Detail Pengiriman
            </h3>

            {{-- Tipe pengiriman --}}
            <div class="flex items-center gap-4 mb-5">
                @if($isDelivery)
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-800">Delivery</p>
                    <p class="text-xs text-gray-400 mt-0.5">Diantar ke alamat pelanggan · Ongkir Rp{{ number_format($order->ongkir, 0, ',', '.') }}</p>
                </div>
                @else
                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-800">Ambil Sendiri</p>
                    <p class="text-xs text-gray-400 mt-0.5">Pelanggan ambil langsung di outlet · Gratis</p>
                </div>
                @endif
            </div>

            {{-- Alamat (hanya jika delivery) --}}
            @if($isDelivery)
            <div class="bg-gray-50 rounded-xl p-4 text-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                    <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Alamat Pengiriman
                </p>
                <p class="font-semibold text-gray-700 leading-relaxed">{{ $order->alamat }}</p>
                @if($order->detail_alamat)
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Detail Alamat</p>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $order->detail_alamat }}</p>
                </div>
                @endif
            </div>
            @else
            <div class="bg-orange-50 rounded-xl p-4 text-sm border border-orange-100">
                <p class="text-xs font-semibold text-orange-400 uppercase tracking-wider mb-1">Lokasi Outlet</p>
                <p class="font-semibold text-orange-800">Ummilaa Kitchen, Malang, Jawa Timur</p>
                <p class="text-xs text-orange-400 mt-1">Pelanggan akan datang langsung ke outlet</p>
            </div>
            @endif
        </div>

        {{-- CATATAN --}}
        @if($order->catatan)
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                Catatan dari Pelanggan
            </h3>
            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 flex gap-3">
                <div class="text-xl flex-shrink-0">💬</div>
                <p class="text-sm text-gray-700 leading-relaxed italic">"{{ $order->catatan }}"</p>
            </div>
        </div>
        @endif

        {{-- PRODUK --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                Produk Dipesan
                <span class="text-sm font-normal text-gray-400 ml-2">{{ $order->items->count() }} item</span>
            </h3>
            <div class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 py-4">
                    @if($item->product && $item->product->image)
                        <img src="{{ Str::startsWith($item->product->image, 'products/') ? asset('storage/' . $item->product->image) : $item->product->image }}"
                             class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center text-2xl flex-shrink-0">🍽️</div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800">{{ $item->product->name ?? 'Produk dihapus' }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $item->quantity }} pcs × Rp{{ number_format($item->price, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="font-bold text-gray-800 flex-shrink-0">
                        Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>

            {{-- Ringkasan harga --}}
            <div class="border-t border-gray-100 mt-2 pt-4 space-y-2.5 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-semibold text-gray-700">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Ongkos Kirim</span>
                    <span class="font-semibold {{ $order->ongkir > 0 ? 'text-gray-700' : 'text-green-600' }}">
                        {{ $order->ongkir > 0 ? 'Rp' . number_format($order->ongkir, 0, ',', '.') : 'Gratis' }}
                    </span>
                </div>
                <div class="flex justify-between font-bold text-base pt-3 border-t border-gray-100">
                    <span class="text-gray-800">Total Pembayaran</span>
                    <span class="text-[#8B1A1A]">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- KANAN --}}
    <div class="flex flex-col gap-6">

        {{-- UPDATE STATUS --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4">Status Pesanan</h3>
            <div class="mb-4">
                <p class="text-xs text-gray-400 mb-2 font-semibold uppercase tracking-wider">Status saat ini</p>
                <span class="px-3 py-1.5 rounded-full text-sm font-bold {{ $sc }}">{{ $statusLabel }}</span>
            </div>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                @csrf @method('PATCH')
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ubah Status</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800 bg-white">
                        <option value="pending"      {{ $order->status === 'pending'      ? 'selected' : '' }}>Pending</option>
                        <option value="diproses"     {{ $order->status === 'diproses'     ? 'selected' : '' }}>Diproses</option>
                        @if($isDelivery)
                        <option value="dikirim"      {{ $order->status === 'dikirim'      ? 'selected' : '' }}>Dikirim</option>
                        @else
                        <option value="siap_diambil" {{ $order->status === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                        @endif
                        <option value="selesai"      {{ $order->status === 'selesai'      ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan"   {{ $order->status === 'dibatalkan'   ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <button type="submit"
                    class="w-full py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition bg-[#8B1A1A]">
                    Perbarui Status
                </button>
            </form>

        </div>

        {{-- TIMELINE --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4">Alur Status</h3>
            @php
                $steps = $isDelivery
                    ? [['key' => 'pending', 'label' => 'Pending'], ['key' => 'diproses', 'label' => 'Diproses'], ['key' => 'dikirim', 'label' => 'Dikirim'], ['key' => 'selesai', 'label' => 'Selesai']]
                    : [['key' => 'pending', 'label' => 'Pending'], ['key' => 'diproses', 'label' => 'Diproses'], ['key' => 'siap_diambil', 'label' => 'Siap Diambil'], ['key' => 'selesai', 'label' => 'Selesai']];
                $stepKeys = array_column($steps, 'key');
                $currentIndex = array_search($order->status, $stepKeys);
            @endphp
            <div class="relative">
                @foreach($steps as $i => $step)
                @php
                    $done = $currentIndex !== false && $i <= $currentIndex;
                    $isCurrent = $order->status === $step['key'];
                @endphp
                <div class="flex items-start gap-3 {{ !$loop->last ? 'mb-4' : '' }}">
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $done ? 'bg-[#8B1A1A] text-white' : 'bg-gray-100 text-gray-400' }}">
                            {{ $done ? '✓' : ($i + 1) }}
                        </div>
                        @if(!$loop->last)
                        <div class="w-0.5 h-6 mt-1 {{ $done && $currentIndex > $i ? 'bg-[#8B1A1A] opacity-100' : 'bg-gray-300 opacity-20' }}"></div>
                        @endif
                    </div>
                    <div class="pt-1">
                        <p class="text-sm {{ $isCurrent ? 'font-bold text-gray-800' : ($done ? 'font-semibold text-gray-600' : 'text-gray-400') }}">
                            {{ $step['label'] }}
                            @if($isCurrent)
                            <span class="ml-1 text-xs font-normal px-2 py-0.5 rounded-full bg-[#fdf0f0] text-[#8B1A1A]">sekarang</span>
                            @endif
                        </p>
                    </div>
                </div>
                @endforeach

                @if($order->status === 'dibatalkan')
                <div class="flex items-center gap-3 mt-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold bg-red-100 text-red-600 flex-shrink-0">✕</div>
                    <p class="text-sm font-bold text-red-600">Dibatalkan
                        <span class="ml-1 text-xs font-normal px-2 py-0.5 rounded-full bg-red-50 text-red-500">sekarang</span>
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- RINGKASAN CEPAT --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4">Ringkasan</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-semibold">Order ID</span>
                    <span class="font-bold text-gray-800">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-semibold">Tanggal</span>
                    <span class="font-semibold text-gray-700">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-semibold">Jam</span>
                    <span class="font-semibold text-gray-700">{{ $order->created_at->format('H:i') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-semibold">Pengiriman</span>
                    <span class="font-bold {{ $isDelivery ? 'text-blue-600' : 'text-orange-600' }}">
                        {{ $isDelivery ? 'Delivery' : 'Ambil Sendiri' }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-semibold">Jumlah Item</span>
                    <span class="font-bold text-gray-800">{{ $order->items->count() }} produk</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-400 font-semibold">Total</span>
                    <span class="font-bold text-base text-[#8B1A1A]">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- POPUP KONFIRMASI WHATSAPP (show page) --}}
<div id="wa-confirm-modal-show" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-80 text-center mx-4">
        <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color:#25D366">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-base mb-1">Kirim Konfirmasi WhatsApp?</h3>
        <p class="text-sm text-gray-500 mb-5">Beritahu pembeli bahwa pesanannya sedang diproses.</p>
        <div class="flex gap-3">
            <button id="wa-show-no"
                class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                Tidak
            </button>
            <button id="wa-show-yes"
                class="flex-1 py-2.5 rounded-xl text-white text-sm font-bold hover:opacity-90 transition"
                style="background-color:#25D366">
                Kirim WA
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF_TOKEN_SHOW = '{{ csrf_token() }}';
const WA_ORDER_ID = {{ session('wa_prompt') ?? 'null' }};

function sendWhatsapp(orderId) {
    fetch(`/admin/orders/${orderId}/send-whatsapp`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN_SHOW,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        alert(data.success ? '✅ Pesan WhatsApp berhasil dikirim!' : '❌ ' + data.message);
    })
    .catch(() => alert('❌ Terjadi kesalahan saat mengirim pesan.'));
}

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('wa-confirm-modal-show');
    const noBtn  = document.getElementById('wa-show-no');
    const yesBtn = document.getElementById('wa-show-yes');

    noBtn.addEventListener('click', () => modal.classList.add('hidden'));
    yesBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        if (WA_ORDER_ID) sendWhatsapp(WA_ORDER_ID);
    });

    // Auto-buka popup jika session wa_prompt ada
    if (WA_ORDER_ID === {{ $order->id }}) {
        modal.classList.remove('hidden');
    }
});
</script>
@endpush
@endsection