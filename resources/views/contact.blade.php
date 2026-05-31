@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
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
     HERO — 85vh, white, elegant (same theme as About Us)
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
            Hubungi Kami
            <span class="hero-line h-px bg-maroon inline-block"></span>
        </div>

        {{-- Heading --}}
        <h1 data-aos="fade-up" data-aos-duration="800" data-aos-delay="100"
            class="font-playfair text-[2.8rem] md:text-[3.6rem] lg:text-[4.4rem] leading-[1.15] text-[#1a1a1a] mb-6 tracking-[-1.5px]">
            Kami Siap <em class="text-maroon not-italic">Membantu</em><br>
            Setiap Saat
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
            Ada pertanyaan, pemesanan khusus, atau ingin tahu lebih lanjut tentang produk kami? Jangan ragu — tim kami siap merespons dengan cepat dan ramah.
        </p>

        {{-- CTA --}}
        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="320"
            class="flex flex-wrap gap-3 items-center justify-center mb-12">
            <a href="https://wa.me/6285122791793" target="_blank"
                class="inline-flex items-center gap-2 bg-maroon text-white px-8 py-[14px] rounded-full font-bold text-[0.88rem] tracking-wide no-underline transition-all duration-250 hover:-translate-y-0.5 hover:shadow-[0_12px_36px_rgba(139,26,26,0.3)] shadow-[0_4px_18px_rgba(139,26,26,0.22)]">
                <i class="fab fa-whatsapp text-[1rem]"></i> Chat WhatsApp
            </a>
            <a href="#lokasi-kami"
                class="inline-flex items-center gap-2 border border-[#e8e0e0] text-[#666] px-7 py-[13px] rounded-full font-semibold text-[0.88rem] no-underline hover:border-maroon hover:text-maroon transition-all duration-200">
                Lihat Lokasi <i class="fas fa-arrow-down text-[0.72rem]"></i>
            </a>
        </div>

        {{-- Pills --}}
        <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="400"
            class="flex flex-wrap items-center justify-center gap-3">
            @foreach([
                ['icon' => 'fas fa-bolt', 'label' => '< 5 Menit Respons'],
                ['icon' => 'fas fa-star', 'label' => '4.9 / 5 Rating'],
                ['icon' => 'fas fa-users', 'label' => 'Selalu Siap Melayani'],
            ] as $pill)
            <div class="inline-flex items-center gap-2 bg-[#fdf6f3] border border-[#f0e4df] text-[#666] px-5 py-2 rounded-full text-[0.8rem] font-medium">
                <i class="{{ $pill['icon'] }} text-maroon text-[0.75rem]"></i>
                {{ $pill['label'] }}
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ================================================================
     CONTACT INFO CARDS
================================================================ --}}
<section class="bg-white px-5 md:px-12 lg:px-[80px] py-[80px] lg:py-[100px] border-b border-[#f0eeec]">
    <div class="max-w-[1160px] mx-auto">

        <div data-aos="fade-up" data-aos-duration="700" class="text-center mb-14">
            <div class="flex items-center justify-center gap-3 text-[0.7rem] font-bold tracking-[3px] uppercase text-maroon mb-3">
                <span class="w-6 h-px bg-maroon"></span> Informasi Kontak <span class="w-6 h-px bg-maroon"></span>
            </div>
            <h2 class="font-playfair text-[2rem] md:text-[2.4rem] text-[#1a1a1a] tracking-[-0.5px]">
                Berbagai Cara <span class="text-maroon">Menghubungi Kami</span>
            </h2>
            <p class="text-[#aaa] text-[0.92rem] mt-3 max-w-[440px] mx-auto leading-relaxed">
                Pilih cara yang paling mudah dan nyaman untuk Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-[900px] mx-auto">

            {{-- Alamat --}}
            <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="0"
                class="group relative bg-white rounded-[24px] p-8 border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_56px_rgba(139,26,26,0.1)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] scale-x-0 origin-left transition-transform duration-400 group-hover:scale-x-100 rounded-t-[24px]"></div>
                <div class="w-12 h-12 bg-[#fdf6f3] border border-[#f0e4df] rounded-[14px] flex items-center justify-center text-maroon text-[1.1rem] mb-5">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4 class="font-playfair text-[1.1rem] text-[#1a1a1a] mb-3">Alamat</h4>
                <p class="text-[#777] text-[0.85rem] leading-[1.75]">Jl. Kapi Anala I 7 No.15M, Sawojajar A, Sekarpuro, Kec. Pakis, Kab. Malang, Jawa Timur 65154</p>
            </div>

            {{-- WhatsApp --}}
            <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="100"
                class="group relative bg-white rounded-[24px] p-8 border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_56px_rgba(139,26,26,0.1)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] scale-x-0 origin-left transition-transform duration-400 group-hover:scale-x-100 rounded-t-[24px]"></div>
                <div class="w-12 h-12 bg-[#fdf6f3] border border-[#f0e4df] rounded-[14px] flex items-center justify-center text-maroon text-[1.1rem] mb-5">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h4 class="font-playfair text-[1.1rem] text-[#1a1a1a] mb-3">WhatsApp</h4>
                <a href="https://wa.me/6285122791793" target="_blank"
                    class="text-[#777] text-[0.85rem] no-underline hover:text-maroon transition-colors leading-[1.75] block mb-3">+62 851-2279-1793</a>
                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-[0.72rem] font-bold px-3 py-1 rounded-full border border-green-100">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Online
                </span>
            </div>

            {{-- Jam Buka --}}
            <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="300"
                class="group relative bg-white rounded-[24px] p-8 border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_56px_rgba(139,26,26,0.1)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] scale-x-0 origin-left transition-transform duration-400 group-hover:scale-x-100 rounded-t-[24px]"></div>
                <div class="w-12 h-12 bg-[#fdf6f3] border border-[#f0e4df] rounded-[14px] flex items-center justify-center text-maroon text-[1.1rem] mb-5">
                    <i class="fas fa-clock"></i>
                </div>
                <h4 class="font-playfair text-[1.1rem] text-[#1a1a1a] mb-3">Jam Buka</h4>
                <p class="text-[#777] text-[0.85rem] leading-[1.75]">Selasa: 07.00 – 19.00<br>Rabu–Minggu: 12.00 – 19.00<br>Senin: Tutup</p>
            </div>

        </div>
    </div>
</section>


{{-- ================================================================
     MAP + SIDEBAR
================================================================ --}}
<section id="lokasi-kami" class="bg-white px-5 md:px-12 lg:px-[80px] py-[80px] lg:py-[100px]">
    <div class="max-w-[1160px] mx-auto">

        <div data-aos="fade-up" data-aos-duration="700" class="text-center mb-14">
            <div class="flex items-center justify-center gap-3 text-[0.7rem] font-bold tracking-[3px] uppercase text-maroon mb-3">
                <span class="w-6 h-px bg-maroon"></span> Lokasi & Sosial Media <span class="w-6 h-px bg-maroon"></span>
            </div>
            <h2 class="font-playfair text-[2rem] md:text-[2.4rem] text-[#1a1a1a] tracking-[-0.5px]">
                Temukan & <span class="text-maroon">Ikuti Kami</span>
            </h2>
            <p class="text-[#aaa] text-[0.92rem] mt-3 max-w-[440px] mx-auto leading-relaxed">
                Kunjungi dapur kami langsung atau tetap terhubung lewat media sosial.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-7 items-start">

            {{-- MAP --}}
            <div data-aos="fade-right" data-aos-duration="800"
                class="group relative bg-white rounded-[24px] border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] overflow-hidden hover:shadow-[0_20px_56px_rgba(139,26,26,0.08)] transition-all duration-300">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] rounded-t-[24px]"></div>
                <div class="flex justify-between items-center px-7 py-5 border-b border-[#f0eeec]">
                    <div>
                        <div class="text-[0.7rem] font-bold tracking-[2.5px] uppercase text-maroon mb-1">Peta Lokasi</div>
                        <h3 class="font-playfair text-[1.4rem] text-[#1a1a1a]">Temukan Kami di Sini</h3>
                    </div>
                    <a href="https://maps.app.goo.gl/mtTjXXxSi1AiyF5t7" target="_blank"
                        class="inline-flex items-center gap-2 bg-maroon text-white px-5 py-[10px] rounded-full font-bold text-[0.82rem] no-underline hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(139,26,26,0.3)] transition-all duration-200">
                        <i class="fas fa-directions text-[0.8rem]"></i> Petunjuk Arah
                    </a>
                </div>
                <div class="h-[380px]">
                    <div id="map" class="w-full h-full"></div>
                </div>
                <div class="flex items-start gap-3 px-7 py-5 bg-[#fdf6f3] border-t border-[#f0eeec]">
                    <div class="w-9 h-9 bg-maroon rounded-[10px] flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                        <i class="fas fa-map-marker-alt text-[0.82rem]"></i>
                    </div>
                    <div>
                        <p class="text-[0.88rem] text-[#444] leading-[1.6] font-semibold">Jl. Kapi Anala I 7 No.15M, Sawojajar A, Sekarpuro, Kec. Pakis, Kab. Malang</p>
                        <p class="text-[0.78rem] text-[#999] mt-1">Klik marker pada peta untuk informasi lengkap</p>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div data-aos="fade-left" data-aos-duration="800" data-aos-delay="100" class="flex flex-col gap-5">

                {{-- Quick Contact CTA --}}
                <div class="group relative bg-white rounded-[24px] border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] p-7 overflow-hidden hover:shadow-[0_20px_56px_rgba(139,26,26,0.1)] transition-all duration-300">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] rounded-t-[24px]"></div>
                    <div class="w-12 h-12 bg-[#fdf6f3] border border-[#f0e4df] rounded-[14px] flex items-center justify-center text-maroon text-[1.1rem] mb-5">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <h3 class="font-playfair text-[1.3rem] text-[#1a1a1a] mb-2">Butuh Bantuan Cepat?</h3>
                    <p class="text-[#777] text-[0.85rem] leading-[1.75] mb-5">Tim kami siap menjawab pertanyaan seputar menu, pemesanan, dan pengiriman.</p>
                    <a href="https://wa.me/6285122791793" target="_blank"
                        class="flex items-center justify-center gap-2 w-full py-3.5 bg-[#25D366] text-white rounded-full font-bold text-[0.88rem] no-underline hover:bg-[#1da851] hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(37,211,102,0.3)] transition-all duration-200">
                        <i class="fab fa-whatsapp text-[1.1rem]"></i> Hubungi via WhatsApp
                    </a>
                </div>

                {{-- Sosmed --}}
                <div class="group relative bg-white rounded-[24px] border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] p-7 overflow-hidden hover:shadow-[0_20px_56px_rgba(139,26,26,0.08)] transition-all duration-300">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] rounded-t-[24px]"></div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-[#fdf6f3] border border-[#f0e4df] rounded-[12px] flex items-center justify-center text-maroon">
                            <i class="fas fa-share-alt text-[0.9rem]"></i>
                        </div>
                        <h4 class="font-playfair text-[1.1rem] text-[#1a1a1a]">Ikuti Kami</h4>
                    </div>
                    <div class="flex flex-col gap-3">
                        <a href="https://www.instagram.com/ummilaa_kitchen?igsh=MWw3dWNqanNuc2w4OA==" target="_blank"
                            class="flex items-center gap-4 p-4 rounded-[16px] border border-[#f0eeec] bg-[#fdf6f3] no-underline transition-all duration-200 hover:border-maroon hover:shadow-[0_4px_16px_rgba(139,26,26,0.08)] hover:-translate-y-0.5 group/item">
                            <div class="w-10 h-10 rounded-[12px] flex items-center justify-center text-[1rem] text-white flex-shrink-0 bg-[linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888)]">
                                <i class="fab fa-instagram"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <strong class="block text-[0.88rem] text-[#1a1a1a] font-extrabold">@ummilaa_kitchen</strong>
                                <span class="text-[0.76rem] text-[#999]">Foto & Update Terbaru</span>
                            </div>
                            <i class="fas fa-arrow-right text-[#ccc] text-[0.78rem] transition-all group-hover/item:text-maroon group-hover/item:translate-x-0.5"></i>
                        </a>
                        <a href="https://wa.me/6285122791793" target="_blank"
                            class="flex items-center gap-4 p-4 rounded-[16px] border border-[#f0eeec] bg-[#f0fdf4] no-underline transition-all duration-200 hover:border-green-400 hover:shadow-[0_4px_16px_rgba(37,211,102,0.1)] hover:-translate-y-0.5 group/item">
                            <div class="w-10 h-10 rounded-[12px] flex items-center justify-center text-[1rem] text-white flex-shrink-0 bg-[#25D366]">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <strong class="block text-[0.88rem] text-[#1a1a1a] font-extrabold">+62 851-2279-1793</strong>
                                <span class="text-[0.76rem] text-[#999]">Pesan Langsung</span>
                            </div>
                            <i class="fas fa-arrow-right text-[#ccc] text-[0.78rem] transition-all group-hover/item:text-green-600 group-hover/item:translate-x-0.5"></i>
                        </a>
                    </div>
                </div>

                {{-- Status Toko --}}
                <div class="group relative bg-white rounded-[24px] border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] p-7 overflow-hidden hover:shadow-[0_20px_56px_rgba(139,26,26,0.08)] transition-all duration-300">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] rounded-t-[24px]"></div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-[#fdf6f3] border border-[#f0e4df] rounded-[12px] flex items-center justify-center text-maroon">
                            <i class="fas fa-store text-[0.9rem]"></i>
                        </div>
                        <h4 class="font-playfair text-[1.1rem] text-[#1a1a1a]">Status Toko</h4>
                    </div>
                    <div class="space-y-0">
                        <div class="flex justify-between items-center py-3 border-b border-[#f0eeec]">
                            <span class="text-[0.84rem] text-[#777]">Selasa</span>
                            <span class="text-[0.84rem] font-bold text-[#1a1a1a]">07.00 – 19.00</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-[#f0eeec]">
                            <span class="text-[0.84rem] text-[#777]">Rabu – Minggu</span>
                            <span class="text-[0.84rem] font-bold text-[#1a1a1a]">12.00 – 19.00</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-[#f0eeec]">
                            <span class="text-[0.84rem] text-[#777]">Senin</span>
                            <span class="text-[0.84rem] font-bold text-red-500">Tutup</span>
                        </div>
                        <div class="flex justify-between items-center pt-3">
                            <span class="text-[0.84rem] text-[#777]">Status</span>
                            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-[0.76rem] font-bold px-3 py-1.5 rounded-full border border-green-100">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Buka Hari Ini
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 60 });
</script>
<script>
    const lat = -7.963536;
    const lng = 112.669380;
    const namaLokasi = "Ummilaa Kitchen";
    const alamat = "Jl. Kapi Anala I 7 No.15M, Sawojajar A, Sekarpuro, Kec. Pakis, Kab. Malang";

    const map = L.map('map').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const icon = L.divIcon({
        html: `<div style="background:#8B1A1A;width:36px;height:36px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,0.3);"></div>`,
        iconSize: [36, 36], iconAnchor: [18, 36], popupAnchor: [0, -36],
        className: ''
    });

    L.marker([lat, lng], { icon })
        .addTo(map)
        .bindPopup(`<strong>${namaLokasi}</strong><br>${alamat}`)
        .openPopup();
</script>
@endpush
