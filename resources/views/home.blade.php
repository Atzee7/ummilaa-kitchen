@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
@endpush

@section('content')

{{-- HERO --}}
<section class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-[60px] px-5 md:px-10 lg:px-[80px] py-10 lg:py-[80px] min-h-0 lg:min-h-[90vh] bg-white relative overflow-hidden
    before:content-[''] before:absolute before:top-[-100px] before:right-[-100px] before:w-[600px] before:h-[600px] before:rounded-full before:bg-[radial-gradient(circle,#fdf0f0_0%,transparent_70%)] before:z-0">
    <div class="relative z-[1]">
        <div data-aos="fade-down" data-aos-duration="600"
            class="inline-flex items-center gap-2 bg-maroon-100 text-maroon px-[18px] py-2 rounded-[30px] text-[0.82rem] font-bold tracking-[1px] uppercase mb-6">
            <i class="fas fa-fire text-[0.75rem]"></i> Kuliner Favorit Malang
        </div>
        <h1 data-aos="fade-up" data-aos-duration="700" data-aos-delay="100"
            class="font-playfair text-[2rem] md:text-[2.6rem] lg:text-[3.2rem] leading-[1.2] text-[#1a1a1a] mb-5">
            Pesan <span class="text-maroon">Makanan Lezat</span> & Fresh Langsung ke Pintu Anda
        </h1>
        <p data-aos="fade-up" data-aos-duration="700" data-aos-delay="200"
            class="text-base text-[#777] leading-[1.8] mb-9 max-w-[400px]">Aneka dimsum, risol, frozen food, dan catering berkualitas dari dapur Ummilaa Kitchen. Mudah dipesan, cepat dikirim.</p>
        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="300"
            class="flex items-center gap-5">
            <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-[10px] bg-maroon text-white px-8 py-[15px] rounded-xl font-bold text-[0.95rem] transition-all duration-200 shadow-[0_8px_24px_rgba(139,26,26,0.25)] hover:bg-maroon-dark hover:-translate-y-0.5 hover:shadow-[0_12px_32px_rgba(139,26,26,0.35)] no-underline">
                <i class="fas fa-utensils"></i> Lihat Produk
            </a>
            <a href="{{ route('about') }}" class="inline-flex items-center gap-2 text-maroon font-bold text-[0.95rem] transition-all duration-200 hover:gap-3 no-underline">
                Tentang Kami <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div data-aos="fade-left" data-aos-duration="800" data-aos-delay="200" class="relative z-[1]">
        <img class="w-full h-[280px] md:h-[400px] lg:h-[520px] object-cover rounded-[24px] shadow-[0_30px_70px_rgba(139,26,26,0.2)]"
             src="https://images.unsplash.com/photo-1563245372-f21724e3856d?w=800" alt="Dimsum Ummilaa Kitchen">
        <div class="absolute top-[20px] right-[10px] lg:top-[30px] lg:right-[-20px] bg-maroon rounded-[16px] px-4 py-3 lg:px-5 lg:py-4 shadow-[0_10px_40px_rgba(139,26,26,0.3)] text-white text-center">
            <strong class="block text-[1.4rem] font-playfair">10+</strong>
            <span class="text-[0.78rem] opacity-85">Varian Menu</span>
        </div>
    </div>
</section>

<div class="h-px bg-gradient-to-r from-transparent via-maroon-200 to-transparent mx-5 md:mx-10 lg:mx-[80px]"></div>

{{-- CATALOGUE --}}
<section class="px-5 md:px-10 lg:px-[80px] py-10 lg:py-[80px]" id="catalogue">
    <div data-aos="fade-up" data-aos-duration="700" class="mb-[50px]">
        <div class="flex flex-col sm:flex-row justify-between sm:items-end gap-3">
            <div>
                <div class="text-[0.78rem] font-bold tracking-[2px] uppercase text-maroon mb-2.5">Menu Pilihan</div>
                <h2 class="font-playfair text-[1.6rem] md:text-[2rem] lg:text-[2.2rem] text-[#1a1a1a]">Our Special <span class="text-maroon">Catalogue</span></h2>
                <p class="text-[#999] mt-2 text-[0.95rem]">Produk segar dan lezat siap dipesan hari ini</p>
            </div>
            <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 text-maroon font-bold text-[0.9rem] transition-all duration-200 hover:gap-3 no-underline">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    @if($featuredProducts->isEmpty())
    <div class="text-center py-[60px] text-[#bbb]">
        <i class="fas fa-box-open text-[3rem] mb-4 block"></i>
        <p>Belum ada produk unggulan. Tambahkan badge produk di panel admin.</p>
    </div>
    @else
    <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="150" class="overflow-hidden">
        <div class="flex gap-7 will-change-transform" id="productCarousel">
            @foreach($featuredProducts as $product)
            <a href="{{ $product->status !== 'habis' ? route('product.show', $product->id) : '#' }}"
               class="product-card flex-[0_0_calc(50%-14px)] md:flex-[0_0_calc(33.333%-19px)] lg:flex-[0_0_calc(25%-21px)] rounded-[20px] overflow-hidden bg-white border border-maroon-200 transition-all duration-300 no-underline block {{ $product->status === 'habis' ? 'pointer-events-none' : '' }} hover:shadow-[0_16px_48px_rgba(139,26,26,0.12)] hover:-translate-y-1.5">
                <div class="relative overflow-hidden aspect-[4/3] sm:aspect-auto sm:h-[160px] md:h-[180px] lg:h-[200px]">
                    <img class="absolute inset-0 w-full h-full object-cover bg-maroon-50 transition-transform duration-[400ms] {{ $product->status === 'habis' ? 'grayscale brightness-50' : 'group-hover:scale-105' }}"
                         src="{{ $product->image && Str::startsWith($product->image, 'products/') ? asset('storage/' . $product->image) : $product->image }}"
                         alt="{{ $product->name }}">

                    @if($product->badge)
                    <span class="absolute top-2 sm:top-[14px] left-2 sm:left-[14px] px-2 sm:px-[14px] py-0.5 sm:py-[5px] rounded-[30px] text-[0.6rem] sm:text-[0.72rem] font-extrabold tracking-[1px] uppercase text-white backdrop-blur-sm
                        {{ $product->badge == 'new' ? 'bg-blue-700/90' : ($product->badge == 'terlaris' ? 'bg-orange-700/90' : 'bg-maroon/90') }}">
                        {{ $product->badge == 'new' ? 'New' : ($product->badge == 'terlaris' ? 'Terlaris' : 'Unggulan') }}
                    </span>
                    @endif

                    <span class="absolute bottom-2 sm:bottom-3 left-2 sm:left-3 px-2 sm:px-3 py-0.5 sm:py-1 rounded-[20px] text-[0.6rem] sm:text-[0.72rem] font-bold {{ $product->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-pink-100 text-red-800' }}">
                        @if($product->status === 'ready') ● Ready Stock @else ● Habis @endif
                    </span>
                </div>
                <div class="p-3 sm:p-[18px_20px_20px]">
                    <h3 class="font-bold text-[#1a1a1a] text-[0.82rem] sm:text-base mb-1 sm:mb-1.5 line-clamp-2">{{ $product->name }}</h3>
                    <p class="hidden sm:block text-[0.82rem] text-[#aaa] leading-[1.5] mb-4 line-clamp-2">{{ Str::limit($product->description, 65) }}</p>
                    <div class="flex justify-between items-center mt-2 sm:mt-0">
                        <span class="font-extrabold text-maroon text-[0.88rem] sm:text-base">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="bg-maroon text-white rounded-[10px] w-8 h-8 sm:w-[38px] sm:h-[38px] flex items-center justify-center text-[0.8rem] sm:text-[0.85rem] transition-all duration-200 {{ $product->status === 'habis' ? 'bg-[#ccc]' : 'hover:bg-maroon-dark hover:scale-110' }}">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</section>

{{-- CATERING CTA --}}
<section class="px-5 md:px-10 lg:px-[80px] py-[50px] lg:py-[60px] border-t border-[#f0eeec]">
    <div class="max-w-[1160px] mx-auto">
        <div data-aos="fade-up" data-aos-duration="700"
             class="relative rounded-[24px] overflow-hidden border border-[#eddede] bg-white shadow-[0_4px_24px_rgba(139,26,26,0.06)]">

            {{-- Left accent bar --}}
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-maroon to-[#c94040]"></div>

            <div class="pl-10 pr-8 md:pr-12 py-10 md:py-12 flex flex-col md:flex-row items-center justify-between gap-8">

                {{-- Text side --}}
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center gap-2 bg-[#fdf0f0] px-3 py-1.5 rounded-full mb-4">
                        <i class="fas fa-utensils text-maroon text-[0.72rem]"></i>
                        <span class="text-maroon text-[0.72rem] font-bold tracking-[1.5px] uppercase">Layanan Catering</span>
                    </div>
                    <h2 class="font-playfair text-[1.6rem] md:text-[2rem] text-[#1a1a1a] mb-2">
                        Punya Acara <span class="text-maroon">Spesial?</span>
                    </h2>
                    <p class="text-[#888] text-[0.92rem] max-w-[460px] leading-relaxed">
                        Dari syukuran, arisan, hingga pesta — kami siapkan hidangan lezat langsung ke lokasi Anda.
                    </p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-5">
                        <span class="inline-flex items-center gap-1.5 text-[0.78rem] text-[#666] bg-[#f7f7f7] px-3 py-1.5 rounded-full border border-[#eee]">
                            <i class="fas fa-bowl-food text-maroon/70 text-[0.68rem]"></i> Berbagai Paket
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[0.78rem] text-[#666] bg-[#f7f7f7] px-3 py-1.5 rounded-full border border-[#eee]">
                            <i class="fas fa-location-dot text-maroon/70 text-[0.68rem]"></i> Radius 5 km
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[0.78rem] text-[#666] bg-[#f7f7f7] px-3 py-1.5 rounded-full border border-[#eee]">
                            <i class="fab fa-whatsapp text-maroon/70 text-[0.68rem]"></i> Diskusi via WhatsApp
                        </span>
                    </div>
                </div>

                {{-- CTA side --}}
                <div class="flex-shrink-0 flex flex-col items-center gap-2.5">
                    <a href="{{ route('catering.index') }}"
                       class="inline-flex items-center gap-2.5 bg-maroon text-white px-8 py-[14px] rounded-xl font-bold text-[0.92rem] no-underline shadow-[0_8px_24px_rgba(139,26,26,0.25)] hover:-translate-y-0.5 hover:shadow-[0_12px_32px_rgba(139,26,26,0.35)] transition-all duration-200 whitespace-nowrap">
                        <i class="fas fa-utensils text-sm"></i>
                        Lihat Paket Catering
                    </a>
                    <p class="text-[0.75rem] text-[#bbb]">Konsultasi gratis via WhatsApp</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="h-px bg-gradient-to-r from-transparent via-maroon-200 to-transparent mx-5 md:mx-10 lg:mx-[80px]"></div>

{{-- AREA PENGIRIMAN --}}
<section class="bg-white px-5 md:px-10 lg:px-[80px] py-[80px] lg:py-[100px] border-t border-[#f0eeec]">
    <div class="max-w-[1160px] mx-auto">

        <div data-aos="fade-up" data-aos-duration="700" class="text-center mb-14">
            <div class="flex items-center justify-center gap-3 text-[0.7rem] font-bold tracking-[3px] uppercase text-maroon mb-3">
                <span class="w-6 h-px bg-maroon"></span> Jangkauan Layanan <span class="w-6 h-px bg-maroon"></span>
            </div>
            <h2 class="font-playfair text-[2rem] md:text-[2.4rem] text-[#1a1a1a] tracking-[-0.5px]">
                Area <span class="text-maroon">Pengiriman</span>
            </h2>
            <div class="flex items-center justify-center gap-3 my-4">
                <div class="h-px w-10 bg-gradient-to-r from-transparent to-maroon-200"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-maroon opacity-40"></div>
                <div class="w-2 h-2 rounded-full bg-maroon opacity-70"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-maroon opacity-40"></div>
                <div class="h-px w-10 bg-gradient-to-l from-transparent to-maroon-200"></div>
            </div>
            <p class="text-[#aaa] text-[0.92rem] max-w-[460px] mx-auto leading-relaxed">
                Kami melayani delivery dalam radius <strong class="text-maroon font-bold">5 km</strong> dari outlet. Di luar area? Kamu tetap bisa ambil sendiri!
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            {{-- Delivery --}}
            <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="0"
                class="group relative bg-white rounded-[24px] p-8 border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_56px_rgba(139,26,26,0.1)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden text-center">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] scale-x-0 origin-left transition-transform duration-400 group-hover:scale-x-100 rounded-t-[24px]"></div>
                <div class="w-14 h-14 bg-[#fdf6f3] border border-[#f0e4df] rounded-[14px] flex items-center justify-center text-maroon text-[1.3rem] mx-auto mb-5">
                    <i class="fas fa-motorcycle"></i>
                </div>
                <h4 class="font-playfair text-[1.1rem] text-[#1a1a1a] mb-2">Delivery</h4>
                <p class="text-[#999] text-[0.85rem] leading-[1.7] mb-4">Radius hingga 5 km dari outlet kami</p>
                <span class="inline-flex items-center gap-1.5 bg-[#fdf6f3] text-maroon text-[0.82rem] font-bold px-4 py-1.5 rounded-full border border-[#f0e4df]">
                    Ongkir Rp15.000
                </span>
            </div>

            {{-- Ambil Sendiri --}}
            <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="100"
                class="group relative bg-white rounded-[24px] p-8 border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_56px_rgba(139,26,26,0.1)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden text-center">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] scale-x-0 origin-left transition-transform duration-400 group-hover:scale-x-100 rounded-t-[24px]"></div>
                <div class="w-14 h-14 bg-[#fdf6f3] border border-[#f0e4df] rounded-[14px] flex items-center justify-center text-maroon text-[1.3rem] mx-auto mb-5">
                    <i class="fas fa-store"></i>
                </div>
                <h4 class="font-playfair text-[1.1rem] text-[#1a1a1a] mb-2">Ambil Sendiri</h4>
                <p class="text-[#999] text-[0.85rem] leading-[1.7] mb-4">Datang langsung ke outlet kami, tersedia untuk semua area</p>
                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-[0.82rem] font-bold px-4 py-1.5 rounded-full border border-green-100">
                    <i class="fas fa-check text-[0.7rem]"></i> Gratis
                </span>
            </div>

            {{-- Lokasi Outlet --}}
            <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="200"
                class="group relative bg-white rounded-[24px] p-8 border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_56px_rgba(139,26,26,0.1)] hover:-translate-y-1.5 transition-all duration-300 overflow-hidden text-center">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-maroon to-[#c0392b] scale-x-0 origin-left transition-transform duration-400 group-hover:scale-x-100 rounded-t-[24px]"></div>
                <div class="w-14 h-14 bg-[#fdf6f3] border border-[#f0e4df] rounded-[14px] flex items-center justify-center text-maroon text-[1.3rem] mx-auto mb-5">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4 class="font-playfair text-[1.1rem] text-[#1a1a1a] mb-2">Lokasi Outlet</h4>
                <p class="text-[#999] text-[0.85rem] leading-[1.7] mb-4">Ummilaa Kitchen, Malang, Jawa Timur</p>
                <span class="inline-flex items-center gap-1.5 bg-[#fdf6f3] text-maroon text-[0.82rem] font-bold px-4 py-1.5 rounded-full border border-[#f0e4df]">
                    <i class="fas fa-location-dot text-[0.7rem]"></i> Kota Malang
                </span>
            </div>

        </div>

        {{-- Map --}}
        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="100"
            class="bg-white rounded-[24px] border border-[#f0eeec] shadow-[0_2px_24px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="flex items-center justify-between px-7 py-5 border-b border-[#f0eeec]">
                <div>
                    <div class="text-[0.7rem] font-bold tracking-[2.5px] uppercase text-maroon mb-1">Peta Jangkauan</div>
                    <h3 class="font-playfair text-[1.3rem] text-[#1a1a1a]">Area Delivery Kami</h3>
                </div>
                <a href="{{ route('contact') }}"
                    class="inline-flex items-center gap-2 border border-[#e8e0e0] text-[#666] px-5 py-[9px] rounded-full font-semibold text-[0.82rem] no-underline hover:border-maroon hover:text-maroon transition-all duration-200">
                    <i class="fas fa-map-marker-alt text-maroon text-[0.75rem]"></i> Lihat Lokasi
                </a>
            </div>
            <div id="mapCoverage" class="h-[280px] md:h-[350px] lg:h-[400px] w-full"></div>
            <div class="flex items-center gap-2 px-7 py-4 bg-[#fdf6f3] border-t border-[#f0eeec]">
                <i class="fas fa-info-circle text-maroon text-[0.82rem]"></i>
                <p class="text-[0.8rem] text-[#999]">Area berwarna menunjukkan jangkauan delivery. Sistem akan otomatis mendeteksi jarak saat checkout.</p>
            </div>
        </div>

    </div>
</section>
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
    // Carousel
    const carousel = document.getElementById('productCarousel');
    if (carousel && carousel.children.length > 0) {
        const gap = 28;
        const originalCards = Array.from(carousel.children);
        originalCards.forEach(card => { carousel.appendChild(card.cloneNode(true)); });

        const totalOriginal = originalCards.length;
        let offset = 0;
        let animating = false;

        function getCardWidth() {
            return carousel.querySelector('.product-card').offsetWidth + gap;
        }

        function step() {
            if (animating) return;
            animating = true;
            offset += getCardWidth();
            carousel.style.transition = 'transform 0.6s ease';
            carousel.style.transform = `translateX(-${offset}px)`;
            setTimeout(() => {
                if (offset >= getCardWidth() * totalOriginal) {
                    carousel.style.transition = 'none';
                    offset = 0;
                    carousel.style.transform = `translateX(0)`;
                }
                animating = false;
            }, 650);
        }

        setInterval(step, 2500);
    }

    // Peta
    document.addEventListener('DOMContentLoaded', function () {
        const outletLat = -7.963536;
        const outletLng = 112.669380;
        const map = L.map('mapCoverage').setView([outletLat, outletLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(map);
        const outletIcon = L.divIcon({
            html: `<div style="background:#8B1A1A;width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.5);"></div>`,
            className: '', iconAnchor: [8, 8],
        });
        L.marker([outletLat, outletLng], { icon: outletIcon }).addTo(map)
            .bindPopup('<b style="color:#8B1A1A">📍 Ummilaa Kitchen</b><br><small>Outlet kami di sini!</small>')
            .openPopup();
        L.circle([outletLat, outletLng], {
            radius: 5000, color: '#8B1A1A', fillColor: '#8B1A1A',
            fillOpacity: 0.12, weight: 2, dashArray: '6, 4',
        }).addTo(map);
    });
</script>
@endpush
