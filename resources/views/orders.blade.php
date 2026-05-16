@extends('layouts.app')


@section('content')
<div class="px-[80px] py-[60px] min-h-[70vh]">
    <div class="mb-10">
        <h1 class="font-playfair text-[2rem] text-[#1a1a1a]">Pesanan Saya</h1>
        <p class="text-[#999] mt-1.5 text-[0.9rem]">Pantau status dan riwayat pesanan Anda</p>
    </div>

    <div class="flex gap-2 mb-8 flex-wrap">
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] text-[0.85rem] font-bold cursor-pointer transition-all bg-[#8B1A1A] text-white border-[#8B1A1A]" data-status="semua">Semua</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="pending">Menunggu</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="diproses">Diproses</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="dikirim">Dikirim</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="siap_diambil">Siap Diambil</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="selesai">Selesai</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="dibatalkan">Dibatalkan</button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-5 py-[14px] rounded-xl text-[0.9rem] flex items-center gap-[10px] mb-6">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-pink-100 text-red-700 px-5 py-[14px] rounded-xl text-[0.9rem] flex items-center gap-[10px] mb-6">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @php
        $hasAnyOrder = $groupedOrders->isNotEmpty();
        // Kumpulkan semua tanggal selain hari ini, urut terbaru dulu
        $otherDates = $groupedOrders->keys()->filter(fn($d) => $d !== $today)->sortDesc()->values();
    @endphp

    <div id="ordersList">

    {{-- ===== SECTION HARI INI (selalu tampil) ===== --}}
    <div class="date-group mb-8" data-date="{{ $today }}" data-is-today="1">
        <div class="flex items-center gap-3 mb-4">
            <h2 class="font-playfair text-[1.15rem] font-bold text-[#1a1a1a]">Hari ini</h2>
            <div class="flex-1 h-px bg-maroon-200"></div>
            <span class="text-[0.8rem] text-[#bbb]">{{ now()->translatedFormat('d F Y') }}</span>
        </div>

        @if($groupedOrders->has($today))
        <div class="flex flex-col gap-5 date-group-cards">
        @foreach($groupedOrders[$today] as $order)
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden transition-all duration-200 hover:shadow-[0_8px_30px_rgba(139,26,26,0.08)] hover:-translate-y-0.5 order-card" data-status="{{ $order->status }}">

            {{-- HEADER --}}
            <div class="flex justify-between items-center px-6 py-[18px] bg-[#fafafa] border-b border-maroon-200">
                <div>
                    <div class="font-extrabold text-[#1a1a1a] text-[0.92rem]">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-[0.82rem] text-[#999] mt-[3px]"><i class="fas fa-calendar-alt mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-[14px] py-[6px] rounded-[20px] text-[0.78rem] font-extrabold
                    @if($order->status === 'pending') bg-amber-50 text-orange-700
                    @elseif($order->status === 'diproses') bg-blue-50 text-blue-800
                    @elseif($order->status === 'dikirim') bg-purple-50 text-purple-800
                    @elseif($order->status === 'siap_diambil') bg-orange-50 text-orange-700
                    @elseif($order->status === 'selesai') bg-green-100 text-green-800
                    @else bg-pink-100 text-red-700 @endif">
                    @if($order->status === 'pending') <i class="fas fa-clock"></i> Menunggu Konfirmasi
                    @elseif($order->status === 'diproses') <i class="fas fa-cog fa-spin"></i> Sedang Diproses
                    @elseif($order->status === 'dikirim') <i class="fas fa-truck"></i> Sedang Dikirim
                    @elseif($order->status === 'siap_diambil') <i class="fas fa-store"></i> Siap Diambil
                    @elseif($order->status === 'selesai') <i class="fas fa-check-circle"></i> Selesai
                    @else <i class="fas fa-times-circle"></i> Dibatalkan
                    @endif
                </span>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-5">
                <div class="flex gap-3 items-center">
                    @foreach($order->items->take(3) as $item)
                        @if($item->product)
                            <img class="w-[60px] h-[60px] object-cover rounded-xl border-[1.5px] border-maroon-200 flex-shrink-0"
                                src="{{ $item->product->image && Str::startsWith($item->product->image, 'products/') ? asset('storage/' . $item->product->image) : $item->product->image }}"
                                alt="{{ $item->product->name }}">
                        @endif
                    @endforeach
                    @if($order->items->count() > 3)
                        <div class="w-[60px] h-[60px] rounded-xl bg-maroon-100 flex items-center justify-center text-maroon font-extrabold text-[0.88rem] flex-shrink-0">
                            +{{ $order->items->count() - 3 }}
                        </div>
                    @endif
                    <div>
                        @if($order->items->count() > 0 && $order->items->first()->product)
                            <h4 class="font-bold text-[#1a1a1a] text-[0.95rem] mb-1">{{ $order->items->first()->product->name }}
                                @if($order->items->count() > 1)
                                    <span class="text-[#999] font-medium">& {{ $order->items->count() - 1 }} produk lainnya</span>
                                @endif
                            </h4>
                        @else
                            <h4 class="text-[#999] text-[0.95rem] mb-1">Produk tidak tersedia</h4>
                        @endif
                        <p class="text-[0.83rem] text-[#999]">{{ $order->items->sum('quantity') }} item · {{ ucfirst($order->metode_pembayaran) }}</p>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="flex justify-between items-center px-6 py-4 border-t border-maroon-200">
                <div>
                    <div class="text-[0.82rem] text-[#999] mb-[3px]">Total Pembayaran</div>
                    <div class="font-extrabold text-maroon text-[1.05rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                </div>
                <div class="flex items-center gap-2">
                    @if($order->status === 'selesai')
                        @if($order->testimonial)
                            <span class="inline-flex items-center gap-1.5 text-green-700 text-[0.85rem] font-bold mr-2">
                                <i class="fas fa-check-circle"></i> Sudah Diulas
                            </span>
                        @else
                            <button class="inline-flex items-center gap-2 bg-white text-maroon px-5 py-[10px] rounded-[10px] font-bold text-[0.85rem] border-[1.5px] border-maroon hover:bg-maroon-100 transition-all cursor-pointer" onclick="toggleForm({{ $order->id }})">
                                <i class="fas fa-star"></i> Tulis Ulasan
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center gap-2 bg-maroon text-white px-5 py-[10px] rounded-[10px] font-bold text-[0.85rem] no-underline hover:bg-maroon-dark hover:-translate-y-px transition-all">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>

            {{-- FORM TESTIMONI --}}
            @if($order->status === 'selesai' && !$order->testimonial)
            <div class="{{ $errors->any() && old('order_id') == $order->id ? '' : 'hidden' }} px-6 py-5 border-t border-maroon-200 bg-[#fffaf9]" id="form-{{ $order->id }}">
                @if(session('success') && session('order_id') == $order->id)
                    <div class="text-green-800 font-bold mb-[10px]">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('testimonial.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <p class="font-bold text-[#1a1a1a] mb-2">Beri Rating</p>
                    <div class="star-rating flex flex-row-reverse justify-end gap-1.5 mb-3">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" id="star{{ $i }}-{{ $order->id }}" value="{{ $i }}" class="sr-only">
                            <label for="star{{ $i }}-{{ $order->id }}" class="text-[#ddd] text-[1.8rem] cursor-pointer transition-colors duration-150 select-none">★</label>
                        @endfor
                    </div>
                    @error('rating')
                        <p class="text-red-600 text-[0.82rem] mb-2 -mt-1"><i class="fas fa-exclamation-circle"></i> Silakan pilih rating bintang terlebih dahulu.</p>
                    @enderror
                    <p class="font-bold text-[#1a1a1a] mb-2">Komentar</p>
                    <textarea name="komentar" rows="3"
                        class="w-full border-[1.5px] border-maroon-200 rounded-[10px] p-3 font-sans text-[0.88rem] resize-y outline-none focus:border-maroon transition-colors box-border"
                        placeholder="Ceritakan pengalaman kamu memesan di Ummilaa Kitchen..."></textarea>
                    @error('komentar')
                        <p class="text-red-600 text-[0.82rem] mt-1"><i class="fas fa-exclamation-circle"></i> Komentar tidak boleh kosong.</p>
                    @enderror
                    <div class="mt-[10px]">
                        <button type="submit" class="bg-maroon text-white border-none px-6 py-[10px] rounded-[10px] font-bold text-[0.85rem] cursor-pointer">
                            <i class="fas fa-paper-plane"></i> Kirim Ulasan
                        </button>
                        <button type="button" onclick="toggleForm({{ $order->id }})"
                            class="bg-transparent border-none text-[#999] text-[0.85rem] cursor-pointer ml-[10px]">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>
        @endforeach
        </div>{{-- .date-group-cards --}}
        @else
        {{-- Hari ini kosong --}}
        <div class="flex items-center gap-4 py-6 px-5 bg-[#fafafa] border border-dashed border-maroon-200 rounded-[18px] text-[#bbb]">
            <i class="fas fa-clock text-[1.6rem]"></i>
            <div>
                <p class="font-bold text-[#999] text-[0.95rem]">Belum ada pesanan hari ini</p>
                <p class="text-[0.82rem] mt-0.5">Yuk pesan sekarang!</p>
            </div>
            <a href="{{ route('catalogue') }}" class="ml-auto inline-flex items-center gap-2 bg-maroon text-white px-5 py-[9px] rounded-xl font-bold text-[0.85rem] no-underline hover:bg-maroon-dark transition-colors flex-shrink-0">
                <i class="fas fa-utensils"></i> Pesan Sekarang
            </a>
        </div>
        @endif
    </div>{{-- .date-group hari ini --}}

    {{-- ===== SECTION TANGGAL LAINNYA ===== --}}
    @foreach($otherDates as $date)
    @php
        $dateOrders = $groupedOrders[$date];
        $carbon = \Carbon\Carbon::parse($date);
        $dateLabel = $date === $yesterday
            ? 'Kemarin'
            : $carbon->translatedFormat('d F Y');
        $dateSubLabel = $date === $yesterday ? $carbon->translatedFormat('d F Y') : null;
    @endphp
    <div class="date-group mb-8" data-date="{{ $date }}">
        <div class="flex items-center gap-3 mb-4">
            <h2 class="font-playfair text-[1.15rem] font-bold text-[#1a1a1a]">{{ $dateLabel }}</h2>
            @if($dateSubLabel)
            <span class="text-[0.8rem] text-[#999] font-normal">{{ $dateSubLabel }}</span>
            @endif
            <div class="flex-1 h-px bg-maroon-200"></div>
        </div>
        <div class="flex flex-col gap-5 date-group-cards">
        @foreach($dateOrders as $order)
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden transition-all duration-200 hover:shadow-[0_8px_30px_rgba(139,26,26,0.08)] hover:-translate-y-0.5 order-card" data-status="{{ $order->status }}">

            {{-- HEADER --}}
            <div class="flex justify-between items-center px-6 py-[18px] bg-[#fafafa] border-b border-maroon-200">
                <div>
                    <div class="font-extrabold text-[#1a1a1a] text-[0.92rem]">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-[0.82rem] text-[#999] mt-[3px]"><i class="fas fa-calendar-alt mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-[14px] py-[6px] rounded-[20px] text-[0.78rem] font-extrabold
                    @if($order->status === 'pending') bg-amber-50 text-orange-700
                    @elseif($order->status === 'diproses') bg-blue-50 text-blue-800
                    @elseif($order->status === 'dikirim') bg-purple-50 text-purple-800
                    @elseif($order->status === 'siap_diambil') bg-orange-50 text-orange-700
                    @elseif($order->status === 'selesai') bg-green-100 text-green-800
                    @else bg-pink-100 text-red-700 @endif">
                    @if($order->status === 'pending') <i class="fas fa-clock"></i> Menunggu Konfirmasi
                    @elseif($order->status === 'diproses') <i class="fas fa-cog fa-spin"></i> Sedang Diproses
                    @elseif($order->status === 'dikirim') <i class="fas fa-truck"></i> Sedang Dikirim
                    @elseif($order->status === 'siap_diambil') <i class="fas fa-store"></i> Siap Diambil
                    @elseif($order->status === 'selesai') <i class="fas fa-check-circle"></i> Selesai
                    @else <i class="fas fa-times-circle"></i> Dibatalkan
                    @endif
                </span>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-5">
                <div class="flex gap-3 items-center">
                    @foreach($order->items->take(3) as $item)
                        @if($item->product)
                            <img class="w-[60px] h-[60px] object-cover rounded-xl border-[1.5px] border-maroon-200 flex-shrink-0"
                                src="{{ $item->product->image && Str::startsWith($item->product->image, 'products/') ? asset('storage/' . $item->product->image) : $item->product->image }}"
                                alt="{{ $item->product->name }}">
                        @endif
                    @endforeach
                    @if($order->items->count() > 3)
                        <div class="w-[60px] h-[60px] rounded-xl bg-maroon-100 flex items-center justify-center text-maroon font-extrabold text-[0.88rem] flex-shrink-0">
                            +{{ $order->items->count() - 3 }}
                        </div>
                    @endif
                    <div>
                        @if($order->items->count() > 0 && $order->items->first()->product)
                            <h4 class="font-bold text-[#1a1a1a] text-[0.95rem] mb-1">{{ $order->items->first()->product->name }}
                                @if($order->items->count() > 1)
                                    <span class="text-[#999] font-medium">& {{ $order->items->count() - 1 }} produk lainnya</span>
                                @endif
                            </h4>
                        @else
                            <h4 class="text-[#999] text-[0.95rem] mb-1">Produk tidak tersedia</h4>
                        @endif
                        <p class="text-[0.83rem] text-[#999]">{{ $order->items->sum('quantity') }} item · {{ ucfirst($order->metode_pembayaran) }}</p>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="flex justify-between items-center px-6 py-4 border-t border-maroon-200">
                <div>
                    <div class="text-[0.82rem] text-[#999] mb-[3px]">Total Pembayaran</div>
                    <div class="font-extrabold text-maroon text-[1.05rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                </div>
                <div class="flex items-center gap-2">
                    @if($order->status === 'selesai')
                        @if($order->testimonial)
                            <span class="inline-flex items-center gap-1.5 text-green-700 text-[0.85rem] font-bold mr-2">
                                <i class="fas fa-check-circle"></i> Sudah Diulas
                            </span>
                        @else
                            <button class="inline-flex items-center gap-2 bg-white text-maroon px-5 py-[10px] rounded-[10px] font-bold text-[0.85rem] border-[1.5px] border-maroon hover:bg-maroon-100 transition-all cursor-pointer" onclick="toggleForm({{ $order->id }})">
                                <i class="fas fa-star"></i> Tulis Ulasan
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center gap-2 bg-maroon text-white px-5 py-[10px] rounded-[10px] font-bold text-[0.85rem] no-underline hover:bg-maroon-dark hover:-translate-y-px transition-all">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>

            {{-- FORM TESTIMONI --}}
            @if($order->status === 'selesai' && !$order->testimonial)
            <div class="{{ $errors->any() && old('order_id') == $order->id ? '' : 'hidden' }} px-6 py-5 border-t border-maroon-200 bg-[#fffaf9]" id="form-{{ $order->id }}">
                <form action="{{ route('testimonial.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <p class="font-bold text-[#1a1a1a] mb-2">Beri Rating</p>
                    <div class="star-rating flex flex-row-reverse justify-end gap-1.5 mb-3">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" id="star{{ $i }}-{{ $order->id }}" value="{{ $i }}" class="sr-only">
                            <label for="star{{ $i }}-{{ $order->id }}" class="text-[#ddd] text-[1.8rem] cursor-pointer transition-colors duration-150 select-none">★</label>
                        @endfor
                    </div>
                    @error('rating')
                        <p class="text-red-600 text-[0.82rem] mb-2 -mt-1"><i class="fas fa-exclamation-circle"></i> Silakan pilih rating bintang terlebih dahulu.</p>
                    @enderror
                    <p class="font-bold text-[#1a1a1a] mb-2">Komentar</p>
                    <textarea name="komentar" rows="3"
                        class="w-full border-[1.5px] border-maroon-200 rounded-[10px] p-3 font-sans text-[0.88rem] resize-y outline-none focus:border-maroon transition-colors box-border"
                        placeholder="Ceritakan pengalaman kamu memesan di Ummilaa Kitchen..."></textarea>
                    @error('komentar')
                        <p class="text-red-600 text-[0.82rem] mt-1"><i class="fas fa-exclamation-circle"></i> Komentar tidak boleh kosong.</p>
                    @enderror
                    <div class="mt-[10px]">
                        <button type="submit" class="bg-maroon text-white border-none px-6 py-[10px] rounded-[10px] font-bold text-[0.85rem] cursor-pointer">
                            <i class="fas fa-paper-plane"></i> Kirim Ulasan
                        </button>
                        <button type="button" onclick="toggleForm({{ $order->id }})"
                            class="bg-transparent border-none text-[#999] text-[0.85rem] cursor-pointer ml-[10px]">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>
        @endforeach
        </div>{{-- .date-group-cards --}}
    </div>{{-- .date-group --}}
    @endforeach

    @if(!$hasAnyOrder)
    {{-- Empty state global (belum pernah pesan sama sekali) --}}
    <div class="text-center py-[60px] px-10 text-[#bbb]">
        <i class="fas fa-box-open text-[3.5rem] mb-5 block"></i>
        <h3 class="text-[1.2rem] text-[#999] mb-3">Belum ada pesanan</h3>
        <p>Yuk mulai pesan produk favorit dari Ummilaa Kitchen</p>
        <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 bg-maroon text-white px-7 py-[13px] rounded-xl font-bold text-[0.95rem] mt-2 no-underline hover:bg-maroon-dark transition-colors">
            <i class="fas fa-utensils"></i> Belanja Sekarang
        </a>
    </div>
    @endif

    </div>{{-- #ordersList --}}
</div>
@endsection

@push('scripts')
<script>
    // Status tab active state via Tailwind classes
    function deactivateTab(t) {
        t.classList.remove('bg-[#8B1A1A]', 'text-white', 'border-[#8B1A1A]');
        t.classList.add('bg-white', 'text-[#777]', 'border-maroon-200');
    }
    function activateTab(t) {
        t.classList.add('bg-[#8B1A1A]', 'text-white', 'border-[#8B1A1A]');
        t.classList.remove('bg-white', 'text-[#777]', 'border-maroon-200');
    }

    document.querySelectorAll('.status-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.status-tab').forEach(deactivateTab);
            activateTab(tab);
            const status = tab.dataset.status;

            // Show/hide kartu
            document.querySelectorAll('.order-card').forEach(card => {
                card.classList.toggle('hidden', !(status === 'semua' || card.dataset.status === status));
            });

            // Show/hide date-group (kecuali "Hari ini" yang selalu tampil)
            document.querySelectorAll('.date-group').forEach(group => {
                if (group.dataset.isToday === '1') return; // Hari ini selalu tampil
                const cards = group.querySelectorAll('.order-card');
                const anyVisible = [...cards].some(c => !c.classList.contains('hidden'));
                group.classList.toggle('hidden', !anyVisible);
            });
        });
    });

    // Testimoni form toggle via Tailwind hidden class
    function toggleForm(orderId) {
        const form = document.getElementById('form-' + orderId);
        form.classList.toggle('hidden');
    }

    // Validasi form ulasan sebelum submit
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

    // Star rating via JS (replaces CSS sibling selectors)
    document.querySelectorAll('.star-rating').forEach(container => {
        const labels = [...container.querySelectorAll('label')];
        const inputs = [...container.querySelectorAll('input')];

        function applyRating(idx) {
            // DOM order: [label5, label4, label3, label2, label1]
            // Highlight from selected (idx) to end (lower stars)
            labels.forEach((lbl, i) => {
                lbl.classList.toggle('text-amber-400', i >= idx);
                lbl.classList.toggle('text-[#ddd]', i < idx);
            });
        }

        function resetToChecked() {
            const checkedIdx = inputs.findIndex(inp => inp.checked);
            if (checkedIdx >= 0) applyRating(checkedIdx);
            else labels.forEach(lbl => { lbl.classList.add('text-[#ddd]'); lbl.classList.remove('text-amber-400'); });
        }

        labels.forEach((lbl, idx) => {
            lbl.addEventListener('click', () => { inputs[idx].checked = true; applyRating(idx); });
            lbl.addEventListener('mouseenter', () => applyRating(idx));
            lbl.addEventListener('mouseleave', resetToChecked);
        });
    });
</script>
@endpush
