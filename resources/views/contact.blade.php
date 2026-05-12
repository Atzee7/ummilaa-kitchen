@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-[linear-gradient(135deg,#8B1A1A_0%,#4a0c0c_100%)] px-[80px] py-[70px] text-white">
    <div class="absolute -right-[80px] -top-[80px] w-[400px] h-[400px] rounded-full bg-white/[0.04] pointer-events-none"></div>
    <div class="relative z-[1] max-w-[600px]">
        <div class="inline-flex items-center gap-2 bg-white/[0.12] text-white px-[18px] py-[7px] rounded-[30px] text-[0.8rem] font-bold tracking-[1.5px] uppercase mb-5">
            <i class="fas fa-headset"></i> Hubungi Kami
        </div>
        <h1 class="font-playfair text-[2.8rem] leading-[1.2] mb-[14px]">Kami Siap Membantu Anda</h1>
        <p class="opacity-85 text-[0.97rem] leading-[1.8]">Ada pertanyaan, pemesanan, atau ingin tahu lebih lanjut? Jangan ragu untuk menghubungi kami melalui kontak di bawah ini.</p>
    </div>
</section>

{{-- BODY --}}
<div class="grid grid-cols-[1fr_1.4fr] gap-[50px] px-[80px] py-[70px]">

    {{-- KIRI: Info + Sosmed --}}
    <div class="flex flex-col gap-5">

        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[18px] p-6 flex items-start gap-4 transition-all duration-200 hover:shadow-[0_8px_30px_rgba(139,26,26,0.08)] hover:-translate-y-0.5">
            <div class="w-12 h-12 bg-maroon-100 rounded-[14px] flex items-center justify-center text-maroon text-[1.1rem] flex-shrink-0">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-[#1a1a1a] text-[0.92rem] mb-1">Alamat</h4>
                <p class="text-[#777] text-[0.88rem] leading-[1.6]">Jl. Contoh No. 123, Kelurahan Sukun,<br>Kota Malang, Jawa Timur 65148</p>
            </div>
        </div>

        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[18px] p-6 flex items-start gap-4 transition-all duration-200 hover:shadow-[0_8px_30px_rgba(139,26,26,0.08)] hover:-translate-y-0.5">
            <div class="w-12 h-12 bg-maroon-100 rounded-[14px] flex items-center justify-center text-maroon text-[1.1rem] flex-shrink-0">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-[#1a1a1a] text-[0.92rem] mb-1">Telepon / WhatsApp</h4>
                <a href="https://wa.me/6281234567890" target="_blank" class="text-[#777] text-[0.88rem] no-underline hover:text-maroon transition-colors">+62 812-3456-7890</a>
            </div>
        </div>

        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[18px] p-6 flex items-start gap-4 transition-all duration-200 hover:shadow-[0_8px_30px_rgba(139,26,26,0.08)] hover:-translate-y-0.5">
            <div class="w-12 h-12 bg-maroon-100 rounded-[14px] flex items-center justify-center text-maroon text-[1.1rem] flex-shrink-0">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-[#1a1a1a] text-[0.92rem] mb-1">Email</h4>
                <a href="mailto:ummilaakitchen@gmail.com" class="text-[#777] text-[0.88rem] no-underline hover:text-maroon transition-colors">ummilaakitchen@gmail.com</a>
            </div>
        </div>

        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[18px] p-6 flex items-start gap-4 transition-all duration-200 hover:shadow-[0_8px_30px_rgba(139,26,26,0.08)] hover:-translate-y-0.5">
            <div class="w-12 h-12 bg-maroon-100 rounded-[14px] flex items-center justify-center text-maroon text-[1.1rem] flex-shrink-0">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-[#1a1a1a] text-[0.92rem] mb-1">Jam Operasional</h4>
                <p class="text-[#777] text-[0.88rem] leading-[1.6]">Senin – Sabtu: 08.00 – 20.00 WIB<br>Minggu: 09.00 – 17.00 WIB</p>
            </div>
        </div>

        <div class="mt-2">
            <div class="text-[0.72rem] font-extrabold tracking-[2px] uppercase text-[#bbb] mb-[14px]">Ikuti Kami</div>
            <div class="flex flex-col gap-3">
                <a href="https://instagram.com/ummilaakitchen" target="_blank" class="flex items-center gap-[14px] px-[18px] py-[14px] rounded-[14px] border-[1.5px] border-maroon-200 bg-white no-underline transition-all duration-200 hover:border-maroon hover:bg-maroon-100 hover:translate-x-1">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-[1.1rem] text-white flex-shrink-0" style="background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888)">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <div>
                        <strong class="block text-[0.9rem] text-[#1a1a1a] font-extrabold">@ummilaakitchen</strong>
                        <span class="text-[0.8rem] text-[#999]">Instagram</span>
                    </div>
                    <i class="fas fa-arrow-right ml-auto text-[#ccc] text-[0.8rem]"></i>
                </a>
                <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center gap-[14px] px-[18px] py-[14px] rounded-[14px] border-[1.5px] border-maroon-200 bg-white no-underline transition-all duration-200 hover:border-maroon hover:bg-maroon-100 hover:translate-x-1">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-[1.1rem] text-white flex-shrink-0 bg-[#25D366]">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <strong class="block text-[0.9rem] text-[#1a1a1a] font-extrabold">+62 812-3456-7890</strong>
                        <span class="text-[0.8rem] text-[#999]">WhatsApp</span>
                    </div>
                    <i class="fas fa-arrow-right ml-auto text-[#ccc] text-[0.8rem]"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- KANAN: Map --}}
    <div>
        <div class="mb-5">
            <div class="text-[0.72rem] font-extrabold tracking-[2px] uppercase text-maroon mb-2">Lokasi Kami</div>
            <h2 class="font-playfair text-[1.8rem] text-[#1a1a1a]">Temukan Kami di Sini</h2>
            <p class="text-[#999] text-[0.88rem] mt-1.5">Kunjungi langsung atau gunakan maps untuk navigasi</p>
        </div>
        <div class="rounded-[20px] overflow-hidden shadow-[0_8px_32px_rgba(139,26,26,0.1)] border-2 border-maroon-200 h-[380px]">
            <div id="map" class="w-full h-full"></div>
        </div>
        <div class="mt-4 px-5 py-4 bg-maroon-50 rounded-[14px] flex items-center gap-3">
            <i class="fas fa-map-marker-alt text-maroon text-[1rem] flex-shrink-0"></i>
            <p class="text-[0.88rem] text-[#555] leading-[1.6]">Jalan kapi anala 1 blok 15n no 18 Sawojajar 2 Malang</p>
            <a href="https://maps.app.goo.gl/mtTjXXxSi1AiyF5t7" target="_blank" class="text-maroon font-bold text-[0.85rem] ml-2 no-underline hover:underline whitespace-nowrap">
                <i class="fas fa-external-link-alt"></i> Buka di Google Maps
            </a>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
