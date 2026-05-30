@extends('layouts.app')

@php
$statusMeta = [
    'pengajuan'           => ['Menunggu Konfirmasi', 'bg-yellow-50 text-yellow-700 border-yellow-200',   'fa-hourglass-half',  'border-yellow-400',  0],
    'menunggu_pembayaran' => ['Menunggu Pembayaran', 'bg-amber-50 text-orange-700 border-amber-200',    'fa-money-bill-wave', 'border-orange-400',  1],
    'diproses'            => ['Diproses',             'bg-blue-50 text-blue-700 border-blue-200',        'fa-utensils',        'border-blue-400',    2],
    'dikirim'             => ['Sedang Dikirim',       'bg-purple-50 text-purple-700 border-purple-200',  'fa-truck',           'border-purple-400',  3],
    'selesai'             => ['Selesai',              'bg-green-50 text-green-700 border-green-200',     'fa-circle-check',    'border-green-500',   4],
    'dibatalkan'          => ['Dibatalkan',           'bg-red-50 text-red-700 border-red-200',           'fa-circle-xmark',    'border-red-300',    -1],
];

$steps = [
    ['key' => 'pengajuan',           'label' => 'Pengajuan'],
    ['key' => 'menunggu_pembayaran', 'label' => 'Pembayaran'],
    ['key' => 'diproses',            'label' => 'Diproses'],
    ['key' => 'dikirim',             'label' => 'Dikirim'],
    ['key' => 'selesai',             'label' => 'Selesai'],
];

$countAktif     = $orders->whereIn('status', ['pengajuan', 'menunggu_pembayaran', 'diproses', 'dikirim'])->count();
$countSelesai   = $orders->where('status', 'selesai')->count();
$countDibatalkan = $orders->where('status', 'dibatalkan')->count();

$defaultTab = $countAktif > 0 ? 'aktif' : ($countSelesai > 0 ? 'selesai' : 'dibatalkan');
@endphp

@section('content')
<div class="px-4 md:px-10 lg:px-[80px] py-10 lg:py-[60px] min-h-[70vh]">

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="font-playfair text-[2rem] text-[#1a1a1a]">Riwayat Catering</h1>
        <p class="text-[#999] mt-1.5 text-[0.9rem]">Pantau status dan riwayat pesanan catering Anda</p>
    </div>

    {{-- EMPTY STATE (no orders at all) --}}
    @if($orders->isEmpty())
    <div class="text-center py-20 bg-maroon-50 rounded-2xl">
        <i class="fas fa-bowl-food text-[3rem] text-[#ddd] mb-4 block"></i>
        <p class="text-[#888] mb-1 font-semibold">Belum ada pesanan catering</p>
        <p class="text-[0.82rem] text-[#bbb] mb-5">Buat pesanan pertama Anda sekarang.</p>
        <a href="{{ route('catering.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-maroon text-white rounded-xl font-bold text-[0.85rem] no-underline hover:bg-maroon-dark transition-all">
            <i class="fas fa-utensils text-xs"></i> Mulai Pesan Catering
        </a>
    </div>

    @else

    @if(session('success'))
    <div class="bg-green-100 text-green-800 rounded-xl px-5 py-[14px] text-[0.9rem] mb-6 flex items-center gap-[10px]">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-pink-100 text-red-700 rounded-xl px-5 py-[14px] text-[0.9rem] mb-6 flex items-center gap-[10px]">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    {{-- TABS (gaya pill, sama seperti Pesanan Saya) --}}
    @php
        $tabBadge = 'tab-badge absolute -top-1.5 -right-1.5 min-w-[20px] h-[20px] px-[5px] flex items-center justify-center rounded-full text-[0.68rem] font-extrabold leading-none bg-[#EF4444] text-white shadow-[0_2px_6px_rgba(239,68,68,0.45)] ring-[2.5px] ring-white pointer-events-none';
        $tabBase  = 'status-tab relative inline-flex items-center px-5 py-[9px] rounded-[25px] border-[1.5px] text-[0.85rem] font-bold cursor-pointer transition-all whitespace-nowrap flex-shrink-0';
        $tabs = [
            ['key' => 'aktif',      'label' => 'Aktif',      'count' => $countAktif],
            ['key' => 'selesai',    'label' => 'Selesai',    'count' => $countSelesai],
            ['key' => 'dibatalkan', 'label' => 'Dibatalkan', 'count' => $countDibatalkan],
        ];
    @endphp
    <div class="relative mb-8">
        <div class="flex gap-3 overflow-x-auto pb-2 pt-2 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] -mx-4 px-4 md:mx-0 md:px-0">
            @foreach($tabs as $tab)
            <button data-tab="{{ $tab['key'] }}"
                class="{{ $tabBase }} {{ $defaultTab === $tab['key'] ? 'bg-[#8B1A1A] text-white border-[#8B1A1A]' : 'border-maroon-200 bg-white text-[#777] hover:border-maroon hover:text-maroon' }}">
                {{ $tab['label'] }}
                @if($tab['count'] > 0)<span class="{{ $tabBadge }}">{{ $tab['count'] > 99 ? '99+' : $tab['count'] }}</span>@endif
            </button>
            @endforeach
        </div>
        <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-white to-transparent z-10 md:hidden"></div>
    </div>

    {{-- ORDER LIST --}}
    <div class="space-y-4" id="orderList">
        @foreach($orders as $order)
        @php
            [$label, $badgeColor, $icon, $borderColor, $stepIndex] = $statusMeta[$order->status] ?? ['Tidak Diketahui', 'bg-gray-50 text-gray-700 border-gray-200', 'fa-circle', 'border-gray-300', -1];
            $isCancelled = $order->status === 'dibatalkan';
            $group = in_array($order->status, ['pengajuan', 'menunggu_pembayaran', 'diproses', 'dikirim']) ? 'aktif'
                   : ($order->status === 'selesai' ? 'selesai' : 'dibatalkan');
        @endphp

        <div class="order-card bg-white rounded-[16px] border border-maroon-200 border-l-4 {{ $borderColor }} overflow-hidden transition-all duration-200 hover:shadow-[0_6px_24px_rgba(139,26,26,0.08)] {{ $defaultTab !== $group ? 'hidden' : '' }}"
             data-group="{{ $group }}">

            {{-- TOP: info utama --}}
            <div class="p-5 pb-4">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="min-w-0">
                        <p class="text-[0.7rem] text-[#bbb] mb-0.5 font-semibold tracking-wide uppercase">
                            #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            · {{ $order->created_at->translatedFormat('d M Y') }}
                        </p>
                        <h3 class="font-playfair text-[1.1rem] sm:text-[1.2rem] text-[#1a1a1a] truncate">{{ $order->nama_acara }}</h3>
                        <p class="text-[0.8rem] text-[#999] mt-0.5">{{ $order->package->name ?? 'Custom' }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[0.72rem] font-extrabold whitespace-nowrap border {{ $badgeColor }} shrink-0">
                        <i class="fas {{ $icon }} text-[0.65rem]"></i> {{ $label }}
                    </span>
                </div>

                {{-- INFO GRID --}}
                <div class="flex flex-wrap gap-3 sm:gap-5 mb-4">
                    <div class="flex items-center gap-1.5 text-[0.8rem] text-[#666]">
                        <i class="fas fa-calendar-days text-maroon/60 text-[0.75rem]"></i>
                        <span>Acara: <strong class="text-[#333]">{{ \Carbon\Carbon::parse($order->tanggal_acara)->translatedFormat('d M Y') }}</strong></span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[0.8rem] text-[#666]">
                        <i class="fas fa-users text-maroon/60 text-[0.75rem]"></i>
                        <span><strong class="text-[#333]">{{ $order->jumlah_pax }}</strong> pax</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[0.8rem] text-[#666]">
                        <i class="fas fa-location-dot text-maroon/60 text-[0.75rem]"></i>
                        <span class="truncate max-w-[180px]">{{ Str::limit($order->lokasi_acara, 35) }}</span>
                    </div>
                </div>

                {{-- PROGRESS STEPS --}}
                @if(!$isCancelled)
                <div class="flex items-center gap-0">
                    @foreach($steps as $i => $step)
                    @php
                        $done    = $stepIndex > $i;
                        $current = $stepIndex === $i;
                        $future  = $stepIndex < $i;
                    @endphp
                    <div class="flex items-center {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[0.6rem] font-bold shrink-0
                                {{ $done    ? 'bg-maroon text-white' : '' }}
                                {{ $current ? 'bg-maroon text-white ring-4 ring-maroon/20' : '' }}
                                {{ $future  ? 'bg-[#eee] text-[#bbb]' : '' }}">
                                @if($done)<i class="fas fa-check"></i>@else{{ $i + 1 }}@endif
                            </div>
                            <span class="text-[0.6rem] mt-1 whitespace-nowrap font-semibold
                                {{ $done || $current ? 'text-maroon' : 'text-[#ccc]' }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                        @if($i < count($steps) - 1)
                        <div class="h-[2px] flex-1 mx-1 mb-[18px] {{ $stepIndex > $i ? 'bg-maroon' : 'bg-[#eee]' }}"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex items-center gap-2 bg-red-50 rounded-lg px-3 py-2 text-[0.78rem] text-red-700">
                    <i class="fas fa-circle-xmark"></i>
                    <span>Pesanan dibatalkan</span>
                    @if($order->alasan_pembatalan)
                    <span class="text-red-400">· {{ Str::limit($order->alasan_pembatalan, 50) }}</span>
                    @endif
                </div>
                @endif
            </div>

            {{-- BOTTOM: total + actions --}}
            <div class="flex items-center justify-between gap-3 px-5 py-3 border-t border-maroon-100 bg-[#fdfafa]">
                <div class="text-[0.85rem]">
                    <span class="text-[#aaa] text-[0.75rem]">Total</span>
                    <p class="font-extrabold text-maroon leading-none mt-0.5">
                        {{ $order->total ? 'Rp' . number_format($order->total, 0, ',', '.') : 'Menunggu penawaran' }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @if($order->status === 'menunggu_pembayaran')
                    <a href="{{ route('catering.payment', $order->id) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-maroon text-white rounded-lg font-bold text-[0.8rem] no-underline hover:bg-maroon-dark transition-all shadow-[0_2px_8px_rgba(139,26,26,0.2)]">
                        <i class="fas fa-credit-card text-[0.72rem]"></i> Bayar Sekarang
                    </a>
                    @endif
                    @if($order->status === 'selesai')
                        @if($order->testimonial)
                            <span class="inline-flex items-center gap-1 text-green-700 text-[0.78rem] font-bold">
                                <i class="fas fa-check-circle"></i> Sudah Diulas
                            </span>
                        @else
                            <button onclick="toggleCateringForm({{ $order->id }})"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-maroon border-[1.5px] border-maroon rounded-lg font-bold text-[0.8rem] hover:bg-maroon-50 transition-all cursor-pointer">
                                <i class="fas fa-star text-[0.72rem]"></i> Tulis Ulasan
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('catering.show', $order->id) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 border-[1.5px] border-maroon text-maroon rounded-lg font-bold text-[0.8rem] no-underline hover:bg-maroon-50 transition-all">
                        <i class="fas fa-eye text-[0.72rem]"></i> Detail
                    </a>
                </div>
            </div>

            {{-- FORM ULASAN --}}
            @if($order->status === 'selesai' && !$order->testimonial)
            <div class="hidden px-5 py-5 border-t border-maroon-200 bg-[#fffaf9]" id="form-catering-{{ $order->id }}">
                <form action="{{ route('testimonial.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="catering_order_id" value="{{ $order->id }}">
                    <p class="font-bold text-[#1a1a1a] mb-2">Beri Rating</p>
                    <div class="star-rating flex flex-row-reverse justify-end gap-1.5 mb-3">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" id="cstar{{ $i }}-{{ $order->id }}" value="{{ $i }}" class="sr-only">
                            <label for="cstar{{ $i }}-{{ $order->id }}" class="text-[#ddd] text-[1.8rem] cursor-pointer transition-colors duration-150 select-none">★</label>
                        @endfor
                    </div>
                    @error('rating')
                        <p class="text-red-600 text-[0.82rem] mb-2 -mt-1"><i class="fas fa-exclamation-circle"></i> Silakan pilih rating bintang terlebih dahulu.</p>
                    @enderror
                    <p class="font-bold text-[#1a1a1a] mb-2">Komentar</p>
                    <textarea name="komentar" rows="3"
                        class="w-full border-[1.5px] border-maroon-200 rounded-[10px] p-3 font-sans text-[0.88rem] resize-y outline-none focus:border-maroon transition-colors box-border"
                        placeholder="Ceritakan pengalaman kamu menggunakan layanan catering Ummilaa Kitchen...">{{ old('komentar') }}</textarea>
                    @error('komentar')
                        <p class="text-red-600 text-[0.82rem] mt-1"><i class="fas fa-exclamation-circle"></i> Komentar tidak boleh kosong.</p>
                    @enderror
                    <div class="mt-3">
                        <button type="submit" class="bg-maroon text-white border-none px-6 py-[10px] rounded-[10px] font-bold text-[0.85rem] cursor-pointer hover:bg-maroon-dark transition">
                            <i class="fas fa-paper-plane"></i> Kirim Ulasan
                        </button>
                        <button type="button" onclick="toggleCateringForm({{ $order->id }})"
                            class="bg-transparent border-none text-[#999] text-[0.85rem] cursor-pointer ml-[10px]">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>
        @endforeach

        {{-- Empty state per tab (shown by JS) --}}
        <div id="empty-aktif"      class="hidden text-center py-14 bg-[#fafafa] rounded-2xl text-[#bbb]"><i class="fas fa-spinner text-2xl mb-3 block"></i><p class="text-sm">Tidak ada pesanan aktif</p></div>
        <div id="empty-selesai"    class="hidden text-center py-14 bg-[#fafafa] rounded-2xl text-[#bbb]"><i class="fas fa-circle-check text-2xl mb-3 block"></i><p class="text-sm">Belum ada pesanan selesai</p></div>
        <div id="empty-dibatalkan" class="hidden text-center py-14 bg-[#fafafa] rounded-2xl text-[#bbb]"><i class="fas fa-circle-xmark text-2xl mb-3 block"></i><p class="text-sm">Tidak ada pesanan dibatalkan</p></div>
    </div>
    @endif

</div>

@push('scripts')
<script>
const DEFAULT_TAB = '{{ $defaultTab }}';

function deactivateTab(t) {
    t.classList.remove('bg-[#8B1A1A]', 'text-white', 'border-[#8B1A1A]');
    t.classList.add('bg-white', 'text-[#777]', 'border-maroon-200', 'hover:border-maroon', 'hover:text-maroon');
}
function activateTab(t) {
    t.classList.add('bg-[#8B1A1A]', 'text-white', 'border-[#8B1A1A]');
    t.classList.remove('bg-white', 'text-[#777]', 'border-maroon-200', 'hover:border-maroon', 'hover:text-maroon');
}

function switchTab(tab) {
    document.querySelectorAll('.status-tab').forEach(t => {
        t.dataset.tab === tab ? activateTab(t) : deactivateTab(t);
    });

    let visibleCount = 0;
    document.querySelectorAll('.order-card').forEach(card => {
        const show = card.dataset.group === tab;
        card.classList.toggle('hidden', !show);
        if (show) visibleCount++;
    });

    ['aktif', 'selesai', 'dibatalkan'].forEach(t => {
        const el = document.getElementById('empty-' + t);
        if (el) el.classList.add('hidden');
    });
    if (visibleCount === 0) {
        const emptyEl = document.getElementById('empty-' + tab);
        if (emptyEl) emptyEl.classList.remove('hidden');
    }
}

document.querySelectorAll('.status-tab').forEach(tab => {
    tab.addEventListener('click', () => switchTab(tab.dataset.tab));
});

switchTab(DEFAULT_TAB);

function toggleCateringForm(id) {
    const el = document.getElementById('form-catering-' + id);
    if (el) el.classList.toggle('hidden');
}

document.querySelectorAll('form[action*="testimonial"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        this.querySelectorAll('.field-error').forEach(el => el.remove());

        const ratingInputs = this.querySelectorAll('input[name="rating"]');
        const hasRating = [...ratingInputs].some(inp => inp.checked);
        const komentar = this.querySelector('textarea[name="komentar"]');
        const hasKomentar = komentar.value.trim() !== '';

        let hasError = false;

        if (!hasRating) {
            hasError = true;
            const errEl = document.createElement('p');
            errEl.className = 'field-error text-red-600 text-[0.82rem] mb-2';
            errEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Silakan pilih rating bintang terlebih dahulu.';
            this.querySelector('.star-rating').insertAdjacentElement('afterend', errEl);
        }

        if (!hasKomentar) {
            hasError = true;
            const errEl = document.createElement('p');
            errEl.className = 'field-error text-red-600 text-[0.82rem] mt-1';
            errEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Komentar tidak boleh kosong.';
            komentar.insertAdjacentElement('afterend', errEl);
        }

        if (hasError) e.preventDefault();
    });
});
</script>
<style>
.star-rating label:hover ~ label,
.star-rating label:hover,
.star-rating input:checked ~ label {
    color: #f59e0b;
}
</style>
@endpush
@endsection
