@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
.form-section { border-left: 3px solid #8B1A1A; padding-left: 1rem; }
.field-group { background: #fafafa; border: 1.5px solid #f0e8e8; border-radius: 14px; padding: 1.25rem; }
</style>
@endpush

@section('content')
<div class="px-4 sm:px-10 lg:px-[80px] py-8 lg:py-[50px] min-h-[70vh]"
     style="background: linear-gradient(160deg, #fff9f9 0%, #ffffff 60%)">

    <div class="max-w-2xl mx-auto">

        {{-- Back --}}
        <a href="{{ $package ? route('catering.package', $package->id) : route('catering.index') }}"
           class="inline-flex items-center gap-2 text-[0.83rem] text-[#999] hover:text-maroon no-underline mb-6 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            {{ $package ? 'Kembali ke Detail Paket' : 'Kembali ke Catering' }}
        </a>

        {{-- Page header --}}
        <div class="mb-7">
            <div class="inline-flex items-center gap-2 bg-maroon-100 text-maroon px-4 py-1.5 rounded-full text-[0.72rem] font-extrabold tracking-widest uppercase mb-3">
                <i class="fas fa-clipboard-list text-[0.65rem]"></i> Form Pemesanan
            </div>
            <h1 class="font-playfair text-[1.8rem] sm:text-[2.2rem] text-[#1a1a1a] mb-1 leading-tight">
                Pemesanan Catering
            </h1>
            <p class="text-[#999] text-[0.88rem] leading-relaxed">
                Isi detail acara Anda. Setelah submit, kami akan menghubungi Anda via WhatsApp untuk konfirmasi.
            </p>
        </div>

        {{-- Errors --}}
        @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm mb-6 flex gap-3 items-start">
            <i class="fas fa-circle-exclamation mt-0.5 shrink-0"></i>
            <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('catering.store') }}" class="space-y-6">
            @csrf

            {{-- ═══ SECTION 1: PILIH PAKET ═══ --}}
            <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden shadow-[0_4px_24px_rgba(139,26,26,0.06)]">
                <div class="px-5 sm:px-6 py-4 border-b border-maroon-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-maroon flex items-center justify-center shrink-0">
                        <span class="text-white font-extrabold text-xs">1</span>
                    </div>
                    <div>
                        <p class="font-extrabold text-[#1a1a1a] text-[0.9rem] leading-none">Pilih Paket Catering</p>
                        <p class="text-[0.75rem] text-[#bbb] mt-0.5">Wajib dipilih sebelum melanjutkan</p>
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    {{-- Package cards (hidden select) --}}
                    <select name="catering_package_id" id="pkgSelect" required class="sr-only">
                        <option value="" data-min-pax="1" disabled {{ old('catering_package_id', $package?->id) ? '' : 'selected' }}></option>
                        @foreach($packages as $p)
                            <option value="{{ $p->id }}"
                                    data-min-pax="{{ $p->min_pax ?? 1 }}"
                                    data-name="{{ $p->name }}"
                                    data-price="{{ number_format($p->price_per_pax, 0, ',', '.') }}"
                                    data-min-label="{{ $p->min_pax ? 'Min. ' . $p->min_pax . ' pax' : '' }}"
                                    data-image="{{ $p->image ? asset('storage/' . $p->image) : '' }}"
                                    {{ (string) old('catering_package_id', $package?->id) === (string) $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Visual package selector --}}
                    <div class="space-y-3" id="pkgCardList">
                        @foreach($packages as $p)
                        @php $isSelected = (string) old('catering_package_id', $package?->id) === (string) $p->id; @endphp
                        <label class="pkg-card flex items-center gap-4 p-3 sm:p-4 rounded-[14px] border-[2px] cursor-pointer transition-all duration-200
                                      {{ $isSelected ? 'border-maroon bg-maroon-50 shadow-[0_4px_16px_rgba(139,26,26,0.1)]' : 'border-maroon-200 bg-white hover:border-maroon hover:bg-maroon-50/50' }}"
                               data-id="{{ $p->id }}">
                            <input type="radio" name="_pkg_radio" value="{{ $p->id }}" class="sr-only" {{ $isSelected ? 'checked' : '' }}>

                            {{-- Thumbnail --}}
                            <div class="shrink-0 w-14 h-14 sm:w-16 sm:h-16 rounded-xl overflow-hidden bg-maroon-50 flex items-center justify-center">
                                @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-bowl-food text-xl text-[#c5b8ae]"></i>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-extrabold text-[#1a1a1a] text-[0.9rem] leading-tight mb-0.5 truncate">{{ $p->name }}</p>
                                <p class="text-maroon font-bold text-[0.82rem]">
                                    Rp{{ number_format($p->price_per_pax, 0, ',', '.') }}<span class="text-[#bbb] font-semibold text-[0.72rem]">/pax</span>
                                </p>
                                @if($p->min_pax)
                                <span class="inline-block mt-1 text-[0.68rem] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                                    Min. {{ $p->min_pax }} pax
                                </span>
                                @endif
                            </div>

                            {{-- Check indicator --}}
                            <div class="shrink-0 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all
                                        {{ $isSelected ? 'bg-maroon border-maroon' : 'border-maroon-300' }}
                                        pkg-check-{{ $p->id }}">
                                @if($isSelected)
                                <i class="fas fa-check text-white text-[0.6rem]"></i>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ═══ SECTION 2: DETAIL ACARA ═══ --}}
            <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden shadow-[0_4px_24px_rgba(139,26,26,0.06)]">
                <div class="px-5 sm:px-6 py-4 border-b border-maroon-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-maroon flex items-center justify-center shrink-0">
                        <span class="text-white font-extrabold text-xs">2</span>
                    </div>
                    <div>
                        <p class="font-extrabold text-[#1a1a1a] text-[0.9rem] leading-none">Detail Acara</p>
                        <p class="text-[0.75rem] text-[#bbb] mt-0.5">Informasi tentang acara yang akan diadakan</p>
                    </div>
                </div>

                <div class="p-5 sm:p-6 space-y-4">
                    <div>
                        <label class="block text-[0.78rem] font-bold text-[#555] mb-1.5">
                            <i class="fas fa-party-horn text-maroon mr-1 text-[0.7rem]"></i>
                            Nama Acara <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_acara" value="{{ old('nama_acara') }}" required
                               placeholder="contoh: Syukuran Pernikahan, Ulang Tahun, Seminar"
                               class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm text-[#333] placeholder:text-[#ccc] focus:outline-none focus:border-maroon bg-white transition-colors">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[0.78rem] font-bold text-[#555] mb-1.5">
                                <i class="fas fa-calendar text-maroon mr-1 text-[0.7rem]"></i>
                                Tanggal Acara <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_acara" value="{{ old('tanggal_acara') }}" required
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm text-[#333] focus:outline-none focus:border-maroon bg-white transition-colors">
                        </div>
                        <div>
                            <label class="block text-[0.78rem] font-bold text-[#555] mb-1.5">
                                <i class="fas fa-clock text-maroon mr-1 text-[0.7rem]"></i>
                                Jam Acara <span class="text-red-500">*</span>
                            </label>
                            <select name="jam_acara" required
                                    class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm text-[#333] focus:outline-none focus:border-maroon bg-white transition-colors">
                                <option value="" disabled {{ old('jam_acara') ? '' : 'selected' }}>Pilih jam acara</option>
                                @for($h = 0; $h < 24; $h++)
                                    @foreach(['00', '30'] as $m)
                                        @php $val = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . $m; @endphp
                                        <option value="{{ $val }}" {{ old('jam_acara') === $val ? 'selected' : '' }}>{{ $val }}</option>
                                    @endforeach
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-[0.78rem] font-bold text-[#555] mb-1.5">
                                <i class="fas fa-truck text-maroon mr-1 text-[0.7rem]"></i>
                                Jam Pengantaran <span class="text-red-500">*</span>
                            </label>
                            <select name="jam_pengantaran" required
                                    class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm text-[#333] focus:outline-none focus:border-maroon bg-white transition-colors">
                                <option value="" disabled {{ old('jam_pengantaran') ? '' : 'selected' }}>Pilih jam pengantaran</option>
                                @for($h = 9; $h <= 16; $h++)
                                    @foreach(['00', '30'] as $m)
                                        @if($h === 16 && $m === '30') @continue @endif
                                        @php $val = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . $m; @endphp
                                        <option value="{{ $val }}" {{ old('jam_pengantaran') === $val ? 'selected' : '' }}>{{ $val }}</option>
                                    @endforeach
                                @endfor
                            </select>
                            <p class="text-[0.72rem] text-[#aaa] mt-1"><i class="fas fa-info-circle mr-1"></i>Pengantaran tersedia 09.00–16.00</p>
                        </div>
                        <div>
                            <label class="block text-[0.78rem] font-bold text-[#555] mb-1.5">
                                <i class="fas fa-users text-maroon mr-1 text-[0.7rem]"></i>
                                Jumlah Pax (porsi) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah_pax" id="jumlahPaxInput" value="{{ old('jumlah_pax') }}"
                                   required min="1" placeholder="contoh: 50"
                                   class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm text-[#333] placeholder:text-[#ccc] focus:outline-none focus:border-maroon bg-white transition-colors">
                            <p id="paxHint" class="hidden text-[0.73rem] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-1.5 mt-1.5 font-semibold">
                                <i class="fas fa-triangle-exclamation mr-1"></i><span id="paxHintText"></span>
                            </p>
                            @error('jumlah_pax')
                                <p class="text-[0.73rem] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ SECTION 3: LOKASI ACARA ═══ --}}
            <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden shadow-[0_4px_24px_rgba(139,26,26,0.06)]">
                <div class="px-5 sm:px-6 py-4 border-b border-maroon-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-maroon flex items-center justify-center shrink-0">
                        <span class="text-white font-extrabold text-xs">3</span>
                    </div>
                    <div>
                        <p class="font-extrabold text-[#1a1a1a] text-[0.9rem] leading-none">Lokasi Acara</p>
                        <p class="text-[0.75rem] text-[#bbb] mt-0.5">Tentukan lokasi dengan peta atau GPS</p>
                    </div>
                </div>

                <div class="p-5 sm:p-6 space-y-4">
                    {{-- Readonly address display --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-[0.78rem] font-bold text-[#555]">
                                <i class="fas fa-map-marker-alt text-maroon mr-1 text-[0.7rem]"></i>
                                Alamat Lokasi <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[0.68rem] text-maroon font-bold bg-maroon-50 border border-maroon-200 px-2 py-0.5 rounded-full">
                                <i class="fas fa-lock text-[0.6rem]"></i> Otomatis dari peta
                            </span>
                        </div>
                        <textarea name="lokasi_acara" id="lokasiAcaraInput" rows="2" readonly required
                            placeholder="Klik pada peta atau gunakan tombol Lokasi Saya"
                            class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm bg-[#f7f7f7] text-[#555] cursor-default select-none resize-none focus:outline-none">{{ old('lokasi_acara') }}</textarea>
                    </div>

                    {{-- Toolbar --}}
                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                        <input type="text" id="mapSearchInput" placeholder="Cari daerah... (contoh: Sukun Malang)"
                            class="w-full sm:flex-1 sm:min-w-0 px-4 py-2.5 border-[1.5px] border-maroon-200 rounded-xl text-[0.83rem] bg-white focus:outline-none focus:border-maroon transition-colors placeholder:text-[#ccc]">
                        <div class="flex gap-2">
                            <button type="button" onclick="searchCateringLocation()"
                                class="flex-1 sm:flex-none px-4 py-2.5 bg-white text-maroon border-[1.5px] border-maroon rounded-xl font-bold text-[0.8rem] cursor-pointer hover:bg-maroon hover:text-white transition-all">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <button type="button" id="gpsBtn" onclick="detectCateringGPS()"
                                class="flex-1 sm:flex-none px-4 py-2.5 bg-maroon text-white border-[1.5px] border-maroon rounded-xl font-bold text-[0.8rem] cursor-pointer hover:opacity-90 transition-opacity flex items-center justify-center gap-1.5">
                                <i class="fas fa-location-arrow"></i> Lokasi Saya
                            </button>
                            <button type="button" onclick="resetCateringMap()"
                                class="px-4 py-2.5 bg-[#f0f0f0] text-[#777] border-[1.5px] border-[#e0e0e0] rounded-xl font-bold text-[0.8rem] cursor-pointer hover:bg-[#e5e5e5] transition-colors">
                                <i class="fas fa-rotate-left"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Map --}}
                    <div class="rounded-[14px] overflow-hidden border-[1.5px] border-maroon-200 h-[240px] sm:h-[280px]">
                        <div id="cateringMap" class="w-full h-full"></div>
                    </div>

                    {{-- Result chip --}}
                    <div class="px-4 py-3 bg-maroon-50 border border-maroon-100 rounded-xl flex items-start gap-2.5 min-h-[44px]">
                        <i class="fas fa-map-pin text-maroon mt-0.5 shrink-0 text-[0.82rem]"></i>
                        <span id="mapResultText" class="text-[0.8rem] text-[#666] leading-relaxed">
                            @if(old('lokasi_acara'))
                                {{ old('lokasi_acara') }}
                            @else
                                Klik pada peta untuk menentukan lokasi acara
                            @endif
                        </span>
                    </div>

                    {{-- Detail Lokasi --}}
                    <div>
                        <label class="block text-[0.78rem] font-bold text-[#555] mb-1.5">
                            <i class="fas fa-building text-maroon mr-1 text-[0.7rem]"></i>
                            Detail Lokasi <span class="text-[#bbb] font-normal">(Opsional)</span>
                        </label>
                        <textarea name="detail_lokasi_acara" rows="2"
                            placeholder="RT/RW, nomor gedung, patokan, nama venue, lantai, dll."
                            class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm text-[#333] placeholder:text-[#ccc] focus:outline-none focus:border-maroon resize-none bg-white transition-colors">{{ old('detail_lokasi_acara') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ═══ SECTION 4: CATATAN & KONTAK ═══ --}}
            <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden shadow-[0_4px_24px_rgba(139,26,26,0.06)]">
                <div class="px-5 sm:px-6 py-4 border-b border-maroon-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-maroon flex items-center justify-center shrink-0">
                        <span class="text-white font-extrabold text-xs">4</span>
                    </div>
                    <div>
                        <p class="font-extrabold text-[#1a1a1a] text-[0.9rem] leading-none">Catatan & Kontak</p>
                        <p class="text-[0.75rem] text-[#bbb] mt-0.5">Permintaan khusus dan data pemesan</p>
                    </div>
                </div>

                <div class="p-5 sm:p-6 space-y-4">
                    <div>
                        <label class="block text-[0.78rem] font-bold text-[#555] mb-1.5">
                            <i class="fas fa-note-sticky text-maroon mr-1 text-[0.7rem]"></i>
                            Catatan Tambahan <span class="text-[#bbb] font-normal">(Opsional)</span>
                        </label>
                        <textarea name="catatan" rows="2"
                            placeholder="Permintaan menu khusus, pantangan makanan, kebutuhan dekorasi, dll."
                            class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm text-[#333] placeholder:text-[#ccc] focus:outline-none focus:border-maroon resize-none bg-white transition-colors">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[0.78rem] font-bold text-[#555] mb-1.5">
                                <i class="fas fa-user text-maroon mr-1 text-[0.7rem]"></i>
                                Nama Pemesan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan', auth()->user()->name) }}" required
                                   class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm text-[#333] focus:outline-none focus:border-maroon bg-white transition-colors">
                        </div>
                        <div>
                            <label class="block text-[0.78rem] font-bold text-[#555] mb-1.5">
                                <i class="fab fa-whatsapp text-maroon mr-1 text-[0.8rem]"></i>
                                No. WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_telepon" value="{{ old('no_telepon', auth()->user()->no_telepon) }}"
                                   required placeholder="08xxxxxxxxxx"
                                   class="w-full px-4 py-3 rounded-xl border-[1.5px] border-maroon-200 text-sm text-[#333] placeholder:text-[#ccc] focus:outline-none focus:border-maroon bg-white transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info note --}}
            <div class="flex items-start gap-3 px-4 py-3.5 bg-blue-50 border border-blue-100 rounded-xl text-[0.8rem] text-blue-700">
                <i class="fas fa-circle-info mt-0.5 shrink-0"></i>
                <span>Harga final ditentukan oleh admin setelah konfirmasi via WhatsApp. Anda tidak perlu membayar dulu sebelum ada kesepakatan.</span>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-4 rounded-xl font-extrabold text-[1rem] text-white flex items-center justify-center gap-2.5 hover:opacity-90 active:scale-[0.99] transition-all shadow-[0_8px_24px_rgba(37,211,102,0.3)]"
                style="background: linear-gradient(135deg, #1ebe5e 0%, #25D366 100%)">
                <i class="fab fa-whatsapp text-xl"></i> Ajukan Pesanan via WhatsApp
            </button>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ── Package card selector ──────────────────────────────────────────────────
(function () {
    const hiddenSelect = document.getElementById('pkgSelect');
    const cards = document.querySelectorAll('.pkg-card');

    cards.forEach(function(card) {
        card.addEventListener('click', function() {
            const id = card.dataset.id;

            // Update hidden select
            hiddenSelect.value = id;

            // Update card visual states
            cards.forEach(function(c) {
                const isActive = c.dataset.id === id;
                c.classList.toggle('border-maroon', isActive);
                c.classList.toggle('bg-maroon-50', isActive);
                c.classList.toggle('shadow-[0_4px_16px_rgba(139,26,26,0.1)]', isActive);
                c.classList.toggle('border-maroon-200', !isActive);
                c.classList.toggle('bg-white', !isActive);

                const check = c.querySelector('.pkg-check-' + c.dataset.id);
                if (check) {
                    if (isActive) {
                        check.classList.add('bg-maroon', 'border-maroon');
                        check.classList.remove('border-maroon-300');
                        check.innerHTML = '<i class="fas fa-check text-white text-[0.6rem]"></i>';
                    } else {
                        check.classList.remove('bg-maroon', 'border-maroon');
                        check.classList.add('border-maroon-300');
                        check.innerHTML = '';
                    }
                }
            });

            // Trigger min_pax update
            updateMinPax();
        });
    });

    // ── Min pax validation ───────────────────────────────────────────────
    const paxInput = document.getElementById('jumlahPaxInput');
    const paxHint  = document.getElementById('paxHint');
    const paxHintText = document.getElementById('paxHintText');

    window.updateMinPax = function() {
        const selected = hiddenSelect.options[hiddenSelect.selectedIndex];
        if (!selected) return;
        const minPax = parseInt(selected.dataset.minPax || '1', 10);
        paxInput.min = minPax;
        if (minPax > 1) {
            paxHintText.textContent = 'Minimum ' + minPax + ' pax untuk paket ini';
            paxHint.classList.remove('hidden');
        } else {
            paxHint.classList.add('hidden');
        }
        const cur = parseInt(paxInput.value, 10);
        if (!isNaN(cur) && cur > 0 && cur < minPax) paxInput.value = minPax;
    };

    updateMinPax();
})();
</script>

<script>
// ── Leaflet map ───────────────────────────────────────────────────────────
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

    const oldAlamat = document.getElementById('lokasiAcaraInput').value;
    if (oldAlamat) document.getElementById('mapResultText').textContent = oldAlamat;

    map.on('click', async function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
        await reverseGeocode(e.latlng.lat, e.latlng.lng);
    });

    function setMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
    }

    async function reverseGeocode(lat, lng) {
        document.getElementById('mapResultText').textContent = 'Memuat alamat...';
        try {
            const res  = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
            const data = await res.json();
            const alamat = data.display_name || `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            document.getElementById('lokasiAcaraInput').value = alamat;
            document.getElementById('mapResultText').textContent = alamat;
        } catch(e) {
            document.getElementById('mapResultText').textContent = 'Gagal memuat alamat, silakan coba lagi.';
        }
    }

    window.searchCateringLocation = async function() {
        const query = document.getElementById('mapSearchInput').value.trim();
        if (!query) return;
        document.getElementById('mapResultText').textContent = 'Mencari lokasi...';
        try {
            const res  = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
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
            document.getElementById('mapResultText').textContent = 'Browser tidak mendukung GPS. Pilih lokasi manual di peta.';
            return;
        }
        const btn = document.getElementById('gpsBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendeteksi...';
        document.getElementById('mapResultText').textContent = 'Mendeteksi lokasi GPS...';

        navigator.geolocation.getCurrentPosition(
            async function(pos) {
                const lat = pos.coords.latitude, lng = pos.coords.longitude;
                map.setView([lat, lng], 16);
                setMarker(lat, lng);
                await reverseGeocode(lat, lng);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-location-arrow"></i> Lokasi Saya';
            },
            function(err) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-location-arrow"></i> Lokasi Saya';
                const pesan = {
                    1: 'Akses lokasi ditolak. Izinkan akses di pengaturan browser.',
                    2: 'Posisi tidak dapat ditentukan. Pastikan GPS aktif.',
                    3: 'Waktu habis. Coba lagi atau pilih lokasi manual.'
                };
                document.getElementById('mapResultText').textContent = pesan[err.code] || 'Gagal mendeteksi lokasi.';
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
@endpush
