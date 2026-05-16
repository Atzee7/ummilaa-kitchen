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
    $nextStatus = match(true) {
        $order->status === 'pending'                  => 'diproses',
        $order->status === 'diproses' && $isDelivery  => 'dikirim',
        $order->status === 'diproses' && !$isDelivery => 'siap_diambil',
        $order->status === 'dikirim'                  => 'selesai',
        $order->status === 'siap_diambil'             => 'selesai',
        default                                       => null,
    };
    $nextLabel = match(true) {
        $order->status === 'pending'                  => 'Proses Pesanan',
        $order->status === 'diproses' && $isDelivery  => 'Tandai Dikirim',
        $order->status === 'diproses' && !$isDelivery => 'Tandai Siap Diambil',
        $order->status === 'dikirim'                  => 'Tandai Selesai',
        $order->status === 'siap_diambil'             => 'Tandai Selesai',
        default                                       => null,
    };
    $canCancel = in_array($order->status, ['pending', 'diproses']);
@endphp

{{-- HEADER MODAL --}}
<div class="flex items-start justify-between p-6 border-b border-gray-100">
    <div>
        <h3 class="font-playfair text-xl font-bold text-gray-800">
            Detail Pesanan <span class="text-[#8B1A1A]">#{{ $order->id }}</span>
        </h3>
        <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $sc }}">{{ $statusLabel }}</span>
        <button onclick="closeOrderModal()" class="w-8 h-8 rounded-lg hover:bg-gray-100 transition flex items-center justify-center text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

{{-- BODY MODAL --}}
<div class="overflow-y-auto max-h-[60vh] p-6 space-y-5">

    {{-- AKSI STATUS --}}
    @if($nextStatus || $canCancel)
    <div class="bg-gray-50 rounded-xl p-4 flex flex-wrap items-center gap-3">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mr-auto">Ubah Status:</span>
        @if($nextStatus)
        <button onclick="modalUpdateStatus({{ $order->id }}, '{{ $nextStatus }}')"
            class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#8B1A1A] hover:opacity-90 transition">
            {{ $nextLabel }}
        </button>
        @endif
        @if($canCancel)
        <button onclick="modalUpdateStatus({{ $order->id }}, 'dibatalkan')"
            class="px-4 py-2 rounded-xl text-xs font-bold text-red-600 border border-red-200 hover:bg-red-50 transition">
            Batalkan
        </button>
        @endif
    </div>
    @endif

    {{-- INFO PEMESAN --}}
    <div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Informasi Pemesan</p>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Nama Penerima</p>
                <p class="font-bold text-gray-800">{{ $order->nama_penerima }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">No. Telepon</p>
                <p class="font-semibold text-gray-700">{{ $order->no_telepon }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Akun</p>
                <p class="font-semibold text-gray-700">{{ $order->user->email ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Pembayaran</p>
                <p class="font-semibold text-gray-700 uppercase text-xs">{{ $order->metode_pembayaran }}</p>
            </div>
        </div>
    </div>

    {{-- PENGIRIMAN --}}
    <div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Pengiriman</p>
        <div class="flex items-start gap-3 text-sm">
            @if($isDelivery)
                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-800">Delivery</p>
                    <p class="text-xs text-gray-500 leading-relaxed mt-0.5">{{ $order->alamat }}</p>
                    @if($order->detail_alamat)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $order->detail_alamat }}</p>
                    @endif
                </div>
            @else
                <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-800">Ambil Sendiri</p>
                    <p class="text-xs text-gray-500 mt-0.5">Ummilaa Kitchen, Malang</p>
                </div>
            @endif
        </div>
    </div>

    {{-- CATATAN --}}
    @if($order->catatan)
    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 flex gap-2">
        <span class="text-base flex-shrink-0">💬</span>
        <p class="text-xs text-gray-700 italic leading-relaxed">"{{ $order->catatan }}"</p>
    </div>
    @endif

    {{-- PRODUK --}}
    <div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
            Produk Dipesan <span class="font-normal">({{ $order->items->count() }} item)</span>
        </p>
        <div class="divide-y divide-gray-50">
            @foreach($order->items as $item)
            <div class="flex items-center gap-3 py-3">
                @if($item->product && $item->product->image)
                    <img src="{{ Str::startsWith($item->product->image, 'products/') ? asset('storage/' . $item->product->image) : $item->product->image }}"
                         class="w-11 h-11 rounded-lg object-cover flex-shrink-0">
                @else
                    <div class="w-11 h-11 rounded-lg bg-gray-100 flex items-center justify-center text-lg flex-shrink-0">🍽️</div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-800">{{ $item->product->name ?? 'Produk dihapus' }}</p>
                    <p class="text-xs text-gray-400">{{ $item->quantity }} pcs × Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
                <p class="text-sm font-bold text-gray-800 flex-shrink-0">
                    Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- Ringkasan harga --}}
        <div class="border-t border-gray-100 mt-1 pt-3 space-y-2 text-sm">
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
            <div class="flex justify-between font-bold text-base pt-2 border-t border-gray-100">
                <span class="text-gray-800">Total</span>
                <span class="text-[#8B1A1A]">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

</div>

{{-- FOOTER MODAL --}}
<div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
    <a href="{{ route('admin.orders.show', $order->id) }}"
       class="text-xs text-gray-400 hover:text-gray-600 transition flex items-center gap-1">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        Buka halaman penuh
    </a>
    <button onclick="closeOrderModal()"
        class="px-4 py-2 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition">
        Tutup
    </button>
</div>
