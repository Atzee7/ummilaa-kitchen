@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-10 lg:px-[80px] py-8 lg:py-[50px] min-h-[70vh]">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="font-playfair text-[1.8rem] sm:text-[2.4rem] text-[#1a1a1a] mb-1">Catering Ummilaa Kitchen</h1>
            <p class="text-[#888] text-[0.9rem]">Pilih paket catering untuk acara Anda, atau ajukan pesanan custom sesuai kebutuhan.</p>
        </div>
        <a href="{{ route('catering.history') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 border-[1.5px] border-maroon text-maroon rounded-xl font-bold text-[0.85rem] no-underline hover:bg-maroon-50 transition-all whitespace-nowrap">
            <i class="fas fa-clock-rotate-left"></i> Riwayat Catering
        </a>
    </div>

    {{-- GRID PAKET --}}
    @if($packages->isEmpty())
        <div class="text-center py-16 bg-maroon-50 rounded-2xl mb-8">
            <div class="text-5xl mb-3">🍱</div>
            <p class="text-[#888]">Belum ada paket catering tersedia. Anda tetap bisa mengajukan pesanan custom di bawah.</p>
        </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
        @foreach($packages as $package)
        <div class="bg-white border border-maroon-200 rounded-[18px] overflow-hidden flex flex-col hover:shadow-lg transition-shadow">
            <div class="aspect-[16/10] bg-maroon-50 overflow-hidden">
                @if($package->image)
                    <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-5xl">🍱</div>
                @endif
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h3 class="font-playfair text-[1.15rem] text-[#1a1a1a] mb-1">{{ $package->name }}</h3>
                <p class="text-[0.82rem] text-[#888] mb-3 line-clamp-2 flex-1">{{ $package->description }}</p>
                <div class="mb-3">
                    <span class="text-maroon font-extrabold text-[1.1rem]">Rp{{ number_format($package->price_per_pax, 0, ',', '.') }}</span>
                    <span class="text-[0.78rem] text-[#999]">/ pax</span>
                    @if($package->min_pax)
                        <span class="block text-[0.72rem] text-[#aaa] mt-0.5">Min. {{ $package->min_pax }} pax</span>
                    @endif
                </div>
                <a href="{{ route('catering.checkout', ['package' => $package->id]) }}"
                   class="block text-center py-2.5 bg-maroon text-white rounded-xl font-bold text-[0.85rem] no-underline hover:bg-maroon-dark transition-all">
                    Pilih Paket
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- CUSTOM CTA --}}
    <div class="bg-maroon rounded-[20px] p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-5 text-white">
        <div>
            <h2 class="font-playfair text-[1.4rem] sm:text-[1.7rem] mb-1">Punya kebutuhan khusus?</h2>
            <p class="text-white/80 text-[0.88rem]">Ajukan pesanan catering custom — menu, jumlah, dan budget sesuai acara Anda.</p>
        </div>
        <a href="{{ route('catering.checkout') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-maroon rounded-xl font-bold text-[0.9rem] no-underline hover:bg-maroon-50 transition-all whitespace-nowrap">
            <i class="fas fa-pen-to-square"></i> Pesan Custom
        </a>
    </div>

</div>
@endsection
