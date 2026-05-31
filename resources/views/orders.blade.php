@extends('layouts.app')


@section('content')
<div class="px-4 md:px-10 lg:px-[80px] py-10 lg:py-[60px] min-h-[70vh]">
    <div class="mb-10">
        <h1 class="font-playfair text-[2rem] text-[#1a1a1a]">Riwayat Katalog</h1>
        <p class="text-[#999] mt-1.5 text-[0.9rem]">Pantau status dan riwayat pesanan Anda</p>
    </div>

    @php
        $hasAnyOrder   = $groupedOrders->isNotEmpty();
        $otherDates    = $groupedOrders->keys()->filter(fn($d) => $d !== $today)->sortDesc()->values();
        $aktifStatuses = ['belum_bayar', 'pending', 'pembayaran', 'diproses', 'dikirim', 'siap_diambil'];
        $orderSteps    = [
            ['label' => 'Menunggu Bayar'],
            ['label' => 'Pembayaran'],
            ['label' => 'Dimasak'],
            ['label' => 'Dikirim'],
            ['label' => 'Selesai'],
        ];
        $tabBadge = 'tab-badge absolute -top-1.5 -right-1.5 min-w-[20px] h-[20px] px-[5px] flex items-center justify-center rounded-full text-[0.68rem] font-extrabold leading-none bg-[#EF4444] text-white shadow-[0_2px_6px_rgba(239,68,68,0.45)] ring-[2.5px] ring-white pointer-events-none';
        $tabBase  = 'status-tab relative inline-flex items-center px-5 py-[9px] rounded-[25px] border-[1.5px] text-[0.85rem] font-bold cursor-pointer transition-all whitespace-nowrap flex-shrink-0';
    @endphp
    @if($hasAnyOrder)
    <div class="relative mb-8">
    <div class="flex gap-3 overflow-x-auto pb-2 pt-2 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] -mx-4 px-4 md:mx-0 md:px-0">
        <button class="{{ $tabBase }} border-maroon-200 bg-white text-[#777] hover:border-maroon hover:text-maroon" data-tab="aktif">
            Aktif
            @if(($counts['aktif'] ?? 0) > 0)<span class="{{ $tabBadge }}">{{ $counts['aktif'] > 99 ? '99+' : $counts['aktif'] }}</span>@endif
        </button>
        <button class="{{ $tabBase }} border-maroon-200 bg-white text-[#777] hover:border-maroon hover:text-maroon" data-tab="selesai">
            Selesai
            @if(($counts['selesai'] ?? 0) > 0)<span class="{{ $tabBadge }}">{{ $counts['selesai'] > 99 ? '99+' : $counts['selesai'] }}</span>@endif
        </button>
        <button class="{{ $tabBase }} border-maroon-200 bg-white text-[#777] hover:border-maroon hover:text-maroon" data-tab="dibatalkan">
            Dibatalkan
            @if(($counts['dibatalkan'] ?? 0) > 0)<span class="{{ $tabBadge }}">{{ $counts['dibatalkan'] > 99 ? '99+' : $counts['dibatalkan'] }}</span>@endif
        </button>
    </div>
    <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-white to-transparent z-10 md:hidden"></div>
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

    <div id="ordersList">

    {{-- ===== SECTION HARI INI (hanya tampil jika ada pesanan) ===== --}}
    @if($groupedOrders->has($today))
    <div class="date-group mb-8" data-date="{{ $today }}" data-is-today="1">
        <div class="flex items-center gap-3 mb-4">
            <h2 class="font-playfair text-[1.15rem] font-bold text-[#1a1a1a]">Hari ini</h2>
            <div class="flex-1 h-px bg-maroon-200"></div>
            <span class="text-[0.8rem] text-[#bbb]">{{ now()->translatedFormat('d F Y') }}</span>
        </div>

        <div class="flex flex-col gap-5 date-group-cards">
        @foreach($groupedOrders[$today] as $order)
        @php $group = in_array($order->status, $aktifStatuses) ? 'aktif' : ($order->status === 'selesai' ? 'selesai' : 'dibatalkan'); @endphp
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden transition-all duration-200 hover:shadow-[0_8px_30px_rgba(139,26,26,0.08)] hover:-translate-y-0.5 order-card" data-status="{{ $order->status }}" data-group="{{ $group }}">

            {{-- HEADER --}}
            <div class="flex justify-between items-start flex-wrap gap-y-2 px-4 sm:px-6 py-[18px] bg-[#fafafa] border-b border-maroon-200">
                <div>
                    <div class="font-extrabold text-[#1a1a1a] text-[0.92rem]">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-[0.82rem] text-[#999] mt-[3px]"><i class="fas fa-calendar-alt mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-[14px] py-[6px] rounded-[20px] text-[0.78rem] font-extrabold flex-shrink-0
                    @if($order->status === 'belum_bayar') bg-amber-50 text-orange-700
                    @elseif($order->status === 'pending') bg-yellow-50 text-yellow-800
                    @elseif($order->status === 'diproses') bg-blue-50 text-blue-800
                    @elseif($order->status === 'dikirim') bg-purple-50 text-purple-800
                    @elseif($order->status === 'siap_diambil') bg-orange-50 text-orange-700
                    @elseif($order->status === 'selesai') bg-green-100 text-green-800
                    @elseif($order->status === 'pembayaran') bg-teal-50 text-teal-700
                    @else bg-pink-100 text-red-700 @endif">
                    @if($order->status === 'belum_bayar') <i class="fas fa-hourglass-half"></i> Belum Bayar
                    @elseif($order->status === 'pending') <i class="fas fa-clock"></i> Menunggu
                    @elseif($order->status === 'diproses') <i class="fas fa-utensils"></i> Sedang Dimasak
                    @elseif($order->status === 'dikirim') <i class="fas fa-truck"></i> Sedang Dikirim
                    @elseif($order->status === 'siap_diambil') <i class="fas fa-store"></i> Siap Diambil
                    @elseif($order->status === 'selesai') <i class="fas fa-check-circle"></i> Selesai
                    @elseif($order->status === 'pembayaran') <i class="fas fa-credit-card"></i> Sudah Bayar
                    @else <i class="fas fa-times-circle"></i> Dibatalkan
                    @endif
                </span>
            </div>

            @if($order->status === 'belum_bayar')
            <div class="px-6 pt-3 -mb-1">
                <span class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-lg text-[0.78rem] text-amber-800">
                    <i class="fas fa-hourglass-half"></i>
                    <span>Bayar dalam</span>
                    <strong class="font-mono payment-countdown" data-expires-at="{{ $order->paymentExpiresAt()->toIso8601String() }}">--:--</strong>
                </span>
            </div>
            @endif

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
                        @if($order->tanggal_pengiriman)
                        <p class="text-[0.78rem] text-blue-600 mt-1 font-semibold">
                            <i class="fas fa-calendar-check text-[0.65rem]"></i>
                            Jadwal: {{ $order->tanggal_pengiriman->isToday() ? 'Hari ini' : $order->tanggal_pengiriman->translatedFormat('d M Y') }}@if($order->waktu_pengiriman) · {{ $order->waktu_pengiriman }}@endif
                        </p>
                        @endif
                    </div>
                </div>

                @if($order->status === 'dibatalkan' && $order->alasan_pembatalan)
                <div class="mt-4 flex items-start gap-2.5 px-4 py-3 bg-red-50 border border-red-200 rounded-xl">
                    <i class="fas fa-times-circle text-red-500 text-[0.9rem] mt-0.5 flex-shrink-0"></i>
                    <div class="min-w-0">
                        <p class="text-[0.72rem] font-bold text-red-600 uppercase tracking-wider mb-0.5">Alasan Pembatalan</p>
                        <p class="text-[0.85rem] text-red-800 leading-relaxed line-clamp-2">{{ $order->alasan_pembatalan }}</p>
                    </div>
                </div>
                @endif
                @if($order->status !== 'dibatalkan')
                @php
                    $isPickup       = ($order->metode_pengiriman ?? 'delivery') !== 'delivery';
                    $paidNotCooking = in_array($order->status, ['pembayaran', 'pending']);
                    $orderStepIndex = match($order->status) {
                        'belum_bayar'             => 0,
                        'pembayaran', 'pending'   => 0,
                        'diproses'                => 1,
                        'dikirim', 'siap_diambil' => 2,
                        'selesai'                 => 3,
                        default                   => -1,
                    };
                    $currentOrderSteps = [
                        ['label' => 'Pembayaran'],
                        ['label' => 'Dimasak'],
                        ['label' => $isPickup ? 'Siap Diambil' : 'Dikirim'],
                        ['label' => 'Selesai'],
                    ];
                @endphp
                <div class="mt-4 pt-3 border-t border-maroon-100 flex items-center">
                    @foreach($currentOrderSteps as $i => $step)
                    @php
                        $done    = ($orderStepIndex > $i) || ($paidNotCooking && $i === 0);
                        $current = ($orderStepIndex === $i) && !($paidNotCooking && $i === 0);
                    @endphp
                    <div class="flex items-center {{ $i < count($currentOrderSteps) - 1 ? 'flex-1' : '' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[0.6rem] font-bold shrink-0
                                {{ $done    ? 'bg-maroon text-white' : '' }}
                                {{ $current ? 'bg-maroon text-white ring-4 ring-maroon/20' : '' }}
                                {{ !$done && !$current ? 'bg-[#eee] text-[#bbb]' : '' }}">
                                @if($done)<i class="fas fa-check"></i>@else{{ $i + 1 }}@endif
                            </div>
                            <span class="text-[0.6rem] mt-1 whitespace-nowrap font-semibold
                                {{ $done || $current ? 'text-maroon' : 'text-[#ccc]' }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                        @if($i < count($currentOrderSteps) - 1)
                        <div class="h-[2px] flex-1 mx-1 mb-[18px] {{ $orderStepIndex > $i ? 'bg-maroon' : 'bg-[#eee]' }}"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- FOOTER --}}
            <div class="flex justify-between items-center px-4 sm:px-6 py-3 sm:py-4 border-t border-maroon-200">
                <div>
                    <div class="text-[0.78rem] sm:text-[0.82rem] text-[#999] mb-[3px]">Total Pembayaran</div>
                    <div class="font-extrabold text-maroon text-[0.95rem] sm:text-[1.05rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    @if($order->status === 'selesai')
                        @if($order->testimonial)
                            <span class="inline-flex items-center gap-1 text-green-700 text-[0.75rem] sm:text-[0.85rem] font-bold mr-1 sm:mr-2">
                                <i class="fas fa-check-circle"></i> Sudah Diulas
                            </span>
                        @else
                            <button class="inline-flex items-center gap-1.5 bg-white text-maroon px-3 sm:px-5 py-[7px] sm:py-[10px] rounded-[10px] font-bold text-[0.75rem] sm:text-[0.85rem] border-[1.5px] border-maroon hover:bg-maroon-100 transition-all cursor-pointer" onclick="toggleForm({{ $order->id }})">
                                <i class="fas fa-star text-[0.7rem]"></i> Tulis Ulasan
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center gap-1.5 bg-maroon text-white px-3 sm:px-5 py-[7px] sm:py-[10px] rounded-[10px] font-bold text-[0.75rem] sm:text-[0.85rem] no-underline hover:bg-maroon-dark hover:-translate-y-px transition-all">
                        <i class="fas fa-{{ $order->status === 'belum_bayar' ? 'credit-card' : 'eye' }} text-[0.7rem]"></i>
                        {{ $order->status === 'belum_bayar' ? 'Lihat Detail & Bayar' : 'Lihat Detail' }}
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
    </div>{{-- .date-group hari ini --}}
    @endif

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
        @php $group = in_array($order->status, $aktifStatuses) ? 'aktif' : ($order->status === 'selesai' ? 'selesai' : 'dibatalkan'); @endphp
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden transition-all duration-200 hover:shadow-[0_8px_30px_rgba(139,26,26,0.08)] hover:-translate-y-0.5 order-card" data-status="{{ $order->status }}" data-group="{{ $group }}">

            {{-- HEADER --}}
            <div class="flex justify-between items-start flex-wrap gap-y-2 px-4 sm:px-6 py-[18px] bg-[#fafafa] border-b border-maroon-200">
                <div>
                    <div class="font-extrabold text-[#1a1a1a] text-[0.92rem]">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-[0.82rem] text-[#999] mt-[3px]"><i class="fas fa-calendar-alt mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-[14px] py-[6px] rounded-[20px] text-[0.78rem] font-extrabold flex-shrink-0
                    @if($order->status === 'belum_bayar') bg-amber-50 text-orange-700
                    @elseif($order->status === 'pending') bg-yellow-50 text-yellow-800
                    @elseif($order->status === 'diproses') bg-blue-50 text-blue-800
                    @elseif($order->status === 'dikirim') bg-purple-50 text-purple-800
                    @elseif($order->status === 'siap_diambil') bg-orange-50 text-orange-700
                    @elseif($order->status === 'selesai') bg-green-100 text-green-800
                    @elseif($order->status === 'pembayaran') bg-teal-50 text-teal-700
                    @else bg-pink-100 text-red-700 @endif">
                    @if($order->status === 'belum_bayar') <i class="fas fa-hourglass-half"></i> Belum Bayar
                    @elseif($order->status === 'pending') <i class="fas fa-clock"></i> Menunggu
                    @elseif($order->status === 'diproses') <i class="fas fa-utensils"></i> Sedang Dimasak
                    @elseif($order->status === 'dikirim') <i class="fas fa-truck"></i> Sedang Dikirim
                    @elseif($order->status === 'siap_diambil') <i class="fas fa-store"></i> Siap Diambil
                    @elseif($order->status === 'selesai') <i class="fas fa-check-circle"></i> Selesai
                    @elseif($order->status === 'pembayaran') <i class="fas fa-credit-card"></i> Sudah Bayar
                    @else <i class="fas fa-times-circle"></i> Dibatalkan
                    @endif
                </span>
            </div>

            @if($order->status === 'belum_bayar')
            <div class="px-6 pt-3 -mb-1">
                <span class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-lg text-[0.78rem] text-amber-800">
                    <i class="fas fa-hourglass-half"></i>
                    <span>Bayar dalam</span>
                    <strong class="font-mono payment-countdown" data-expires-at="{{ $order->paymentExpiresAt()->toIso8601String() }}">--:--</strong>
                </span>
            </div>
            @endif

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
                        @if($order->tanggal_pengiriman)
                        <p class="text-[0.78rem] text-blue-600 mt-1 font-semibold">
                            <i class="fas fa-calendar-check text-[0.65rem]"></i>
                            Jadwal: {{ $order->tanggal_pengiriman->isToday() ? 'Hari ini' : $order->tanggal_pengiriman->translatedFormat('d M Y') }}@if($order->waktu_pengiriman) · {{ $order->waktu_pengiriman }}@endif
                        </p>
                        @endif
                    </div>
                </div>

                @if($order->status === 'dibatalkan' && $order->alasan_pembatalan)
                <div class="mt-4 flex items-start gap-2.5 px-4 py-3 bg-red-50 border border-red-200 rounded-xl">
                    <i class="fas fa-times-circle text-red-500 text-[0.9rem] mt-0.5 flex-shrink-0"></i>
                    <div class="min-w-0">
                        <p class="text-[0.72rem] font-bold text-red-600 uppercase tracking-wider mb-0.5">Alasan Pembatalan</p>
                        <p class="text-[0.85rem] text-red-800 leading-relaxed line-clamp-2">{{ $order->alasan_pembatalan }}</p>
                    </div>
                </div>
                @endif
                @if($order->status !== 'dibatalkan')
                @php
                    $isPickup       = ($order->metode_pengiriman ?? 'delivery') !== 'delivery';
                    $paidNotCooking = in_array($order->status, ['pembayaran', 'pending']);
                    $orderStepIndex = match($order->status) {
                        'belum_bayar'             => 0,
                        'pembayaran', 'pending'   => 0,
                        'diproses'                => 1,
                        'dikirim', 'siap_diambil' => 2,
                        'selesai'                 => 3,
                        default                   => -1,
                    };
                    $currentOrderSteps = [
                        ['label' => 'Pembayaran'],
                        ['label' => 'Dimasak'],
                        ['label' => $isPickup ? 'Siap Diambil' : 'Dikirim'],
                        ['label' => 'Selesai'],
                    ];
                @endphp
                <div class="mt-4 pt-3 border-t border-maroon-100 flex items-center">
                    @foreach($currentOrderSteps as $i => $step)
                    @php
                        $done    = ($orderStepIndex > $i) || ($paidNotCooking && $i === 0);
                        $current = ($orderStepIndex === $i) && !($paidNotCooking && $i === 0);
                    @endphp
                    <div class="flex items-center {{ $i < count($currentOrderSteps) - 1 ? 'flex-1' : '' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[0.6rem] font-bold shrink-0
                                {{ $done    ? 'bg-maroon text-white' : '' }}
                                {{ $current ? 'bg-maroon text-white ring-4 ring-maroon/20' : '' }}
                                {{ !$done && !$current ? 'bg-[#eee] text-[#bbb]' : '' }}">
                                @if($done)<i class="fas fa-check"></i>@else{{ $i + 1 }}@endif
                            </div>
                            <span class="text-[0.6rem] mt-1 whitespace-nowrap font-semibold
                                {{ $done || $current ? 'text-maroon' : 'text-[#ccc]' }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                        @if($i < count($currentOrderSteps) - 1)
                        <div class="h-[2px] flex-1 mx-1 mb-[18px] {{ $orderStepIndex > $i ? 'bg-maroon' : 'bg-[#eee]' }}"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- FOOTER --}}
            <div class="flex justify-between items-center px-4 sm:px-6 py-3 sm:py-4 border-t border-maroon-200">
                <div>
                    <div class="text-[0.78rem] sm:text-[0.82rem] text-[#999] mb-[3px]">Total Pembayaran</div>
                    <div class="font-extrabold text-maroon text-[0.95rem] sm:text-[1.05rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    @if($order->status === 'selesai')
                        @if($order->testimonial)
                            <span class="inline-flex items-center gap-1 text-green-700 text-[0.75rem] sm:text-[0.85rem] font-bold mr-1 sm:mr-2">
                                <i class="fas fa-check-circle"></i> Sudah Diulas
                            </span>
                        @else
                            <button class="inline-flex items-center gap-1.5 bg-white text-maroon px-3 sm:px-5 py-[7px] sm:py-[10px] rounded-[10px] font-bold text-[0.75rem] sm:text-[0.85rem] border-[1.5px] border-maroon hover:bg-maroon-100 transition-all cursor-pointer" onclick="toggleForm({{ $order->id }})">
                                <i class="fas fa-star text-[0.7rem]"></i> Tulis Ulasan
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center gap-1.5 bg-maroon text-white px-3 sm:px-5 py-[7px] sm:py-[10px] rounded-[10px] font-bold text-[0.75rem] sm:text-[0.85rem] no-underline hover:bg-maroon-dark hover:-translate-y-px transition-all">
                        <i class="fas fa-{{ $order->status === 'belum_bayar' ? 'credit-card' : 'eye' }} text-[0.7rem]"></i>
                        {{ $order->status === 'belum_bayar' ? 'Lihat Detail & Bayar' : 'Lihat Detail' }}
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

    </div>{{-- #ordersList --}}

    <div id="empty-aktif" class="hidden text-center py-14 bg-[#fafafa] rounded-2xl text-[#bbb] mt-2">
        <i class="fas fa-spinner text-2xl mb-3 block"></i>
        <p class="text-sm">Tidak ada pesanan aktif</p>
    </div>
    <div id="empty-selesai" class="hidden text-center py-14 bg-[#fafafa] rounded-2xl text-[#bbb] mt-2">
        <i class="fas fa-circle-check text-2xl mb-3 block"></i>
        <p class="text-sm">Belum ada pesanan selesai</p>
    </div>
    <div id="empty-dibatalkan" class="hidden text-center py-14 bg-[#fafafa] rounded-2xl text-[#bbb] mt-2">
        <i class="fas fa-circle-xmark text-2xl mb-3 block"></i>
        <p class="text-sm">Tidak ada pesanan dibatalkan</p>
    </div>
    @else
    {{-- Empty state global (gaya Riwayat Catering) --}}
    <div class="text-center py-20 bg-maroon-50 rounded-2xl">
        <i class="fas fa-box-open text-[3rem] text-[#ddd] mb-4 block"></i>
        <p class="text-[#888] mb-1 font-semibold">Belum ada pesanan</p>
        <p class="text-[0.82rem] text-[#bbb] mb-5">Yuk mulai pesan produk favorit dari Ummilaa Kitchen.</p>
        <a href="{{ route('catalogue') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-maroon text-white rounded-xl font-bold text-[0.85rem] no-underline hover:bg-maroon-dark transition-all">
            <i class="fas fa-utensils text-xs"></i> Belanja Sekarang
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Status tab active state via Tailwind classes
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

        document.querySelectorAll('.date-group').forEach(group => {
            const anyVisible = [...group.querySelectorAll('.order-card')].some(c => !c.classList.contains('hidden'));
            group.classList.toggle('hidden', !anyVisible);
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

    switchTab('{{ $defaultTab }}');

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

    // Countdown pembayaran untuk pesanan belum_bayar
    (function () {
        const elements = document.querySelectorAll('.payment-countdown');
        if (!elements.length) return;
        let needReload = false;
        function tick() {
            const now = Date.now();
            elements.forEach(el => {
                const target = new Date(el.dataset.expiresAt).getTime();
                const diff = Math.max(0, Math.floor((target - now) / 1000));
                const mm = String(Math.floor(diff / 60)).padStart(2, '0');
                const ss = String(diff % 60).padStart(2, '0');
                el.textContent = `${mm}:${ss}`;
                if (diff === 0 && !needReload) {
                    needReload = true;
                    setTimeout(() => window.location.reload(), 1500);
                }
            });
        }
        tick();
        setInterval(tick, 1000);
    })();

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
