@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@section('content')
<div class="px-4 md:px-10 lg:px-[80px] py-8 lg:py-[60px]">

    @if(session('success'))
    <div class="bg-green-100 text-green-800 px-5 py-[14px] rounded-xl text-[0.9rem] flex items-center gap-[10px] mb-6">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-amber-50 text-orange-700 px-5 py-[14px] rounded-xl text-[0.9rem] flex items-center gap-[10px] mb-6">
        <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-pink-100 text-red-700 px-5 py-[14px] rounded-xl text-[0.9rem] mb-6">
        <div class="flex items-center gap-[10px] mb-2 font-bold">
            <i class="fas fa-times-circle"></i> Harap lengkapi semua field yang wajib diisi:
        </div>
        <ul class="m-0 pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 lg:gap-10 items-start">

        {{-- SIDEBAR --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-5 lg:p-8 lg:text-center lg:sticky lg:top-[90px] shadow-sm">
            {{-- Mobile: horizontal | Desktop: vertical centered --}}
            <div class="flex items-center gap-4 lg:flex-col lg:items-center">
                <div class="w-14 h-14 lg:w-[90px] lg:h-[90px] bg-maroon rounded-full flex items-center justify-center text-white text-[1.3rem] lg:text-[2rem] font-extrabold flex-shrink-0 lg:mx-auto lg:mb-4">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0 lg:w-full">
                    <h3 class="font-playfair text-[1rem] lg:text-[1.2rem] text-[#1a1a1a] mb-0.5 truncate">{{ $user->name }}</h3>
                    <p class="text-[0.78rem] lg:text-[0.85rem] text-[#999] truncate lg:mb-5">{{ $user->email }}</p>
                </div>
            </div>
            {{-- Nav links --}}
            <div class="flex gap-2 mt-4 lg:flex-col lg:gap-1.5">
                <a href="{{ route('profile.user') }}" class="flex-1 flex items-center justify-center lg:justify-start gap-2 px-3 lg:px-4 py-3 lg:py-[11px] rounded-xl text-[0.78rem] lg:text-[0.9rem] font-semibold bg-maroon-100 text-maroon no-underline transition-all">
                    <i class="fas fa-user-edit text-maroon text-[0.7rem] lg:text-sm lg:w-4"></i>
                    <span class="hidden sm:inline lg:inline">Edit Profil</span>
                </a>
                <a href="{{ route('orders') }}" class="flex-1 flex items-center justify-center lg:justify-start gap-2 px-3 lg:px-4 py-3 lg:py-[11px] rounded-xl text-[0.78rem] lg:text-[0.9rem] font-semibold text-[#555] no-underline transition-all hover:bg-maroon-100 hover:text-maroon border border-[#f0f0f0] lg:border-none">
                    <i class="fas fa-box text-maroon text-[0.7rem] lg:text-sm lg:w-4"></i>
                    <span class="hidden sm:inline lg:inline">Riwayat Katalog</span>
                </a>
                <a href="{{ route('catering.history') }}" class="flex-1 flex items-center justify-center lg:justify-start gap-2 px-3 lg:px-4 py-3 lg:py-[11px] rounded-xl text-[0.78rem] lg:text-[0.9rem] font-semibold text-[#555] no-underline transition-all hover:bg-maroon-100 hover:text-maroon border border-[#f0f0f0] lg:border-none">
                    <i class="fas fa-utensils text-maroon text-[0.7rem] lg:text-sm lg:w-4"></i>
                    <span class="hidden sm:inline lg:inline">Riwayat Catering</span>
                </a>
                <a href="{{ route('cart') }}" class="flex-1 flex items-center justify-center lg:justify-start gap-2 px-3 lg:px-4 py-3 lg:py-[11px] rounded-xl text-[0.78rem] lg:text-[0.9rem] font-semibold text-[#555] no-underline transition-all hover:bg-maroon-100 hover:text-maroon border border-[#f0f0f0] lg:border-none">
                    <i class="fas fa-shopping-cart text-maroon text-[0.7rem] lg:text-sm lg:w-4"></i>
                    <span class="hidden sm:inline lg:inline">Keranjang</span>
                </a>
            </div>
        </div>

        {{-- FORM --}}
        <div>
            <form method="POST" action="{{ route('profile.user.update') }}">
                @csrf

                {{-- INFO PRIBADI --}}
                <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-5 sm:p-8 mb-5 shadow-sm">
                    <div class="font-extrabold text-[#1a1a1a] text-[0.88rem] sm:text-base mb-4 sm:mb-6 pb-3 sm:pb-[14px] border-b border-maroon-200 flex items-center gap-2">
                        <i class="fas fa-user text-maroon text-[0.85rem]"></i> Informasi Pribadi
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                        <div>
                            <label class="block font-bold text-[0.72rem] sm:text-[0.82rem] text-[#555] mb-1.5 uppercase tracking-[0.5px]">Nama Lengkap <span class="text-red-500 ml-0.5">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-3 sm:px-4 py-[10px] sm:py-[13px] border-2 border-[#f0f0f0] rounded-xl text-[0.88rem] sm:text-[0.95rem] text-[#333] bg-[#fafafa] transition-all focus:border-maroon focus:bg-white focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-[0.72rem] sm:text-[0.82rem] text-[#555] mb-1.5 uppercase tracking-[0.5px]">Nomor Telepon <span class="text-red-500 ml-0.5">*</span></label>
                            <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="08xxxxxxxxxx"
                                class="w-full px-3 sm:px-4 py-[10px] sm:py-[13px] border-2 border-[#f0f0f0] rounded-xl text-[0.88rem] sm:text-[0.95rem] text-[#333] bg-[#fafafa] transition-all focus:border-maroon focus:bg-white focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-[0.72rem] sm:text-[0.82rem] text-[#555] mb-1.5 uppercase tracking-[0.5px]">Tanggal Lahir <span class="text-red-500 ml-0.5">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                                class="w-full px-3 sm:px-4 py-[10px] sm:py-[13px] border-2 border-[#f0f0f0] rounded-xl text-[0.88rem] sm:text-[0.95rem] text-[#333] bg-[#fafafa] transition-all focus:border-maroon focus:bg-white focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-[0.72rem] sm:text-[0.82rem] text-[#555] mb-1.5 uppercase tracking-[0.5px]">Kode Pos <span class="text-red-500 ml-0.5">*</span></label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $user->kode_pos) }}" placeholder="Contoh: 65148"
                                class="w-full px-3 sm:px-4 py-[10px] sm:py-[13px] border-2 border-[#f0f0f0] rounded-xl text-[0.88rem] sm:text-[0.95rem] text-[#333] bg-[#fafafa] transition-all focus:border-maroon focus:bg-white focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- ALAMAT + MAP --}}
                <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-5 sm:p-8 mb-5 sm:mb-6 shadow-sm">
                    <div class="font-extrabold text-[#1a1a1a] text-[0.88rem] sm:text-base mb-4 sm:mb-6 pb-3 sm:pb-[14px] border-b border-maroon-200 flex items-center gap-[10px]">
                        <i class="fas fa-map-marker-alt text-maroon"></i> Alamat Pengiriman
                    </div>

                    <div class="mb-5">
                        <label class="block font-bold text-[0.82rem] text-[#555] mb-2 uppercase tracking-[0.5px]">
                            Alamat Lengkap <span class="text-red-500 ml-0.5">*</span>
                            <span class="block mt-1 sm:inline sm:ml-2 text-[0.75rem] text-maroon font-semibold normal-case tracking-normal bg-maroon-100 px-2 py-0.5 rounded-lg">
                                <i class="fas fa-lock text-[0.65rem]"></i> Diisi otomatis dari peta
                            </span>
                        </label>
                        <textarea name="alamat" id="alamatInput" rows="3" readonly
                            placeholder="Terisi otomatis saat Anda klik titik di peta"
                            class="w-full px-4 py-[13px] border-2 border-[#e8e8e8] rounded-xl text-[0.95rem] text-[#666] bg-[#f5f5f5] resize-none h-[90px] cursor-default select-none focus:outline-none">{{ old('alamat', $user->alamat) }}</textarea>
                    </div>

                    <div class="mb-5">
                        <label class="block font-bold text-[0.82rem] text-[#555] mb-2 uppercase tracking-[0.5px]">Detail Alamat <span class="text-[#aaa] font-normal normal-case ml-1">(Opsional)</span></label>
                        <textarea name="detail_alamat" rows="2"
                            placeholder="RT/RW, nomor rumah, lantai, patokan, dll. Contoh: RT 03/RW 05, depan masjid Al-Ikhlas"
                            class="w-full px-4 py-[13px] border-2 border-[#f0f0f0] rounded-xl text-[0.95rem] text-[#333] bg-[#fafafa] transition-all focus:border-maroon focus:bg-white focus:outline-none resize-none h-[80px]">{{ old('detail_alamat', $user->detail_alamat) }}</textarea>
                    </div>

                    <div class="text-[0.85rem] text-[#999] mb-3 flex items-start gap-2">
                        <i class="fas fa-info-circle text-maroon mt-0.5 flex-shrink-0"></i>
                        <span>Gunakan tombol <strong class="text-maroon">Lokasi Saya</strong> untuk deteksi otomatis via GPS, cari lokasi, atau klik langsung pada peta untuk menentukan titik alamat Anda.
                        <strong class="text-red-700">(Wajib tentukan lokasi sebelum menyimpan)</strong></span>
                    </div>

                    <div class="flex flex-col gap-[10px] mb-[14px] sm:flex-row sm:flex-wrap">
                        <input type="text" id="mapSearchInput" placeholder="Cari daerah... (contoh: Sukun Malang)"
                            class="w-full sm:flex-1 sm:min-w-0 px-4 py-3 border-2 border-[#f0f0f0] rounded-xl text-[0.92rem] bg-[#fafafa] transition-all focus:border-maroon focus:bg-white focus:outline-none">
                        <div class="flex gap-[10px]">
                            <button type="button" onclick="searchLocation()"
                                class="flex-1 sm:flex-none px-5 py-3 bg-maroon text-white border-none rounded-xl font-bold text-[0.88rem] font-sans cursor-pointer hover:bg-maroon-dark transition-colors">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <button type="button" id="gpsBtn" onclick="detectGPS()"
                                class="flex-1 sm:flex-none px-4 py-3 bg-maroon text-white border-none rounded-xl font-bold text-[0.88rem] font-sans cursor-pointer hover:bg-maroon-dark transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-location-arrow"></i> Lokasi Saya
                            </button>
                            <button type="button" onclick="resetMap()"
                                class="flex-1 sm:flex-none px-4 py-3 bg-[#f0f0f0] text-[#555] border-none rounded-xl font-bold text-[0.88rem] font-sans cursor-pointer hover:bg-[#e0e0e0] transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>

                    <div class="rounded-[16px] overflow-hidden border-2 border-maroon-200 h-[220px] sm:h-[280px] lg:h-[320px] mb-[14px]">
                        <div id="map" class="w-full h-full"></div>
                    </div>

                    <div class="px-4 py-3 bg-maroon-50 rounded-xl flex items-start gap-[10px] min-h-12">
                        <i class="fas fa-map-marker-alt text-maroon mt-0.5 flex-shrink-0"></i>
                        <span id="mapResultText" class="text-[0.88rem] text-[#555] leading-[1.6]">
                            @if($user->lat && $user->lng)
                                Titik tersimpan: {{ $user->alamat }}
                            @else
                                Klik pada peta untuk menentukan lokasi Anda
                            @endif
                        </span>
                    </div>

                    <input type="hidden" name="lat" id="latInput" value="{{ old('lat', $user->lat) }}">
                    <input type="hidden" name="lng" id="lngInput" value="{{ old('lng', $user->lng) }}">
                </div>

                <button type="submit"
                    class="flex items-center justify-center gap-[10px] w-full py-[15px] bg-maroon text-white border-none rounded-xl text-base font-bold font-sans cursor-pointer transition-all hover:bg-maroon-dark hover:-translate-y-px hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)]">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const defaultLat = {{ $user->lat ?? -7.9666 }};
    const defaultLng = {{ $user->lng ?? 112.6326 }};

    const map = L.map('map').setView([defaultLat, defaultLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const markerIcon = L.divIcon({
        html: `<div style="background:#8B1A1A;width:28px;height:28px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,0.3);"></div>`,
        iconSize: [28, 28], iconAnchor: [14, 28], popupAnchor: [0, -28], className: ''
    });

    let marker = null;

    @if($user->lat && $user->lng)
        marker = L.marker([defaultLat, defaultLng], { icon: markerIcon }).addTo(map);
    @endif

    map.on('click', async function(e) {
        const { lat, lng } = e.latlng;
        setMarker(lat, lng);
        await reverseGeocode(lat, lng);
    });

    function setMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
        document.getElementById('latInput').value = lat;
        document.getElementById('lngInput').value = lng;
    }

    async function reverseGeocode(lat, lng) {
        document.getElementById('mapResultText').textContent = 'Memuat alamat...';
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
            const data = await res.json();
            const alamat = data.display_name || `${lat}, ${lng}`;
            document.getElementById('alamatInput').value = alamat;
            document.getElementById('mapResultText').textContent = alamat;
        } catch(e) {
            document.getElementById('mapResultText').textContent = 'Gagal memuat alamat, silakan isi manual.';
        }
    }

    async function searchLocation() {
        const query = document.getElementById('mapSearchInput').value.trim();
        if (!query) return;
        document.getElementById('mapResultText').textContent = 'Mencari lokasi...';
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
            const data = await res.json();
            if (data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                map.setView([lat, lng], 16);
                setMarker(lat, lng);
                await reverseGeocode(lat, lng);
            } else {
                document.getElementById('mapResultText').textContent = 'Lokasi tidak ditemukan. Coba kata kunci lain.';
            }
        } catch(e) {
            document.getElementById('mapResultText').textContent = 'Gagal mencari lokasi.';
        }
    }

    function resetMap() {
        if (marker) { map.removeLayer(marker); marker = null; }
        document.getElementById('latInput').value = '';
        document.getElementById('lngInput').value = '';
        document.getElementById('alamatInput').value = '';
        document.getElementById('mapResultText').textContent = 'Klik pada peta untuk menentukan lokasi Anda';
        document.getElementById('mapSearchInput').value = '';
        map.setView([-7.9666, 112.6326], 14);
    }

    function detectGPS() {
        if (!navigator.geolocation) {
            document.getElementById('mapResultText').textContent = 'Browser Anda tidak mendukung GPS. Silakan pilih lokasi manual di peta.';
            return;
        }
        const btn = document.getElementById('gpsBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendeteksi...';
        document.getElementById('mapResultText').textContent = 'Mendeteksi lokasi GPS...';

        navigator.geolocation.getCurrentPosition(
            async function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 16);
                setMarker(lat, lng);
                await reverseGeocode(lat, lng);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-location-arrow"></i> Lokasi Saya';
            },
            function(error) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-location-arrow"></i> Lokasi Saya';
                const pesan = {
                    1: 'Akses lokasi ditolak. Izinkan akses lokasi di pengaturan browser Anda.',
                    2: 'Posisi tidak dapat ditentukan. Pastikan GPS perangkat aktif.',
                    3: 'Waktu habis. Coba lagi atau pilih lokasi manual di peta.'
                };
                document.getElementById('mapResultText').textContent = pesan[error.code] || 'Gagal mendeteksi lokasi.';
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }

    document.getElementById('mapSearchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); searchLocation(); }
    });
</script>
@endpush
