@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
@endpush

@section('content')

{{-- ========== HERO ========== --}}
<section class="relative bg-white overflow-hidden border-b border-[#f0f0f0] flex flex-col justify-center" style="min-height: 85vh">

    <div class="relative z-10 max-w-[800px] mx-auto text-center px-5 md:px-10">
        <div data-aos="fade-down" data-aos-duration="600"
            class="inline-flex items-center gap-3 text-maroon text-[0.72rem] font-bold tracking-[2.5px] uppercase mb-7">
            <span class="w-8 h-px bg-maroon inline-block"></span>
            Tentang Kami
            <span class="w-8 h-px bg-maroon inline-block"></span>
        </div>

        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="80"
            class="font-playfair text-[2.6rem] md:text-[3.4rem] lg:text-[4.2rem] leading-[1.15] text-[#1a1a1a] mb-7 tracking-[-1px]">
            Dapur Penuh <em class="text-maroon not-italic">Cinta</em>,<br>Rasa yang Tak Terlupakan
        </h1>

        <p data-aos="fade-up" data-aos-duration="700" data-aos-delay="200"
            class="text-[#777] text-[1.05rem] leading-[1.9] max-w-[520px] mx-auto mb-10">
            Ummilaa Kitchen hadir untuk memudahkan Anda mendapatkan makanan lezat dan fresh. Dari camilan harian hingga catering acara spesial, semua kami siapkan dengan penuh dedikasi.
        </p>

        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="300"
            class="flex flex-wrap gap-4 items-center justify-center mb-16">
            <a href="{{ route('catalogue') }}"
                class="inline-flex items-center gap-[10px] bg-maroon text-white px-8 py-[15px] rounded-[50px] font-bold text-[0.92rem] no-underline transition-all duration-200 hover:-translate-y-1 hover:bg-maroon-dark hover:shadow-[0_16px_40px_rgba(139,26,26,0.3)] shadow-[0_6px_20px_rgba(139,26,26,0.2)]">
                <i class="fas fa-utensils"></i> Lihat Menu Kami
            </a>
        </div>

        {{-- 3 highlight pills --}}
        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="400"
            class="flex flex-wrap items-center justify-center gap-3">
            <div class="inline-flex items-center gap-2 bg-[#fdf6f3] border border-[#f5e8e3] text-[#555] px-5 py-2.5 rounded-full text-[0.82rem] font-semibold">
                <i class="fas fa-leaf text-maroon text-[0.8rem]"></i> Bahan Segar Pilihan
            </div>
            <div class="inline-flex items-center gap-2 bg-[#fdf6f3] border border-[#f5e8e3] text-[#555] px-5 py-2.5 rounded-full text-[0.82rem] font-semibold">
                <i class="fas fa-shield-alt text-maroon text-[0.8rem]"></i> Higienis & Terjamin
            </div>
            <div class="inline-flex items-center gap-2 bg-[#fdf6f3] border border-[#f5e8e3] text-[#555] px-5 py-2.5 rounded-full text-[0.82rem] font-semibold">
                <i class="fas fa-truck text-maroon text-[0.8rem]"></i> Pengiriman Cepat
            </div>
        </div>
    </div>

</section>

{{-- ========== CERITA / STORY ========== --}}
<section id="cerita-kami" class="bg-white px-5 md:px-10 lg:px-[80px] py-[80px] lg:py-[100px]">
    <div class="max-w-[1200px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

        {{-- Kiri: Teks + Mini Cards --}}
        <div data-aos="fade-right" data-aos-duration="800">
            <div class="flex items-center gap-2 text-[0.72rem] font-bold tracking-[2.5px] uppercase text-maroon mb-4">
                <span class="w-5 h-px bg-maroon inline-block"></span> Cerita Kami
            </div>
            <h2 class="font-playfair text-[2rem] md:text-[2.4rem] text-[#1a1a1a] leading-[1.25] mb-5">
                Menghadirkan<br><span class="text-maroon">Kualitas Terbaik</span>
            </h2>
            <p class="text-[#666] leading-[1.85] text-[0.97rem] mb-4">
                Ummilaa Kitchen lahir dari kecintaan terhadap kuliner dan semangat menghadirkan makanan berkualitas dengan harga terjangkau bagi masyarakat Malang dan sekitarnya.
            </p>
            <p class="text-[#666] leading-[1.85] text-[0.97rem] mb-8">
                Berawal dari dapur kecil di rumah, kami terus berkembang dan kini menawarkan berbagai produk mulai dari dimsum, crunchy series, perpentolan, risol, hingga layanan catering untuk berbagai acara.
            </p>

            {{-- Mini Feature Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-start gap-4 bg-[#fdf6f3] rounded-[16px] p-5 border border-[#f5e8e3]">
                    <div class="w-10 h-10 bg-white rounded-[12px] flex items-center justify-center text-maroon flex-shrink-0 shadow-sm border border-[#f5e8e3]">
                        <i class="fas fa-leaf text-[0.95rem]"></i>
                    </div>
                    <div>
                        <strong class="block text-[0.9rem] text-[#1a1a1a] mb-1">Bahan Segar</strong>
                        <span class="text-[0.8rem] text-[#888] leading-relaxed">Dipilih setiap hari untuk hasil terbaik di setiap gigitan.</span>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-[#fdf6f3] rounded-[16px] p-5 border border-[#f5e8e3]">
                    <div class="w-10 h-10 bg-white rounded-[12px] flex items-center justify-center text-maroon flex-shrink-0 shadow-sm border border-[#f5e8e3]">
                        <i class="fas fa-chart-line text-[0.95rem]"></i>
                    </div>
                    <div>
                        <strong class="block text-[0.9rem] text-[#1a1a1a] mb-1">Inovasi Menu</strong>
                        <span class="text-[0.8rem] text-[#888] leading-relaxed">Terus berkembang dengan varian baru yang mengikuti selera pasar.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan: Gambar + Badge Metrik --}}
        <div data-aos="fade-left" data-aos-duration="800" data-aos-delay="150" class="relative flex justify-center">
            <div class="relative w-full max-w-[440px]">
                <img class="w-full h-[380px] md:h-[460px] object-cover rounded-[24px] shadow-[0_24px_60px_rgba(0,0,0,0.12)]"
                    src="{{ asset('images/dapur.jpeg') }}" alt="Dapur Ummilaa Kitchen">

                {{-- Badge kiri atas --}}
                <div class="absolute -top-5 -left-5 bg-white rounded-[16px] px-5 py-4 shadow-[0_8px_32px_rgba(0,0,0,0.12)] flex items-center gap-3 border border-[#f0f0f0]">
                    <div class="w-10 h-10 bg-maroon rounded-[10px] flex items-center justify-center text-white flex-shrink-0">
                        <i class="fas fa-fire-alt"></i>
                    </div>
                    <div>
                        <strong class="block font-playfair text-[1.5rem] text-[#1a1a1a] leading-none">100+</strong>
                        <span class="text-[0.72rem] text-[#999]">Produk Tersedia</span>
                    </div>
                </div>

                {{-- Badge kanan bawah --}}
                <div class="absolute -bottom-5 -right-5 bg-white rounded-[16px] px-5 py-4 shadow-[0_8px_32px_rgba(0,0,0,0.12)] border border-[#f0f0f0]">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-green-400 animate-pulse flex-shrink-0"></div>
                        <span class="text-[0.72rem] text-[#888]">Kepuasan Pelanggan</span>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <strong class="font-playfair text-[1.8rem] text-maroon leading-none">95%</strong>
                        <span class="text-[0.72rem] text-[#aaa]">rating bintang 5</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- ========== VISI & MISI ========== --}}
<section class="bg-[#fdf6f3] px-5 md:px-10 lg:px-[80px] py-[80px] lg:py-[100px] border-y border-[#f5e8e3]">
    <div class="max-w-[1200px] mx-auto">
        <div data-aos="fade-up" data-aos-duration="700" class="text-center mb-14">
            <div class="flex items-center justify-center gap-2 text-[0.72rem] font-bold tracking-[2.5px] uppercase text-maroon mb-3">
                <span class="w-5 h-px bg-maroon inline-block"></span> Arah & Tujuan
            </div>
            <h2 class="font-playfair text-[2rem] md:text-[2.3rem] text-[#1a1a1a]">Visi & <span class="text-maroon">Misi</span></h2>
            <p class="text-[#999] text-[0.95rem] mt-3 max-w-[480px] mx-auto">Komitmen kami dalam menghadirkan yang terbaik untuk setiap pelanggan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-[960px] mx-auto">
            {{-- Visi --}}
            <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="0"
                class="bg-white rounded-[24px] p-9 shadow-[0_4px_24px_rgba(0,0,0,0.06)] border border-[#f5e8e3] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_48px_rgba(139,26,26,0.1)] hover:border-maroon-200 group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-maroon rounded-t-[24px] scale-x-0 origin-left transition-transform duration-300 group-hover:scale-x-100"></div>
                <div class="w-14 h-14 bg-[#fdf6f3] rounded-[16px] flex items-center justify-center text-maroon text-[1.4rem] mb-6 border border-[#f5e8e3]">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="font-playfair text-[1.5rem] text-[#1a1a1a] mb-4">Visi</h3>
                <p class="text-[#666] leading-[1.85] text-[0.95rem]">Menjadi usaha kuliner UMKM terpercaya di Malang yang dikenal atas kualitas rasa, kebersihan, dan pelayanan terbaik kepada setiap pelanggan.</p>
            </div>

            {{-- Misi --}}
            <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="120"
                class="bg-white rounded-[24px] p-9 shadow-[0_4px_24px_rgba(0,0,0,0.06)] border border-[#f5e8e3] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_48px_rgba(139,26,26,0.1)] hover:border-maroon-200 group relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-maroon rounded-t-[24px] scale-x-0 origin-left transition-transform duration-300 group-hover:scale-x-100"></div>
                <div class="w-14 h-14 bg-[#fdf6f3] rounded-[16px] flex items-center justify-center text-maroon text-[1.4rem] mb-6 border border-[#f5e8e3]">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="font-playfair text-[1.5rem] text-[#1a1a1a] mb-4">Misi</h3>
                <ul class="list-none m-0">
                    @foreach([
                        'Menyajikan produk kuliner berkualitas dengan bahan-bahan pilihan yang segar dan higienis.',
                        'Memberikan kemudahan akses informasi produk dan pemesanan melalui platform digital.',
                        'Menghadirkan pelayanan yang ramah, cepat, dan memuaskan untuk setiap pelanggan.',
                        'Terus berinovasi dalam menu dan layanan demi memenuhi kebutuhan pelanggan.',
                    ] as $misi)
                    <li class="flex items-start gap-3 text-[#666] text-[0.93rem] leading-[1.75] mb-3 pb-3 border-b border-[#fdf0eb] last:mb-0 last:pb-0 last:border-0">
                        <div class="w-5 h-5 bg-maroon rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-white text-[0.6rem]"></i>
                        </div>
                        {{ $misi }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ========== TESTIMONI MARQUEE ========== --}}
<section class="bg-white px-5 md:px-10 lg:px-[80px] py-[80px] lg:py-[100px] overflow-hidden">
    <div data-aos="fade-up" data-aos-duration="700" class="text-center mb-14">
        <div class="flex items-center justify-center gap-2 text-[0.72rem] font-bold tracking-[2.5px] uppercase text-maroon mb-3">
            <span class="w-5 h-px bg-maroon inline-block"></span> Apa Kata Mereka
        </div>
        <h2 class="font-playfair text-[2rem] md:text-[2.3rem] text-[#1a1a1a]">Cerita <span class="text-maroon">Pelanggan</span></h2>
        <p class="text-[#999] mt-3 text-[0.95rem]">Kepuasan pelanggan adalah prioritas utama kami</p>
    </div>

    @if($testimonials->isEmpty())
        <div class="text-center px-10 py-[60px] text-[#bbb] text-[0.95rem]">
            <i class="fas fa-comment-slash text-[2.5rem] mb-[14px] block text-[#ddd]"></i>
            Belum ada testimoni yang disetujui.
        </div>
    @else
        <div class="relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-[140px] z-[2] pointer-events-none bg-gradient-to-r from-white to-transparent"></div>
            <div class="absolute right-0 top-0 bottom-0 w-[140px] z-[2] pointer-events-none bg-gradient-to-l from-white to-transparent"></div>
            <div class="flex gap-6 w-max py-4 pb-8 animate-marquee hover:[animation-play-state:paused]">
                @foreach([1,2] as $loop)
                    @foreach($testimonials as $t)
                    <div class="bg-white rounded-[20px] p-7 border border-[#ebebeb] shadow-[0_4px_20px_rgba(0,0,0,0.06)] transition-all duration-300 relative w-[320px] flex-shrink-0 hover:-translate-y-2 hover:shadow-[0_16px_48px_rgba(139,26,26,0.1)] hover:border-maroon-200">
                        <div class="absolute top-4 right-4 bg-[#fdf6f3] text-maroon rounded-full px-3 py-1 text-[0.72rem] font-bold border border-[#f5e8e3]">{{ $t->rating }}/5</div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="text-[0.9rem] tracking-[2px]">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $t->rating ? 'text-[#F5A623]' : 'text-[#e0e0e0]' }}">★</span>
                                @endfor
                            </div>
                            <div class="text-[2.4rem] text-[#f0e0e0] font-[Georgia,serif] leading-[0.8]">"</div>
                        </div>
                        <q class="text-[#555] text-[0.92rem] leading-[1.85] italic block mb-6 [quotes:none]">{{ $t->komentar }}</q>
                        <div class="flex items-center gap-3 pt-4 border-t border-[#f0f0f0]">
                            <div class="w-11 h-11 rounded-full bg-maroon flex items-center justify-center text-white font-extrabold text-[1rem] flex-shrink-0 shadow-[0_4px_12px_rgba(139,26,26,0.2)]">
                                {{ strtoupper(substr($t->nama, 0, 1)) }}
                            </div>
                            <div>
                                <strong class="block text-[#1a1a1a] text-[0.88rem]">{{ $t->nama }}</strong>
                                <span class="text-[#bbb] text-[0.75rem]">Pelanggan Setia</span>
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
    AOS.init({
        duration: 700,
        easing: 'ease-out-cubic',
        once: true,
        offset: 60,
    });
</script>
@endpush
