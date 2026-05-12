@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

{{-- HERO --}}
<section class="grid grid-cols-2 items-center gap-[60px] px-[80px] py-[80px] min-h-[90vh] bg-white relative overflow-hidden
    before:content-[''] before:absolute before:top-[-100px] before:right-[-100px] before:w-[600px] before:h-[600px] before:rounded-full before:bg-[radial-gradient(circle,#fdf0f0_0%,transparent_70%)] before:z-0">
    <div class="relative z-[1]">
        <div class="inline-flex items-center gap-2 bg-maroon-100 text-maroon px-[18px] py-2 rounded-[30px] text-[0.82rem] font-bold tracking-[1px] uppercase mb-6">
            <i class="fas fa-fire text-[0.75rem]"></i> Kuliner Favorit Malang
        </div>
        <h1 class="font-playfair text-[3.2rem] leading-[1.2] text-[#1a1a1a] mb-5">
            Pesan <span class="text-maroon">Makanan Lezat</span> & Fresh Langsung ke Pintu Anda
        </h1>
        <p class="text-base text-[#777] leading-[1.8] mb-9 max-w-[400px]">Aneka dimsum, risol, frozen food, dan catering berkualitas dari dapur Ummilaa Kitchen. Mudah dipesan, cepat dikirim.</p>
        <div class="flex items-center gap-5">
            <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-[10px] bg-maroon text-white px-8 py-[15px] rounded-xl font-bold text-[0.95rem] transition-all duration-200 shadow-[0_8px_24px_rgba(139,26,26,0.25)] hover:bg-maroon-dark hover:-translate-y-0.5 hover:shadow-[0_12px_32px_rgba(139,26,26,0.35)] no-underline">
                <i class="fas fa-utensils"></i> Lihat Produk
            </a>
            <a href="{{ route('about') }}" class="inline-flex items-center gap-2 text-maroon font-bold text-[0.95rem] transition-all duration-200 hover:gap-3 no-underline">
                Tentang Kami <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="relative z-[1]">
        <img class="w-full h-[520px] object-cover rounded-[24px] shadow-[0_30px_70px_rgba(139,26,26,0.2)]"
             src="https://images.unsplash.com/photo-1563245372-f21724e3856d?w=800" alt="Dimsum Ummilaa Kitchen">
        <div class="absolute top-[30px] right-[-20px] bg-maroon rounded-[16px] px-5 py-4 shadow-[0_10px_40px_rgba(139,26,26,0.3)] text-white text-center">
            <strong class="block text-[1.4rem] font-playfair">50+</strong>
            <span class="text-[0.78rem] opacity-85">Varian Menu</span>
        </div>
    </div>
</section>

<div class="h-px bg-gradient-to-r from-transparent via-maroon-200 to-transparent mx-[80px]"></div>

{{-- CATALOGUE --}}
<section class="px-[80px] py-[80px]" id="catalogue">
    <div class="mb-[50px]">
        <div class="flex justify-between items-end">
            <div>
                <div class="text-[0.78rem] font-bold tracking-[2px] uppercase text-maroon mb-2.5">Menu Pilihan</div>
                <h2 class="font-playfair text-[2.2rem] text-[#1a1a1a]">Our Special <span class="text-maroon">Catalogue</span></h2>
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
    <div class="overflow-hidden">
        <div class="flex gap-7 will-change-transform" id="productCarousel">
            @foreach($featuredProducts as $product)
            <a href="{{ $product->status !== 'habis' ? route('product.show', $product->id) : '#' }}"
               class="product-card flex-[0_0_calc(25%-21px)] rounded-[20px] overflow-hidden bg-white border border-maroon-200 transition-all duration-300 no-underline block {{ $product->status === 'habis' ? 'pointer-events-none' : '' }} hover:shadow-[0_16px_48px_rgba(139,26,26,0.12)] hover:-translate-y-1.5">
                <div class="relative overflow-hidden">
                    <img class="w-full h-[200px] object-cover bg-maroon-50 block transition-transform duration-[400ms] {{ $product->status === 'habis' ? 'grayscale brightness-50' : 'group-hover:scale-105' }}"
                         src="{{ $product->image && Str::startsWith($product->image, 'products/') ? asset('storage/' . $product->image) : $product->image }}"
                         alt="{{ $product->name }}">

                    @if($product->badge)
                    <span class="absolute top-[14px] left-[14px] px-[14px] py-[5px] rounded-[30px] text-[0.72rem] font-extrabold tracking-[1px] uppercase text-white backdrop-blur-sm
                        {{ $product->badge == 'new' ? 'bg-blue-700/90' : ($product->badge == 'terlaris' ? 'bg-orange-700/90' : 'bg-maroon/90') }}">
                        {{ $product->badge == 'new' ? 'New' : ($product->badge == 'terlaris' ? 'Terlaris' : 'Unggulan') }}
                    </span>
                    @endif

                    <span class="absolute bottom-3 left-3 px-3 py-1 rounded-[20px] text-[0.72rem] font-bold {{ $product->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-pink-100 text-red-800' }}">
                        @if($product->status === 'ready') ● Ready Stock @else ● Habis @endif
                    </span>
                </div>
                <div class="p-[18px_20px_20px]">
                    <h3 class="font-bold text-[#1a1a1a] text-base mb-1.5">{{ $product->name }}</h3>
                    <p class="text-[0.82rem] text-[#aaa] leading-[1.5] mb-4 min-h-[38px]">{{ Str::limit($product->description, 65) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-maroon text-base">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="bg-maroon text-white rounded-[10px] w-[38px] h-[38px] flex items-center justify-center text-[0.85rem] transition-all duration-200 {{ $product->status === 'habis' ? 'bg-[#ccc]' : 'hover:bg-maroon-dark hover:scale-110' }}">
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

<div class="h-px bg-gradient-to-r from-transparent via-maroon-200 to-transparent mx-[80px]"></div>

{{-- AREA PENGIRIMAN --}}
<section class="px-[80px] py-[80px] bg-[#fdf9f9]">
    <div class="text-center mb-9">
        <div class="text-[0.78rem] font-bold tracking-[2px] uppercase text-maroon mb-2.5">Jangkauan Layanan</div>
        <h2 class="font-playfair text-[2.2rem] text-[#1a1a1a]">Area <span class="text-maroon">Pengiriman</span></h2>
        <p class="text-[#999] mt-2 text-[0.95rem]">
            Kami melayani delivery dalam radius <strong class="text-maroon">5 km</strong> dari outlet. Di luar area? Kamu tetap bisa ambil sendiri!
        </p>
    </div>

    <div class="grid grid-cols-3 gap-5 mb-8">
        <div class="bg-white border border-maroon-200 rounded-[20px] p-[28px_24px] text-center shadow-[0_4px_16px_rgba(139,26,26,0.06)]">
            <div class="text-[2.2rem] mb-3">🛵</div>
            <div class="font-bold text-[#1a1a1a] text-[0.95rem] mb-1.5">Delivery</div>
            <div class="text-[0.82rem] text-[#aaa] leading-[1.5] mb-2.5">Radius hingga 5 km dari outlet kami</div>
            <div class="text-[0.88rem] font-extrabold text-maroon">Ongkir Rp15.000</div>
        </div>
        <div class="bg-white border border-maroon-200 rounded-[20px] p-[28px_24px] text-center shadow-[0_4px_16px_rgba(139,26,26,0.06)]">
            <div class="text-[2.2rem] mb-3">🏠</div>
            <div class="font-bold text-[#1a1a1a] text-[0.95rem] mb-1.5">Ambil Sendiri</div>
            <div class="text-[0.82rem] text-[#aaa] leading-[1.5] mb-2.5">Datang langsung ke outlet kami, tersedia untuk semua area</div>
            <div class="text-[0.88rem] font-extrabold text-green-700">Gratis</div>
        </div>
        <div class="bg-white border border-maroon-200 rounded-[20px] p-[28px_24px] text-center shadow-[0_4px_16px_rgba(139,26,26,0.06)]">
            <div class="text-[2.2rem] mb-3">📍</div>
            <div class="font-bold text-[#1a1a1a] text-[0.95rem] mb-1.5">Lokasi Outlet</div>
            <div class="text-[0.82rem] text-[#aaa] leading-[1.5] mb-2.5">Ummilaa Kitchen, Malang, Jawa Timur</div>
            <div class="text-[0.88rem] font-extrabold text-blue-800">Kota Malang</div>
        </div>
    </div>

    <div class="rounded-[20px] overflow-hidden shadow-[0_8px_32px_rgba(139,26,26,0.1)] border border-maroon-200">
        <div id="mapCoverage" class="h-[420px] w-full"></div>
    </div>

    <p class="text-center text-[0.8rem] text-[#bbb] mt-[14px]">
        <i class="fas fa-info-circle"></i>
        Area berwarna menunjukkan jangkauan delivery. Sistem akan otomatis mendeteksi jarak saat checkout.
    </p>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
