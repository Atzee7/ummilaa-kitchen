@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css"/>
<style>
#pkgSlider::-webkit-scrollbar { display: none; }
#pkgSlider { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="px-4 sm:px-10 lg:px-[80px] pt-12 pb-10 lg:pt-16 lg:pb-14 relative overflow-hidden
                before:content-[''] before:absolute before:top-[-100px] before:left-1/2 before:-translate-x-1/2
                before:w-[700px] before:h-[400px] before:rounded-full
                before:bg-[radial-gradient(circle,#fdf0f0_0%,transparent_70%)] before:z-0">
    <div class="relative z-[1] max-w-2xl mx-auto text-center">

        <div data-aos="fade-down" data-aos-duration="600"
             class="inline-flex items-center gap-2 bg-maroon-100 text-maroon px-[18px] py-2 rounded-[30px] text-[0.78rem] font-bold tracking-[1px] uppercase mb-5">
            <i class="fas fa-utensils text-[0.7rem]"></i> Layanan Catering
        </div>

        <h1 data-aos="fade-up" data-aos-duration="700" data-aos-delay="100"
            class="font-playfair text-[2.4rem] sm:text-[3rem] lg:text-[3.5rem] text-[#1a1a1a] leading-[1.2] mb-4">
            Sajian Istimewa untuk<br>Setiap <span class="text-maroon">Acara Anda</span>
        </h1>

        <p data-aos="fade-up" data-aos-duration="700" data-aos-delay="200"
           class="text-[#777] text-[0.97rem] leading-[1.8] mb-8">
            Kami hadir untuk mewujudkan acara Anda dengan cita rasa terbaik.<br class="hidden sm:block">
            Pilih paket catering sesuai kebutuhan Anda.
        </p>

        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="300"
             class="flex items-center justify-center gap-4 flex-wrap">
            <a href="{{ route('catering.checkout') }}"
               class="inline-flex items-center gap-2 bg-maroon text-white px-7 py-[13px] rounded-xl font-bold text-[0.92rem] no-underline
                      shadow-[0_8px_24px_rgba(139,26,26,0.25)] hover:opacity-90 hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-pen-to-square text-sm"></i> Pesan Sekarang
            </a>
            <a href="{{ route('catering.history') }}"
               class="inline-flex items-center gap-2 text-maroon font-bold text-[0.92rem] no-underline hover:gap-3 transition-all duration-200">
                Riwayat Pesanan <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>

    </div>
</section>

{{-- DIVIDER --}}
<div class="px-4 sm:px-10 lg:px-[80px] mb-8">
    <div class="border-t-[1.5px] border-maroon-200"></div>
</div>

{{-- PACKAGES --}}
<div class="pb-16">

    @if($packages->isEmpty())
    <div class="mx-4 sm:mx-10 lg:mx-[80px] py-20 text-center bg-maroon-50 rounded-2xl mb-10" data-aos="fade-up">
        <i class="fas fa-bowl-food text-[3rem] text-[#ccc] mb-4 block"></i>
        <p class="text-[#888] text-sm mb-1">Belum ada paket catering tersedia.</p>
        <p class="text-[0.82rem] text-[#bbb]">Silakan cek kembali nanti.</p>
    </div>

    @else
    {{-- SLIDER --}}
    <div class="px-4 sm:px-10 lg:px-[80px] mb-6" data-aos="fade-up" data-aos-duration="600">
        <h2 class="font-playfair text-[1.3rem] text-[#1a1a1a]">Paket Catering</h2>
        <span class="text-xs text-[#bbb] font-semibold">{{ $packages->count() }} paket tersedia</span>
    </div>

    <div class="flex items-center gap-3 px-4 sm:px-10 lg:px-[80px] mb-14" data-aos="fade-up" data-aos-duration="650">

        {{-- Arrow kiri --}}
        <button id="prevBtn"
            class="shrink-0 w-10 h-10 rounded-full bg-white border-[1.5px] border-maroon-200 text-maroon
                   flex items-center justify-center shadow-sm transition-all duration-200
                   hover:bg-maroon hover:text-white hover:border-maroon">
            <i class="fas fa-chevron-left text-sm"></i>
        </button>

        {{-- Track wrapper --}}
        <div class="flex-1 overflow-hidden" id="sliderWrapper">
            <div id="pkgTrack" class="flex gap-4 md:gap-6 transition-transform duration-500">
                @foreach($packages as $package)
                <div class="group shrink-0 w-[calc(50%-8px)] md:w-[calc(33.333%-11px)]
                            rounded-[18px] overflow-hidden bg-white border-[1.5px] border-maroon-200
                            transition-all duration-300
                            hover:shadow-[0_16px_48px_rgba(139,26,26,0.1)] hover:-translate-y-[5px]">
                    <a href="{{ route('catering.package', $package->id) }}" class="no-underline block">
                    <div class="relative overflow-hidden aspect-[4/3] md:aspect-auto md:h-[200px] bg-maroon-50">
                        @if($package->image)
                            <img src="{{ asset('storage/' . $package->image) }}"
                                 alt="{{ $package->name }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-[400ms] group-hover:scale-[1.06]">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-bowl-food text-4xl text-[#c5b8ae]"></i>
                            </div>
                        @endif
                        @if($package->min_pax)
                        <span class="absolute top-2 left-2 md:top-3 md:left-3 bg-white/90 backdrop-blur-sm text-maroon text-[0.65rem] md:text-[0.72rem] font-extrabold px-2 md:px-3 py-0.5 md:py-1 rounded-full">
                            Min. {{ $package->min_pax }} pax
                        </span>
                        @endif
                    </div>
                    <div class="p-3 md:p-[18px]">
                        <h3 class="font-extrabold text-[#1a1a1a] text-[0.88rem] md:text-[1rem] mb-1 line-clamp-1">{{ $package->name }}</h3>
                        @if($package->description)
                        <p class="hidden md:block text-[0.78rem] text-[#aaa] leading-relaxed mb-2 line-clamp-2">{{ $package->description }}</p>
                        @else
                        <div class="hidden md:block mb-2"></div>
                        @endif
                        <div class="flex items-center justify-between mt-2 md:mt-0">
                            <div>
                                <p class="text-[0.6rem] md:text-[0.68rem] text-[#bbb] leading-none mb-0.5">mulai dari</p>
                                <p class="font-extrabold text-maroon text-[0.88rem] md:text-base leading-none">
                                    Rp{{ number_format($package->price_per_pax, 0, ',', '.') }}
                                    <span class="text-[0.65rem] md:text-[0.72rem] text-[#aaa] font-semibold">/ pax</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    </a>
                    <div class="px-3 pb-3 md:px-[18px] md:pb-[18px]">
                        <a href="{{ route('catering.checkout', ['package' => $package->id]) }}"
                           class="w-full py-2 bg-maroon text-white rounded-[10px] flex items-center justify-center gap-1.5 text-[0.78rem] md:text-[0.82rem] font-bold no-underline hover:opacity-90 transition-opacity">
                            <i class="fas fa-pen-to-square text-[0.72rem]"></i> Pesan Paket Ini
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Arrow kanan --}}
        <button id="nextBtn"
            class="shrink-0 w-10 h-10 rounded-full bg-white border-[1.5px] border-maroon-200 text-maroon
                   flex items-center justify-center shadow-sm transition-all duration-200
                   hover:bg-maroon hover:text-white hover:border-maroon">
            <i class="fas fa-chevron-right text-sm"></i>
        </button>

    </div>
    @endif


</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 650, easing: 'ease-out-cubic', once: true, offset: 50 });</script>
@if(!$packages->isEmpty())
<script>
(function () {
    const track   = document.getElementById('pkgTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    if (!track) return;

    let index = 0;

    function visible() {
        return window.innerWidth >= 768 ? 3 : 2;
    }

    function cardStep() {
        const card = track.children[0];
        const gap  = parseFloat(getComputedStyle(track).gap) || 16;
        return card ? card.offsetWidth + gap : 300;
    }

    function maxIndex() {
        return Math.max(0, track.children.length - visible());
    }

    function update() {
        track.style.transform = `translateX(-${index * cardStep()}px)`;
        prevBtn.style.opacity = index <= 0 ? '0.35' : '1';
        nextBtn.style.opacity = index >= maxIndex() ? '0.35' : '1';
        prevBtn.disabled = index <= 0;
        nextBtn.disabled = index >= maxIndex();
    }

    prevBtn.addEventListener('click', () => { if (index > 0) { index--; update(); } });
    nextBtn.addEventListener('click', () => { if (index < maxIndex()) { index++; update(); } });

    window.addEventListener('resize', () => { index = Math.min(index, maxIndex()); update(); });

    update();
})();
</script>
@endif
@endpush
