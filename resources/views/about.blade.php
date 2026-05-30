@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    .hero-line {
        width: 0;
        animation: expandLine 0.8s ease forwards 0.6s;
    }
    @keyframes expandLine {
        to { width: 40px; }
    }
</style>
@endpush

@section('content')

{{-- ================================================================
     HERO — 85vh, centered, elegant
================================================================ --}}
<section class="relative bg-white flex flex-col justify-center overflow-hidden border-b border-[#f0eeec]"
    style="min-height: 85vh">

    {{-- Faint corner ornaments --}}
    <div class="absolute top-10 left-10 w-24 h-24 border border-[#f0e8e8] rounded-full pointer-events-none opacity-60"></div>
    <div class="absolute top-16 left-16 w-12 h-12 border border-[#f0e8e8] rounded-full pointer-events-none opacity-40"></div>
    <div class="absolute bottom-14 right-12 w-20 h-20 border border-[#f0e8e8] rounded-full pointer-events-none opacity-50"></div>
    <div class="absolute bottom-20 right-20 w-8 h-8 border border-[#f0e8e8] rounded-full pointer-events-none opacity-30"></div>

    <div class="relative z-10 max-w-[720px] mx-auto text-center px-6">

        {{-- Label --}}
        <div data-aos="fade-down" data-aos-duration="600"
            class="inline-flex items-center gap-3 text-maroon text-[0.7rem] font-bold tracking-[3px] uppercase mb-7">
            <span class="hero-line h-px bg-maroon inline-block"></span>
            Tentang Kami
            <span class="hero-line h-px bg-maroon inline-block"></span>
        </div>

        {{-- Heading --}}
        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100"
            class="font-playfair text-[2.8rem] md:text-[3.6rem] lg:text-[4.4rem] leading-[1.15] text-[#1a1a1a] mb-6 tracking-[-1.5px]">
            Makanan Enak,<br>
            <em class="text-maroon not-italic">Harga Bersahabat</em>
        </h1>

        {{-- Divider --}}
        <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="200"
            class="flex items-center justify-center gap-3 mb-6">
            <div class="h-px w-12 bg-gradient-to-r from-transparent to-maroon-200"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-maroon opacity-40"></div>
            <div class="w-2 h-2 rounded-full bg-maroon opacity-70"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-maroon opacity-40"></div>
            <div class="h-px w-12 bg-gradient-to-l from-transparent to-maroon-200"></div>
        </div>

        {{-- Sub --}}
        <p data-aos="fade-up" data-aos-duration="700" data-aos-delay="250"
            class="text-[#888] text-[1rem] leading-[1.9] max-w-[500px] mx-auto mb-10">
            Ummilaa Kitchen hadir untuk memudahkan Anda mendapatkan makanan lezat dan <em>fresh</em>. Dari camilan harian hingga catering acara spesial, semua kami siapkan dengan penuh dedikasi.
        </p>

        {{-- CTA --}}
        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="320"
            class="flex flex-wrap gap-3 items-center justify-center mb-12">
            <a href="{{ route('catalogue') }}"
                class="inline-flex items-center gap-2 bg-maroon text-white px-8 py-[14px] rounded-full font-bold text-[0.88rem] tracking-wide no-underline transition-all duration-250 hover:-translate-y-0.5 hover:shadow-[0_12px_36px_rgba(139,26,26,0.3)] shadow-[0_4px_18px_rgba(139,26,26,0.22)]">
                <i class="fas fa-utensils text-[0.8rem]"></i> Lihat Menu Kami
            </a>
            <a href="#cerita-label"
                class="inline-flex items-center gap-2 border border-[#e8e0e0] text-[#666] px-7 py-[13px] rounded-full font-semibold text-[0.88rem] no-underline hover:border-maroon hover:text-maroon transition-all duration-200">
                Cerita Kami <i class="fas fa-arrow-down text-[0.72rem]"></i>
            </a>
        </div>


    </div>
</section>


{{-- ================================================================
     CERITA / STORY
================================================================ --}}
<section id="cerita-kami" class="scroll-mt-24 bg-white px-5 md:px-12 lg:px-[80px] py-14 sm:py-[60px] lg:py-[110px]">
    <div class="max-w-[1160px] mx-auto">

        {{-- Label center --}}
        <div id="cerita-label" data-aos="fade-up" data-aos-duration="600" class="text-center mb-12">
            <div class="flex items-center justify-center gap-3 text-[0.7rem] font-bold tracking-[3px] uppercase text-maroon">
                <span class="w-6 h-px bg-maroon"></span> Cerita Kami <span class="w-6 h-px bg-maroon"></span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 lg:gap-24 items-center">

        {{-- Kiri: Teks --}}
        <div data-aos="fade-right" data-aos-duration="800">
            <h2 class="font-playfair text-[1.8rem] md:text-[2.6rem] text-[#1a1a1a] leading-[1.25] mb-6 tracking-[-0.5px]">
                Berawal dari Dapur Rumahan,<br class="hidden md:block"> Tumbuh Bersama <span class="text-maroon">Pelanggan Kami</span>
            </h2>
            <p class="text-[#777] leading-[1.95] text-[0.96rem] mb-4">
                Ummilaa Kitchen lahir dari kecintaan mendalam terhadap dunia kuliner dan tekad untuk menghadirkan makanan berkualitas dengan harga yang bersahabat bagi masyarakat Malang.
            </p>
            <p class="text-[#777] leading-[1.95] text-[0.96rem]">
                Berawal dari dapur kecil di rumah sejak 2025, kini kami menawarkan berbagai produk — dimsum, crunchy series, perpentolan, risol — hingga layanan catering untuk berbagai acara spesial.
            </p>

        </div>

        {{-- Kanan: Gambar --}}
        <div data-aos="fade-left" data-aos-duration="800" data-aos-delay="120"
            class="relative flex justify-center lg:justify-end">
            <div class="relative w-full max-w-[420px]">

                {{-- Decorative frame behind image --}}
                <div class="absolute -bottom-4 -right-4 w-full h-full rounded-[28px] border-2 border-[#f0e4df] pointer-events-none"></div>

                <img class="relative w-full h-[420px] md:h-[500px] object-cover rounded-[28px] shadow-[0_20px_60px_rgba(139,26,26,0.12)]"
                    src="{{ asset('images/dapur.jpeg') }}" alt="Dapur Ummilaa Kitchen">

                {{-- Badge atas kiri --}}
                <div class="absolute -top-5 -left-5 bg-white rounded-2xl px-5 py-3.5 shadow-[0_8px_32px_rgba(0,0,0,0.1)] flex items-center gap-3 border border-[#f5f5f5]">
                    <div class="w-9 h-9 bg-maroon rounded-[10px] flex items-center justify-center text-white flex-shrink-0">
                        <i class="fas fa-fire-alt text-[0.8rem]"></i>
                    </div>
                    <div>
                        <strong class="block font-playfair text-[1.4rem] text-[#1a1a1a] leading-none">100+</strong>
                        <span class="text-[0.68rem] text-[#aaa]">Produk Tersedia</span>
                    </div>
                </div>

                {{-- Badge bawah kanan --}}
                <div class="absolute -bottom-5 -right-9 bg-white rounded-2xl px-5 py-3.5 shadow-[0_8px_32px_rgba(0,0,0,0.1)] border border-[#f5f5f5]">
                    <div class="flex items-center gap-1.5 mb-1">
                        <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                        <span class="text-[0.68rem] text-[#aaa]">Kepuasan Pelanggan</span>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <strong class="font-playfair text-[1.7rem] text-maroon leading-none">95%</strong>
                        <span class="text-[0.68rem] text-[#bbb]">bintang 5</span>
                    </div>
                </div>

            </div>
        </div>

        </div>{{-- end grid --}}
    </div>{{-- end max-w --}}
</section>

{{-- ================================================================
     TESTIMONI
================================================================ --}}
<section class="bg-white border-t border-[#f0eeec] px-5 md:px-12 lg:px-[80px] py-[80px] lg:py-[100px] overflow-hidden">

    <div data-aos="fade-up" data-aos-duration="700" class="text-center mb-14">
        <div class="flex items-center justify-center gap-3 text-[0.7rem] font-bold tracking-[3px] uppercase text-maroon mb-3">
            <span class="w-6 h-px bg-maroon"></span> Apa Kata Mereka <span class="w-6 h-px bg-maroon"></span>
        </div>
        <h2 class="font-playfair text-[2rem] md:text-[2.4rem] text-[#1a1a1a] tracking-[-0.5px]">
            Cerita <span class="text-maroon">Pelanggan</span>
        </h2>
        <p class="text-[#aaa] text-[0.92rem] mt-3">Kepuasan pelanggan adalah prioritas utama kami</p>
    </div>

    @if($testimonials->isEmpty())
        <div class="text-center py-16 text-[#ccc] text-[0.9rem]">
            <i class="fas fa-comment-slash text-[2rem] block mb-3 text-[#e0e0e0]"></i>
            Belum ada testimoni yang disetujui.
        </div>
    @else
        <div class="relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-32 z-10 pointer-events-none bg-gradient-to-r from-white to-transparent"></div>
            <div class="absolute right-0 top-0 bottom-0 w-32 z-10 pointer-events-none bg-gradient-to-l from-white to-transparent"></div>
            <div class="flex gap-5 w-max py-4 pb-6 animate-marquee hover:[animation-play-state:paused]">
                @foreach([1,2] as $loop)
                    @foreach($testimonials as $t)
                    <div class="bg-white rounded-[20px] p-7 border border-[#f0e4df] shadow-[0_2px_20px_rgba(139,26,26,0.05)] w-[300px] flex-shrink-0 hover:-translate-y-1.5 hover:shadow-[0_16px_48px_rgba(139,26,26,0.1)] transition-all duration-300">
                        {{-- Stars --}}
                        <div class="flex gap-0.5 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-[0.85rem] {{ $i <= $t->rating ? 'text-amber-400' : 'text-[#e8e8e8]' }}">★</span>
                            @endfor
                        </div>
                        {{-- Quote --}}
                        <q class="text-[#555] text-[0.9rem] leading-[1.85] italic block mb-6 [quotes:none]">{{ $t->komentar }}</q>
                        {{-- Author --}}
                        <div class="flex items-center gap-3 pt-4 border-t border-[#f8f0ee]">
                            <div class="w-10 h-10 rounded-full bg-maroon flex items-center justify-center text-white font-bold text-[0.95rem] flex-shrink-0 shadow-[0_3px_10px_rgba(139,26,26,0.2)]">
                                {{ strtoupper(substr($t->nama, 0, 1)) }}
                            </div>
                            <div>
                                <strong class="block text-[#1a1a1a] text-[0.85rem]">{{ $t->nama }}</strong>
                                <span class="text-[#bbb] text-[0.74rem]">{{ $t->catering_order_id ? 'Pesanan Catering' : 'Pesanan Katalog' }}</span>
                            </div>
                            <div class="ml-auto bg-[#fdf6f3] text-maroon text-[0.7rem] font-bold px-2.5 py-1 rounded-full border border-[#f0e4df]">
                                {{ $t->rating }}/5
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif

</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 60 });

    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href').slice(1);
            const target = document.getElementById(targetId);
            if (!target) return;
            e.preventDefault();
            const stickyNav = document.querySelector('.sticky');
            const navHeight = stickyNav ? stickyNav.offsetHeight : 0;
            // Gunakan offsetTop (tidak terpengaruh CSS transform dari AOS)
            let absoluteTop = 0;
            let el = target;
            while (el) {
                absoluteTop += el.offsetTop;
                el = el.offsetParent;
            }
            const top = absoluteTop - navHeight - 24;
            window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
        });
    });
</script>
@endpush
