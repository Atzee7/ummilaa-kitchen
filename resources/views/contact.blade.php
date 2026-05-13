@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
@endpush

@section('content')

{{-- ========== HERO ========== --}}
<section class="relative overflow-hidden bg-[linear-gradient(135deg,#8B1A1A_0%,#5a0e0e_55%,#3a0808_100%)] px-5 md:px-10 lg:px-[80px] py-10 lg:py-[80px] text-white">
    {{-- Decorative circles --}}
    <div class="absolute -right-[100px] -top-[100px] w-[500px] h-[500px] rounded-full bg-white/[0.04] pointer-events-none"></div>
    <div class="absolute -left-[60px] -bottom-[100px] w-[350px] h-[350px] rounded-full bg-white/[0.03] pointer-events-none"></div>
    <div class="absolute right-[200px] bottom-[30px] w-[180px] h-[180px] rounded-full bg-white/[0.02] pointer-events-none"></div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-8 lg:gap-[80px] items-center relative z-[1] max-w-[1200px] mx-auto">
        {{-- LEFT: Text --}}
        <div>
            <div data-aos="fade-down" data-aos-duration="600"
                class="inline-flex items-center gap-2 bg-white/[0.15] backdrop-blur-[6px] text-[#ffd9d9] px-5 py-[9px] rounded-[30px] text-[0.78rem] font-bold tracking-[1.8px] uppercase mb-6 border border-white/[0.15]">
                <i class="fas fa-headset"></i> Hubungi Kami
            </div>
            <h1 data-aos="fade-up" data-aos-duration="700" data-aos-delay="100"
                class="font-playfair text-[2rem] md:text-[2.5rem] lg:text-[3rem] leading-[1.2] mb-5 tracking-[-0.5px]">Kami Siap <em class="italic text-[#ffcccc]">Membantu</em> Anda</h1>
            <p data-aos="fade-up" data-aos-duration="700" data-aos-delay="200"
                class="opacity-[0.82] text-[1rem] leading-[1.9] max-w-[520px] mb-8">Ada pertanyaan, pemesanan khusus, atau ingin tahu lebih lanjut tentang produk kami? Jangan ragu menghubungi kami — tim kami siap merespons dengan cepat dan ramah.</p>
        </div>

        {{-- RIGHT: Floating stat cards --}}
        <div data-aos="fade-left" data-aos-duration="800" data-aos-delay="200" class="hidden lg:flex flex-col gap-4 min-w-[220px]">
            <div class="bg-white/[0.1] backdrop-blur-[8px] border border-white/[0.15] rounded-[20px] px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-white/[0.15] rounded-[14px] flex items-center justify-center text-[1.3rem] flex-shrink-0">
                    <i class="fas fa-bolt text-yellow-300"></i>
                </div>
                <div>
                    <strong class="block text-[1.4rem] font-extrabold leading-none">< 5 Menit</strong>
                    <span class="text-white/70 text-[0.8rem]">Waktu Respons</span>
                </div>
            </div>
            <div class="bg-white/[0.1] backdrop-blur-[8px] border border-white/[0.15] rounded-[20px] px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-white/[0.15] rounded-[14px] flex items-center justify-center text-[1.3rem] flex-shrink-0">
                    <i class="fas fa-star text-yellow-300"></i>
                </div>
                <div>
                    <strong class="block text-[1.4rem] font-extrabold leading-none">4.9 / 5</strong>
                    <span class="text-white/70 text-[0.8rem]">Rating Pelayanan</span>
                </div>
            </div>
            <div class="bg-white/[0.1] backdrop-blur-[8px] border border-white/[0.15] rounded-[20px] px-6 py-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-white/[0.15] rounded-[14px] flex items-center justify-center text-[1.3rem] flex-shrink-0">
                    <i class="fas fa-users text-[#ffcccc]"></i>
                </div>
                <div>
                    <strong class="block text-[1.4rem] font-extrabold leading-none">500+</strong>
                    <span class="text-white/70 text-[0.8rem]">Pelanggan Setia</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== CONTACT INFO GRID ========== --}}
<div class="px-5 md:px-10 lg:px-[80px] py-10 lg:py-[70px] bg-[linear-gradient(180deg,#fdf5f5_0%,#fff_50%)]">
    <div data-aos="fade-up" data-aos-duration="700" class="text-center mb-12">
        <div class="inline-flex items-center gap-2 text-[0.78rem] font-bold tracking-[2.5px] uppercase text-maroon mb-3">
            <span class="inline-block w-6 h-0.5 bg-maroon rounded-sm"></span> Informasi Kontak <span class="inline-block w-6 h-0.5 bg-maroon rounded-sm"></span>
        </div>
        <h2 class="font-playfair text-[2rem] text-[#1a1a1a]">Berbagai Cara Menghubungi Kami</h2>
        <p class="text-[#999] text-[0.92rem] mt-2">Pilih cara yang paling mudah untuk Anda</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 max-w-[1100px] mx-auto">
        {{-- Alamat --}}
        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="0"
            class="contact-card relative bg-white border-[1.5px] border-maroon-200 rounded-[22px] p-7 overflow-hidden transition-all duration-200 hover:shadow-[0_12px_40px_rgba(139,26,26,0.1)] hover:-translate-y-1 group">
            <div class="absolute left-0 top-4 bottom-4 w-[3px] bg-gradient-to-b from-[#8B1A1A] to-[#c0392b] rounded-r scale-y-0 origin-center transition-transform duration-[250ms] group-hover:scale-y-100 pointer-events-none"></div>
            <div class="w-14 h-14 bg-[linear-gradient(135deg,#fdf0f0,#ffe8e8)] rounded-[18px] flex items-center justify-center text-maroon text-[1.4rem] mb-5 shadow-[0_4px_12px_rgba(139,26,26,0.1)] transition-all duration-200 group-hover:bg-maroon group-hover:text-white group-hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)]">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h4 class="font-extrabold text-[#1a1a1a] text-[0.95rem] mb-2">Alamat</h4>
            <p class="text-[#777] text-[0.85rem] leading-[1.7]">Jl. Contoh No. 123, Kelurahan Sukun, Kota Malang, Jawa Timur 65148</p>
        </div>

        {{-- Telepon --}}
        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="100"
            class="contact-card relative bg-white border-[1.5px] border-maroon-200 rounded-[22px] p-7 overflow-hidden transition-all duration-200 hover:shadow-[0_12px_40px_rgba(139,26,26,0.1)] hover:-translate-y-1 group">
            <div class="absolute left-0 top-4 bottom-4 w-[3px] bg-gradient-to-b from-[#8B1A1A] to-[#c0392b] rounded-r scale-y-0 origin-center transition-transform duration-[250ms] group-hover:scale-y-100 pointer-events-none"></div>
            <div class="w-14 h-14 bg-[linear-gradient(135deg,#fdf0f0,#ffe8e8)] rounded-[18px] flex items-center justify-center text-maroon text-[1.4rem] mb-5 shadow-[0_4px_12px_rgba(139,26,26,0.1)] transition-all duration-200 group-hover:bg-maroon group-hover:text-white group-hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)]">
                <i class="fas fa-phone-alt"></i>
            </div>
            <h4 class="font-extrabold text-[#1a1a1a] text-[0.95rem] mb-2">WhatsApp</h4>
            <a href="https://wa.me/6281234567890" target="_blank" class="text-[#777] text-[0.85rem] no-underline hover:text-maroon transition-colors leading-[1.7] block">+62 812-3456-7890</a>
            <span class="inline-flex items-center gap-1 mt-2 bg-green-50 text-green-700 text-[0.72rem] font-bold px-3 py-1 rounded-full">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Online
            </span>
        </div>

        {{-- Email --}}
        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="200"
            class="contact-card relative bg-white border-[1.5px] border-maroon-200 rounded-[22px] p-7 overflow-hidden transition-all duration-200 hover:shadow-[0_12px_40px_rgba(139,26,26,0.1)] hover:-translate-y-1 group">
            <div class="absolute left-0 top-4 bottom-4 w-[3px] bg-gradient-to-b from-[#8B1A1A] to-[#c0392b] rounded-r scale-y-0 origin-center transition-transform duration-[250ms] group-hover:scale-y-100 pointer-events-none"></div>
            <div class="w-14 h-14 bg-[linear-gradient(135deg,#fdf0f0,#ffe8e8)] rounded-[18px] flex items-center justify-center text-maroon text-[1.4rem] mb-5 shadow-[0_4px_12px_rgba(139,26,26,0.1)] transition-all duration-200 group-hover:bg-maroon group-hover:text-white group-hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)]">
                <i class="fas fa-envelope"></i>
            </div>
            <h4 class="font-extrabold text-[#1a1a1a] text-[0.95rem] mb-2">Email</h4>
            <a href="mailto:ummilaakitchen@gmail.com" class="text-[#777] text-[0.85rem] no-underline hover:text-maroon transition-colors leading-[1.7] block break-all">ummilaakitchen@gmail.com</a>
        </div>

        {{-- Jam Operasional --}}
        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="300"
            class="contact-card relative bg-white border-[1.5px] border-maroon-200 rounded-[22px] p-7 overflow-hidden transition-all duration-200 hover:shadow-[0_12px_40px_rgba(139,26,26,0.1)] hover:-translate-y-1 group">
            <div class="absolute left-0 top-4 bottom-4 w-[3px] bg-gradient-to-b from-[#8B1A1A] to-[#c0392b] rounded-r scale-y-0 origin-center transition-transform duration-[250ms] group-hover:scale-y-100 pointer-events-none"></div>
            <div class="w-14 h-14 bg-[linear-gradient(135deg,#fdf0f0,#ffe8e8)] rounded-[18px] flex items-center justify-center text-maroon text-[1.4rem] mb-5 shadow-[0_4px_12px_rgba(139,26,26,0.1)] transition-all duration-200 group-hover:bg-maroon group-hover:text-white group-hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)]">
                <i class="fas fa-clock"></i>
            </div>
            <h4 class="font-extrabold text-[#1a1a1a] text-[0.95rem] mb-2">Jam Buka</h4>
            <p class="text-[#777] text-[0.85rem] leading-[1.7]">Sen–Sab: 08.00–20.00<br>Minggu: 09.00–17.00 WIB</p>
        </div>
    </div>
</div>

{{-- ========== MAP + SOSMED ========== --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-8 px-5 md:px-10 lg:px-[80px] pb-10 lg:pb-[80px] items-start">

    {{-- MAP --}}
    <div data-aos="fade-right" data-aos-duration="800" class="bg-white border-[1.5px] border-maroon-200 rounded-[24px] overflow-hidden shadow-[0_8px_40px_rgba(139,26,26,0.07)]">
        <div class="flex justify-between items-center px-7 py-5 border-b border-maroon-200">
            <div>
                <div class="text-[0.72rem] font-extrabold tracking-[2px] uppercase text-maroon mb-1">Lokasi Kami</div>
                <h2 class="font-playfair text-[1.5rem] text-[#1a1a1a]">Temukan Kami di Sini</h2>
            </div>
            <a href="https://maps.app.goo.gl/mtTjXXxSi1AiyF5t7" target="_blank"
                class="inline-flex items-center gap-2 bg-maroon text-white px-5 py-[10px] rounded-xl font-bold text-[0.85rem] no-underline hover:bg-maroon-dark transition-colors">
                <i class="fas fa-directions"></i> Petunjuk Arah
            </a>
        </div>
        <div class="h-[400px]">
            <div id="map" class="w-full h-full"></div>
        </div>
        <div class="flex items-start gap-3 px-7 py-5 bg-maroon-50 border-t border-maroon-200">
            <div class="w-9 h-9 bg-maroon rounded-[10px] flex items-center justify-center text-white flex-shrink-0 mt-0.5">
                <i class="fas fa-map-marker-alt text-[0.85rem]"></i>
            </div>
            <div>
                <p class="text-[0.88rem] text-[#444] leading-[1.6] font-semibold">Jalan kapi anala 1 blok 15n no 18 Sawojajar 2 Malang</p>
                <p class="text-[0.8rem] text-[#999] mt-1">Klik marker pada peta untuk informasi lengkap</p>
            </div>
        </div>
    </div>

    {{-- KANAN: Sosmed + Quick Contact --}}
    <div data-aos="fade-left" data-aos-duration="800" data-aos-delay="100" class="flex flex-col gap-5">

        {{-- Quick Contact CTA --}}
        <div class="bg-[linear-gradient(135deg,#8B1A1A,#5a0e0e)] rounded-[22px] p-7 text-white">
            <div class="w-12 h-12 bg-white/[0.15] rounded-[14px] flex items-center justify-center text-[1.3rem] mb-4">
                <i class="fas fa-comment-dots"></i>
            </div>
            <h3 class="font-playfair text-[1.3rem] mb-2">Butuh Bantuan Cepat?</h3>
            <p class="text-white/75 text-[0.85rem] leading-[1.7] mb-5">Tim kami siap menjawab pertanyaan Anda seputar menu, pemesanan, dan pengiriman.</p>
            <a href="https://wa.me/6281234567890" target="_blank"
                class="flex items-center justify-center gap-2 w-full py-3 bg-[#25D366] text-white rounded-xl font-bold text-[0.9rem] no-underline hover:bg-[#1da851] transition-colors">
                <i class="fab fa-whatsapp text-[1.1rem]"></i> Hubungi via WhatsApp
            </a>
        </div>

        {{-- Sosmed --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[22px] p-7">
            <div class="text-[0.72rem] font-extrabold tracking-[2px] uppercase text-[#bbb] mb-5">Ikuti Kami</div>
            <div class="flex flex-col gap-3">
                <a href="https://instagram.com/ummilaakitchen" target="_blank"
                    class="flex items-center gap-4 p-4 rounded-[16px] border-[1.5px] border-transparent bg-[#fdf5f5] no-underline transition-all duration-200 hover:border-maroon hover:shadow-[0_4px_16px_rgba(139,26,26,0.08)] hover:-translate-y-0.5 group">
                    <div class="w-11 h-11 rounded-[14px] flex items-center justify-center text-[1.1rem] text-white flex-shrink-0 bg-[linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888)]">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <div class="flex-1">
                        <strong class="block text-[0.9rem] text-[#1a1a1a] font-extrabold">@ummilaakitchen</strong>
                        <span class="text-[0.78rem] text-[#999]">Instagram · Foto & Update</span>
                    </div>
                    <i class="fas fa-arrow-right text-[#ccc] text-[0.8rem] transition-all group-hover:text-maroon group-hover:translate-x-0.5"></i>
                </a>
                <a href="https://wa.me/6281234567890" target="_blank"
                    class="flex items-center gap-4 p-4 rounded-[16px] border-[1.5px] border-transparent bg-[#f0fdf4] no-underline transition-all duration-200 hover:border-green-400 hover:shadow-[0_4px_16px_rgba(37,211,102,0.1)] hover:-translate-y-0.5 group">
                    <div class="w-11 h-11 rounded-[14px] flex items-center justify-center text-[1.1rem] text-white flex-shrink-0 bg-[#25D366]">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="flex-1">
                        <strong class="block text-[0.9rem] text-[#1a1a1a] font-extrabold">+62 812-3456-7890</strong>
                        <span class="text-[0.78rem] text-[#999]">WhatsApp · Pesan Langsung</span>
                    </div>
                    <i class="fas fa-arrow-right text-[#ccc] text-[0.8rem] transition-all group-hover:text-green-600 group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </div>

        {{-- Jam Buka Card --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[22px] p-7">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-maroon-100 rounded-[12px] flex items-center justify-center text-maroon">
                    <i class="fas fa-store"></i>
                </div>
                <h4 class="font-extrabold text-[#1a1a1a] text-[0.95rem]">Status Toko</h4>
            </div>
            <div class="flex flex-col gap-3">
                <div class="flex justify-between items-center py-3 border-b border-[#f8f0f0]">
                    <span class="text-[0.85rem] text-[#777]">Senin – Sabtu</span>
                    <span class="text-[0.85rem] font-bold text-[#1a1a1a]">08.00 – 20.00</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-[#f8f0f0]">
                    <span class="text-[0.85rem] text-[#777]">Minggu</span>
                    <span class="text-[0.85rem] font-bold text-[#1a1a1a]">09.00 – 17.00</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-[0.85rem] text-[#777]">Status</span>
                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-[0.78rem] font-bold px-3 py-1.5 rounded-full">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Buka Hari Ini
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 700,
        easing: 'ease-out-cubic',
        once: true,
        offset: 60,
    });
</script>
<script>
    const lat = -7.963536;
    const lng = 112.669380;
    const namaLokasi = "Ummilaa Kitchen";
    const alamat = "Jalan kapi anala 1 blok 15n no 18 Sawojajar 2 Malang";

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
