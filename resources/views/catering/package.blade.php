@extends('layouts.app')

@section('content')
<div class="px-4 md:px-10 lg:px-[80px] py-10 lg:py-[60px]">

    {{-- Back link --}}
    <a href="{{ route('catering.index') }}" class="inline-flex items-center gap-2 text-[0.85rem] text-[#888] hover:text-maroon no-underline mb-8 transition-colors duration-200">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke Catering
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-10 lg:gap-[60px] items-start">

        {{-- KOLOM KIRI: Gambar --}}
        <div class="relative">
            @if($package->image)
                <img src="{{ asset('storage/' . $package->image) }}"
                     alt="{{ $package->name }}"
                     class="w-full h-[280px] sm:h-[380px] lg:h-[460px] object-cover rounded-[24px]
                            shadow-[0_20px_60px_rgba(139,26,26,0.15)]">
            @else
                <div class="w-full h-[280px] sm:h-[380px] lg:h-[460px] bg-maroon-50 rounded-[24px]
                            shadow-[0_20px_60px_rgba(139,26,26,0.08)]
                            flex items-center justify-center">
                    <i class="fas fa-bowl-food text-[4rem] text-[#c5b8ae]"></i>
                </div>
            @endif

        </div>

        {{-- KOLOM KANAN: Info (sticky) --}}
        <div class="lg:sticky lg:top-[90px]">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-1.5 bg-maroon-100 text-maroon px-4 py-1.5 rounded-[20px] text-[0.78rem] font-extrabold tracking-[1px] uppercase mb-4">
                <i class="fas fa-utensils text-[0.7rem]"></i> Paket Catering
            </div>

            {{-- Nama --}}
            <h1 class="font-playfair text-[1.6rem] sm:text-[2rem] lg:text-[2.4rem] text-[#1a1a1a] mb-3 leading-tight">
                {{ $package->name }}
            </h1>

            {{-- Harga --}}
            <div class="flex items-baseline gap-2 mb-5">
                <span class="text-[0.78rem] text-[#bbb] font-semibold">mulai dari</span>
                <span class="text-[1.3rem] sm:text-[1.6rem] lg:text-[1.8rem] font-extrabold text-maroon leading-none">
                    Rp{{ number_format($package->price_per_pax, 0, ',', '.') }}
                </span>
                <span class="text-[0.85rem] text-[#aaa] font-semibold">/ pax</span>
            </div>

            {{-- Estimasi harga --}}
            @if($package->min_pax)
            <div class="bg-maroon-50 rounded-xl px-4 py-3.5 mb-5 flex items-start gap-3">
                <i class="fas fa-calculator text-maroon mt-0.5 shrink-0 text-[0.85rem]"></i>
                <div>
                    <p class="text-[0.72rem] font-bold uppercase tracking-widest text-[#999] mb-0.5">Estimasi Harga</p>
                    <p class="text-[0.9rem] font-extrabold text-maroon leading-snug">
                        Rp{{ number_format($package->price_per_pax * $package->min_pax, 0, ',', '.') }}
                        <span class="text-[0.78rem] font-semibold text-[#aaa]">untuk {{ $package->min_pax }} pax</span>
                    </p>
                </div>
            </div>
            @endif

            <div class="h-px bg-maroon-200 mb-5"></div>

            {{-- Deskripsi --}}
            @if($package->description)
            <div class="mb-6">
                <p class="text-[0.72rem] font-extrabold tracking-[2px] uppercase text-[#bbb] mb-2">Deskripsi Paket</p>
                <p class="text-[#666] leading-[1.8] text-[0.92rem] whitespace-pre-line">{{ $package->description }}</p>
            </div>
            @endif

            {{-- Info chips --}}
            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="bg-white border border-maroon-200 rounded-xl px-4 py-3 text-center shadow-sm">
                    <p class="text-[0.65rem] font-bold uppercase tracking-widest text-[#bbb] mb-1">Harga / Pax</p>
                    <p class="font-extrabold text-maroon text-[0.9rem]">Rp{{ number_format($package->price_per_pax, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white border border-maroon-200 rounded-xl px-4 py-3 text-center shadow-sm">
                    <p class="text-[0.65rem] font-bold uppercase tracking-widest text-[#bbb] mb-1">Min. Order</p>
                    <p class="font-extrabold text-[#1a1a1a] text-[0.9rem]">{{ $package->min_pax ? $package->min_pax . ' pax' : '-' }}</p>
                </div>
                <div class="bg-white border border-maroon-200 rounded-xl px-4 py-3 text-center shadow-sm">
                    <p class="text-[0.65rem] font-bold uppercase tracking-widest text-[#bbb] mb-1">Maks. Order</p>
                    <p class="font-extrabold text-[#1a1a1a] text-[0.9rem]">{{ $package->max_pax ? $package->max_pax . ' pax' : '-' }}</p>
                </div>
            </div>

            {{-- CTA Buttons --}}
            <div class="flex flex-col gap-3">
                <a href="{{ route('catering.checkout', ['package' => $package->id]) }}"
                   class="w-full py-[13px] bg-maroon text-white border-none rounded-xl text-[0.95rem] font-extrabold
                          flex items-center justify-center gap-2 no-underline
                          hover:bg-maroon-dark hover:-translate-y-px hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)]
                          transition-all duration-200">
                    <i class="fas fa-pen-to-square text-sm"></i> Pesan Paket Ini
                </a>
            </div>

            {{-- Note --}}
            <p class="text-center text-[0.75rem] text-[#bbb] mt-3 leading-relaxed">
                <i class="fas fa-info-circle text-[0.7rem] mr-1"></i>
                Harga final ditentukan setelah konfirmasi dengan admin via WhatsApp
            </p>

        </div>
    </div>
</div>
@endsection
