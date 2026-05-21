@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@section('content')
<div class="px-4 sm:px-10 lg:px-[80px] py-8 lg:py-[50px] min-h-[70vh]">

    <div class="max-w-2xl mx-auto">
        <a href="{{ route('catering.index') }}" class="inline-flex items-center gap-2 text-[0.85rem] text-[#888] hover:text-maroon no-underline mb-4">
            <i class="fas fa-arrow-left"></i> Kembali ke Catering
        </a>
        <h1 class="font-playfair text-[1.7rem] sm:text-[2rem] text-[#1a1a1a] mb-1">Form Pemesanan Catering</h1>
        <p class="text-[#888] text-[0.88rem] mb-6">Isi detail acara Anda. Setelah submit, Anda akan diarahkan ke WhatsApp admin untuk konfirmasi & negosiasi harga.</p>

        @if($errors->any())
        <div class="p-4 bg-pink-100 border border-red-300 text-red-700 rounded-xl text-sm mb-5">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('catering.store') }}" class="bg-white border border-maroon-200 rounded-[18px] p-5 sm:p-7 space-y-5">
            @csrf

            <div>
                <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Paket Catering</label>
                <select name="catering_package_id" id="pkgSelect"
                    class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon bg-white">
                    <option value="" data-min-pax="1">Custom (tanpa paket)</option>
                    @foreach($packages as $p)
                        <option value="{{ $p->id }}" data-min-pax="{{ $p->min_pax ?? 1 }}"
                            {{ (string) old('catering_package_id', $package?->id) === (string) $p->id ? 'selected' : '' }}>
                            {{ $p->name }} — Rp{{ number_format($p->price_per_pax, 0, ',', '.') }}/pax
                            @if($p->min_pax) (min. {{ $p->min_pax }} pax) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Nama Acara <span class="text-red-500">*</span></label>
                <input type="text" name="nama_acara" value="{{ old('nama_acara') }}" required placeholder="contoh: Syukuran Pernikahan"
                    class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Tanggal Acara <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_acara" value="{{ old('tanggal_acara') }}" required min="{{ now()->format('Y-m-d') }}"
                        class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Jumlah Pax (porsi) <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_pax" id="jumlahPaxInput" value="{{ old('jumlah_pax') }}" required min="1" placeholder="contoh: 50"
                        class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
                    <p id="paxHint" class="hidden text-[0.75rem] text-amber-600 mt-1 font-semibold"></p>
                    @error('jumlah_pax')
                        <p class="text-[0.75rem] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- LOKASI ACARA — Map Picker --}}
            <div>
                <label class="block text-[0.8rem] font-bold text-[#444] mb-1 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-maroon text-[0.85rem]"></i>
                    Lokasi Acara <span class="text-red-500">*</span>
                    <span class="text-[0.72rem] text-maroon font-semibold bg-maroon-50 px-2 py-0.5 rounded-lg">
                        <i class="fas fa-lock text-[0.65rem]"></i> Diisi otomatis dari peta
                    </span>
                </label>
                <textarea name="lokasi_acara" id="lokasiAcaraInput" rows="2" readonly required
                    placeholder="Klik pada peta atau gunakan Lokasi Saya untuk mengisi alamat"
                    class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm bg-[#f7f7f7] text-[#555] cursor-default select-none resize-none focus:outline-none mb-3">{{ old('lokasi_acara') }}</textarea>

                <div class="text-[0.78rem] text-[#999] mb-3 flex items-start gap-1.5">
                    <i class="fas fa-info-circle text-maroon mt-0.5 flex-shrink-0"></i>
                    <span>Gunakan <strong class="text-maroon">Lokasi Saya</strong> untuk GPS otomatis, cari nama daerah, atau klik langsung pada peta.</span>
                </div>

                {{-- Toolbar: Search + GPS + Reset --}}
                <div class="flex flex-col gap-2 mb-3 sm:flex-row sm:flex-wrap">
                    <input type="text" id="mapSearchInput" placeholder="Cari daerah... (contoh: Sukun Malang)"
                        class="w-full sm:flex-1 sm:min-w-0 px-4 py-2.5 border border-maroon-200 rounded-xl text-[0.85rem] bg-white focus:outline-none focus:border-maroon transition-colors">
                    <div class="flex gap-2">
                        <button type="button" onclick="searchCateringLocation()"
                            class="flex-1 sm:flex-none px-4 py-2.5 bg-maroon text-white border-none rounded-xl font-bold text-[0.82rem] cursor-pointer hover:bg-maroon-dark transition-colors">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <button type="button" id="gpsBtn" onclick="detectCateringGPS()"
                            class="flex-1 sm:flex-none px-4 py-2.5 bg-maroon text-white border-none rounded-xl font-bold text-[0.82rem] cursor-pointer hover:bg-maroon-dark transition-colors flex items-center justify-center gap-1.5">
                            <i class="fas fa-location-arrow"></i> Lokasi Saya
                        </button>
                        <button type="button" onclick="resetCateringMap()"
                            class="flex-1 sm:flex-none px-4 py-2.5 bg-[#f0f0f0] text-[#555] border-none rounded-xl font-bold text-[0.82rem] cursor-pointer hover:bg-[#e0e0e0] transition-colors">
                            Reset
                        </button>
                    </div>
                </div>

                {{-- Map container --}}
                <div class="rounded-[14px] overflow-hidden border border-maroon-200 h-[240px] sm:h-[290px] mb-3">
                    <div id="cateringMap" class="w-full h-full"></div>
                </div>

                {{-- Result info --}}
                <div class="px-4 py-3 bg-maroon-50 rounded-xl flex items-start gap-2 min-h-[44px]">
                    <i class="fas fa-map-marker-alt text-maroon mt-0.5 flex-shrink-0 text-[0.85rem]"></i>
                    <span id="mapResultText" class="text-[0.82rem] text-[#555] leading-relaxed">
                        @if(old('lokasi_acara'))
                            {{ old('lokasi_acara') }}
                        @else
                            Klik pada peta untuk menentukan lokasi acara
                        @endif
                    </span>
                </div>
            </div>

            {{-- Detail Lokasi --}}
            <div>
                <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">
                    Detail Lokasi <span class="text-[#aaa] font-normal">(Opsional)</span>
                </label>
                <textarea name="detail_lokasi_acara" rows="2"
                    placeholder="RT/RW, nomor gedung, patokan, nama venue, lantai, dll. Contoh: Gedung Serbaguna RT 03/RW 05, depan Masjid Al-Ikhlas"
                    class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon resize-none">{{ old('detail_lokasi_acara') }}</textarea>
            </div>

            <div>
                <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Catatan Tambahan</label>
                <textarea name="catatan" rows="2" placeholder="Permintaan khusus, menu, dll (opsional)"
                    class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon resize-none">{{ old('catatan') }}</textarea>
            </div>

            <div class="border-t border-maroon-200 pt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">Nama Pemesan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan', auth()->user()->name) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
                </div>
                <div>
                    <label class="block text-[0.8rem] font-bold text-[#444] mb-1.5">No. WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', auth()->user()->no_telepon) }}" required placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-3 rounded-xl border border-maroon-200 text-sm focus:outline-none focus:border-maroon">
                </div>
            </div>

            <button type="submit"
                class="w-full py-3.5 rounded-xl font-extrabold text-[0.95rem] text-white flex items-center justify-center gap-2 hover:opacity-90 transition-all"
                style="background-color:#25D366">
                <i class="fab fa-whatsapp text-lg"></i> Pesan via WhatsApp
            </button>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const defaultLat = -7.9666;
    const defaultLng = 112.6326;

    const map = L.map('cateringMap').setView([defaultLat, defaultLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const markerIcon = L.divIcon({
        html: `<div style="background:#8B1A1A;width:26px;height:26px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,0.3);"></div>`,
        iconSize: [26, 26], iconAnchor: [13, 26], popupAnchor: [0, -26], className: ''
    });

    let marker = null;

    // Jika ada old value dari form error, tampilkan teks tanpa perlu reload marker
    const oldAlamat = document.getElementById('lokasiAcaraInput').value;
    if (oldAlamat) {
        document.getElementById('mapResultText').textContent = oldAlamat;
    }

    map.on('click', async function(e) {
        const { lat, lng } = e.latlng;
        setMarker(lat, lng);
        await reverseGeocode(lat, lng);
    });

    function setMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
    }

    async function reverseGeocode(lat, lng) {
        document.getElementById('mapResultText').textContent = 'Memuat alamat...';
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
            const data = await res.json();
            const alamat = data.display_name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            document.getElementById('lokasiAcaraInput').value = alamat;
            document.getElementById('mapResultText').textContent = alamat;
        } catch(e) {
            document.getElementById('mapResultText').textContent = 'Gagal memuat alamat, silakan isi ulang.';
        }
    }

    window.searchCateringLocation = async function() {
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
    };

    window.detectCateringGPS = function() {
        if (!navigator.geolocation) {
            document.getElementById('mapResultText').textContent = 'Browser Anda tidak mendukung GPS. Pilih lokasi manual di peta.';
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
                    1: 'Akses lokasi ditolak. Izinkan akses lokasi di pengaturan browser.',
                    2: 'Posisi tidak dapat ditentukan. Pastikan GPS aktif.',
                    3: 'Waktu habis. Coba lagi atau pilih lokasi manual di peta.'
                };
                document.getElementById('mapResultText').textContent = pesan[error.code] || 'Gagal mendeteksi lokasi.';
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    };

    window.resetCateringMap = function() {
        if (marker) { map.removeLayer(marker); marker = null; }
        document.getElementById('lokasiAcaraInput').value = '';
        document.getElementById('mapResultText').textContent = 'Klik pada peta untuk menentukan lokasi acara';
        document.getElementById('mapSearchInput').value = '';
        map.setView([defaultLat, defaultLng], 13);
    };

    document.getElementById('mapSearchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); searchCateringLocation(); }
    });
})();
</script>

<script>
// Validasi min_pax paket: update atribut min dan tampilkan hint secara dinamis.
(function () {
    const pkgSelect  = document.getElementById('pkgSelect');
    const paxInput   = document.getElementById('jumlahPaxInput');
    const paxHint    = document.getElementById('paxHint');
    if (!pkgSelect || !paxInput || !paxHint) return;

    function updateMin() {
        const opt    = pkgSelect.options[pkgSelect.selectedIndex];
        const minPax = parseInt(opt.dataset.minPax || '1', 10);
        paxInput.min = minPax;
        if (minPax > 1) {
            paxHint.textContent = 'Minimum ' + minPax + ' pax untuk paket ini';
            paxHint.classList.remove('hidden');
        } else {
            paxHint.classList.add('hidden');
        }
        const cur = parseInt(paxInput.value, 10);
        if (!isNaN(cur) && cur > 0 && cur < minPax) paxInput.value = minPax;
    }

    pkgSelect.addEventListener('change', updateMin);
    updateMin();
})();
</script>
@endpush
