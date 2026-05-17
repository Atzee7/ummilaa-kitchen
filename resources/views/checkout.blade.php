@extends('layouts.app')


@section('content')

<div class="px-4 md:px-10 lg:px-[80px] py-8 lg:py-[60px] bg-[#faf8f8] min-h-screen">

    <div class="mb-10">
        <h1 class="font-playfair text-[2.2rem] text-[#1a1a1a]">Checkout</h1>
        <p class="text-[#999] mt-1.5 text-[0.9rem]">Periksa pesanan Anda dan selesaikan pembayaran</p>
    </div>

    {{-- STEP INDICATOR --}}
    <div class="flex items-center gap-0 mb-10">
        <div class="flex items-center gap-[10px] text-[0.82rem] font-bold text-green-700">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[0.78rem] font-extrabold bg-green-700 text-white">
                <i class="fas fa-check text-[0.65rem]"></i>
            </div>
            Keranjang
        </div>
        <div class="w-10 h-0.5 bg-green-700 mx-2"></div>
        <div class="flex items-center gap-[10px] text-[0.82rem] font-bold text-maroon">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[0.78rem] font-extrabold bg-maroon text-white">2</div>
            Checkout
        </div>
        <div class="w-10 h-0.5 bg-[#eee] mx-2"></div>
        <div class="flex items-center gap-[10px] text-[0.82rem] font-bold text-[#bbb]">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[0.78rem] font-extrabold bg-[#eee] text-[#bbb]">3</div>
            Selesai
        </div>
    </div>

    @if($errors->any())
    <div class="bg-pink-100 text-red-700 px-5 py-[14px] rounded-xl mb-5 text-[0.88rem] flex items-center gap-[10px]">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-6 lg:gap-8 items-start">

            {{-- KIRI --}}
            <div>

                {{-- 1. ALAMAT --}}
                <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 mb-5 shadow-[0_2px_12px_rgba(139,26,26,0.04)]">
                    <div class="font-extrabold text-[#1a1a1a] text-base mb-[22px] pb-[14px] border-b border-[#f5f0f0] flex items-center gap-[10px]">
                        <i class="fas fa-map-marker-alt text-maroon w-5 text-center"></i>
                        Alamat Pengiriman
                        <a href="{{ route('profile.user') }}" class="ml-auto text-[0.72rem] font-bold bg-maroon-100 text-maroon px-[10px] py-[3px] rounded-[20px] no-underline">
                            <i class="fas fa-edit text-[0.65rem]"></i> Edit Profil
                        </a>
                    </div>
                    <div class="bg-[#fdf9f9] border-[1.5px] border-maroon-200 rounded-[14px] px-[22px] py-5">
                        {{-- Nama & Telepon --}}
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-maroon-200">
                            <div>
                                <div class="font-extrabold text-[#1a1a1a] text-base">{{ Auth::user()->name }}</div>
                                <div class="text-[0.83rem] text-[#888] mt-0.5">
                                    <i class="fas fa-phone text-[0.7rem] text-maroon mr-1"></i>
                                    {{ Auth::user()->no_telepon ?? 'Belum diisi' }}
                                </div>
                            </div>
                            @if(Auth::user()->lat && Auth::user()->lng)
                            <div data-permanent class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-[0.72rem] font-bold px-3 py-1.5 rounded-[20px] flex-shrink-0">
                                <i class="fas fa-check-circle text-[0.65rem]"></i> GPS Tersimpan
                            </div>
                            @endif
                        </div>
                        {{-- Alamat dari Peta --}}
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-8 h-8 bg-maroon-100 rounded-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-map-marker-alt text-maroon text-[0.8rem]"></i>
                            </div>
                            <div>
                                <p class="text-[0.72rem] font-bold text-[#bbb] uppercase tracking-[1px] mb-0.5">Alamat</p>
                                <p class="text-[0.88rem] text-[#444] leading-[1.7]">{{ Auth::user()->alamat ?? 'Alamat belum diisi. Silakan update profil.' }}</p>
                            </div>
                        </div>
                        {{-- Detail Alamat --}}
                        @if(Auth::user()->detail_alamat)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-maroon-100 rounded-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-sticky-note text-maroon text-[0.8rem]"></i>
                            </div>
                            <div>
                                <p class="text-[0.72rem] font-bold text-[#bbb] uppercase tracking-[1px] mb-0.5">Detail Alamat</p>
                                <p class="text-[0.88rem] text-[#444] leading-[1.7]">{{ Auth::user()->detail_alamat }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <input type="hidden" name="nama_penerima" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="no_telepon" value="{{ Auth::user()->no_telepon }}">
                    <input type="hidden" name="alamat" value="{{ Auth::user()->alamat }}">
                    <input type="hidden" name="detail_alamat" value="{{ Auth::user()->detail_alamat }}">
                    @if(!Auth::user()->alamat)
                    <div class="bg-amber-50 border-[1.5px] border-amber-300 rounded-[14px] px-5 py-4 flex items-center gap-3 text-[0.85rem] text-orange-700 mt-[14px]">
                        <i class="fas fa-exclamation-triangle text-[1.1rem] flex-shrink-0"></i>
                        <span>Alamat Anda belum diisi. <a href="{{ route('profile.user') }}" class="text-maroon font-bold underline">Lengkapi profil sekarang</a> agar pesanan bisa diproses.</span>
                    </div>
                    @endif
                </div>

                {{-- 2. CATATAN --}}
                <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 mb-5 shadow-[0_2px_12px_rgba(139,26,26,0.04)]">
                    <div class="font-extrabold text-[#1a1a1a] text-base mb-[22px] pb-[14px] border-b border-[#f5f0f0] flex items-center gap-[10px]">
                        <i class="fas fa-comment-alt text-maroon w-5 text-center"></i>
                        Pesan untuk Penjual
                        <span class="ml-auto text-[0.72rem] font-bold bg-[#f0f0f0] text-[#aaa] px-[10px] py-[3px] rounded-[20px]">Opsional</span>
                    </div>
                    <textarea name="catatan" placeholder="Contoh: Tolong jangan pedas, atau waktu pengiriman siang hari saja..."
                        class="w-full px-4 py-3 border-[1.5px] border-[#f0f0f0] rounded-xl text-[0.92rem] font-sans text-[#333] bg-[#fafafa] transition-all duration-200 resize-none h-[90px] focus:outline-none focus:border-maroon focus:bg-white focus:shadow-[0_0_0_3px_rgba(139,26,26,0.07)]">{{ old('catatan') }}</textarea>
                </div>

                {{-- 3. PENGIRIMAN --}}
                <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 mb-5 shadow-[0_2px_12px_rgba(139,26,26,0.04)]">
                    <div class="font-extrabold text-[#1a1a1a] text-base mb-[22px] pb-[14px] border-b border-[#f5f0f0] flex items-center gap-[10px]">
                        <i class="fas fa-truck text-maroon w-5 text-center"></i> Metode Pengiriman
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-[14px]">
                        <label class="delivery-option relative border-2 border-maroon-200 rounded-[16px] p-5 cursor-pointer transition-all duration-[250ms] overflow-hidden {{ !$bisaDelivery ? 'opacity-45 cursor-not-allowed bg-[#f9f9f9]' : 'hover:border-[#d4a0a0] hover:bg-[#fdf8f8]' }}" id="label-delivery">
                            <div class="delivery-top-bar absolute top-0 left-0 right-0 h-[3px] bg-maroon rounded-t-[16px] scale-x-0 origin-left transition-transform duration-200"></div>
                            <input type="radio" name="metode_pengiriman" value="delivery" id="radio-delivery" class="hidden" {{ $bisaDelivery ? 'checked' : 'disabled' }}>
                            <div class="flex items-start justify-between mb-[14px]">
                                <div class="delivery-icon-box w-[46px] h-[46px] rounded-xl bg-maroon-100 text-maroon flex items-center justify-center text-[1.2rem] transition-all duration-200"><i class="fas fa-motorcycle"></i></div>
                                <div class="delivery-check-dot w-[22px] h-[22px] rounded-full border-2 border-[#ddd] flex items-center justify-center transition-all duration-200"><span class="hidden text-white text-[0.7rem] font-extrabold leading-none">✓</span></div>
                            </div>
                            <h4 class="font-extrabold text-[0.92rem] text-[#1a1a1a] mb-1">Delivery</h4>
                            <p class="text-[0.78rem] text-[#aaa] leading-[1.5] mb-2.5">Diantar ke alamat Anda (maks. 5 km dari outlet)</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-[20px] text-[0.8rem] font-extrabold {{ !$bisaDelivery ? 'bg-[#eee] text-[#bbb]' : 'bg-pink-100 text-red-700' }}">+ Rp{{ number_format($ongkir, 0, ',', '.') }}</span>
                        </label>

                        <label class="delivery-option relative border-2 border-maroon-200 rounded-[16px] p-5 cursor-pointer transition-all duration-[250ms] overflow-hidden hover:border-[#d4a0a0] hover:bg-[#fdf8f8]" id="label-pickup">
                            <div class="delivery-top-bar absolute top-0 left-0 right-0 h-[3px] bg-maroon rounded-t-[16px] scale-x-0 origin-left transition-transform duration-200"></div>
                            <input type="radio" name="metode_pengiriman" value="pickup" id="radio-pickup" class="hidden" {{ !$bisaDelivery ? 'checked' : '' }}>
                            <div class="flex items-start justify-between mb-[14px]">
                                <div class="delivery-icon-box w-[46px] h-[46px] rounded-xl bg-maroon-100 text-maroon flex items-center justify-center text-[1.2rem] transition-all duration-200"><i class="fas fa-store"></i></div>
                                <div class="delivery-check-dot w-[22px] h-[22px] rounded-full border-2 border-[#ddd] flex items-center justify-center transition-all duration-200"><span class="hidden text-white text-[0.7rem] font-extrabold leading-none">✓</span></div>
                            </div>
                            <h4 class="font-extrabold text-[0.92rem] text-[#1a1a1a] mb-1">Ambil Sendiri</h4>
                            <p class="text-[0.78rem] text-[#aaa] leading-[1.5] mb-2.5">Ambil langsung di outlet Ummilaa Kitchen</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-[20px] text-[0.8rem] font-extrabold bg-green-100 text-green-700">Gratis</span>
                        </label>
                    </div>

                    @if($jarakKm !== null)
                        @if($bisaDelivery)
                        <div data-permanent class="mt-4 px-4 py-3 rounded-xl text-[0.83rem] flex items-center gap-[10px] bg-green-100 text-green-800">
                            <i class="fas fa-check-circle"></i>
                            Jarak Anda ke outlet: <strong>&nbsp;{{ number_format($jarakKm, 1) }} km</strong>&nbsp;— Delivery tersedia ✓
                        </div>
                        @else
                        <div class="mt-4 px-4 py-3 rounded-xl text-[0.83rem] flex items-center gap-[10px] bg-pink-100 text-red-700">
                            <i class="fas fa-exclamation-circle"></i>
                            Jarak Anda ke outlet: <strong>&nbsp;{{ number_format($jarakKm, 1) }} km</strong>&nbsp;— Melebihi 5 km, hanya bisa Ambil Sendiri.
                        </div>
                        @endif
                    @else
                        <div class="mt-4 px-4 py-3 rounded-xl text-[0.83rem] flex items-center gap-[10px] bg-amber-50 text-orange-700">
                            <i class="fas fa-map-marker-alt"></i>
                            Lokasi GPS belum diatur di profil. <a href="{{ route('profile.user') }}" class="text-maroon font-bold ml-1">Update profil</a> untuk mengaktifkan Delivery.
                        </div>
                    @endif
                </div>

                {{-- 4. PEMBAYARAN --}}
                <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 mb-5 shadow-[0_2px_12px_rgba(139,26,26,0.04)] transition-all duration-200" id="payment-card">
                    <div class="font-extrabold text-[#1a1a1a] text-base mb-[22px] pb-[14px] border-b border-[#f5f0f0] flex items-center gap-[10px]">
                        <i class="fas fa-credit-card text-maroon w-5 text-center"></i> Metode Pembayaran
                    </div>
                    <div class="flex flex-col gap-[10px]">
                        @foreach([['BNI Virtual Account','Transfer via BNI','bg-orange-500','fa-university'],['BRI Virtual Account','Transfer via BRI','bg-blue-900','fa-university'],['BCA Virtual Account','Transfer via BCA','bg-blue-700','fa-university'],['COD','Bayar saat produk tiba / diambil','bg-green-700','fa-money-bill-wave']] as [$val,$desc,$color,$icon])
                        <label class="payment-option flex items-center gap-[14px] px-[18px] py-[14px] rounded-[14px] border-[1.5px] border-maroon-200 cursor-pointer transition-all duration-200 hover:border-[#d4a0a0] hover:bg-[#fdf8f8]">
                            <input type="radio" name="metode_pembayaran" value="{{ $val }}" class="hidden">
                            <div class="w-[42px] h-[42px] rounded-[11px] {{ $color }} flex items-center justify-center text-base text-white flex-shrink-0">
                                <i class="fas {{ $icon }}"></i>
                            </div>
                            <div class="flex-1">
                                <strong class="block text-[0.9rem] text-[#1a1a1a] font-bold">{{ $val }}</strong>
                                <span class="text-[0.78rem] text-[#aaa]">{{ $desc }}</span>
                            </div>
                            <div class="payment-dot w-5 h-5 rounded-full border-2 border-[#ddd] flex-shrink-0 flex items-center justify-center transition-all duration-200"><span class="hidden w-[7px] h-[7px] bg-white rounded-full"></span></div>
                        </label>
                        @endforeach
                    </div>
                    <div class="hidden items-center gap-[10px] bg-pink-100 text-red-700 border-[1.5px] border-red-300 px-4 py-3 rounded-xl text-[0.85rem] font-bold mt-[14px] animate-shake" id="payment-error-msg">
                        <i class="fas fa-exclamation-circle text-[1.1rem] flex-shrink-0"></i>
                        Silakan pilih metode pembayaran terlebih dahulu!
                    </div>
                </div>

            </div>

            {{-- KANAN: SUMMARY --}}
            <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden shadow-[0_4px_24px_rgba(139,26,26,0.07)] lg:sticky lg:top-[90px]">
                <div class="px-7 py-[22px] bg-[linear-gradient(135deg,#8B1A1A,#6B1414)] text-white">
                    <h3 class="font-playfair text-[1.1rem] font-bold">Ringkasan Pesanan</h3>
                    <p class="text-[0.78rem] opacity-75 mt-0.5">{{ $carts->count() }} produk</p>
                </div>
                <div class="px-7 py-6">
                    @foreach($carts as $cart)
                    <div class="flex items-center gap-3 mb-[14px]">
                        <img class="w-[50px] h-[50px] object-cover rounded-[10px] flex-shrink-0"
                            src="{{ $cart->product->image && Str::startsWith($cart->product->image, 'products/') ? asset('storage/' . $cart->product->image) : $cart->product->image }}"
                            alt="{{ $cart->product->name }}">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-[0.85rem] text-[#1a1a1a] truncate">{{ $cart->product->name }}</h4>
                            <p class="text-[0.78rem] text-[#aaa] mt-0.5">{{ $cart->quantity }}x · Rp{{ number_format($cart->product->price, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-extrabold text-maroon text-[0.85rem] flex-shrink-0">Rp{{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</span>
                    </div>
                    @endforeach

                    <div class="h-px bg-[#f5f0f0] my-4"></div>
                    <div class="flex justify-between text-[0.88rem] mb-[10px]">
                        <span class="text-[#888]">Subtotal</span>
                        <strong class="text-[#1a1a1a] font-bold">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex justify-between text-[0.88rem] mb-[10px]">
                        <span class="text-[#888]">Ongkos Kirim</span>
                        <strong class="text-[#1a1a1a] font-bold" id="ongkir-display">{{ $bisaDelivery ? 'Rp' . number_format($ongkir, 0, ',', '.') : 'Gratis' }}</strong>
                    </div>
                    <div class="flex justify-between items-center mt-4 mb-[22px] px-[18px] py-[14px] bg-maroon-50 rounded-xl">
                        <span class="font-extrabold text-[#1a1a1a] text-[0.92rem]">Total Pembayaran</span>
                        <strong class="font-extrabold text-maroon text-[1.25rem]" id="total-display">Rp{{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    <button type="submit" id="btn-place-order"
                        class="w-full py-[15px] bg-maroon text-white border-none rounded-xl text-[0.98rem] font-extrabold font-sans cursor-pointer transition-all duration-200 flex items-center justify-center gap-[10px] tracking-[0.3px] hover:bg-maroon-dark hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(139,26,26,0.3)]">
                        <i class="fas fa-lock"></i> Konfirmasi Pesanan
                    </button>
                    <div class="text-center text-[0.75rem] text-[#bbb] mt-3 flex items-center justify-center gap-1.5">
                        <i class="fas fa-shield-alt"></i> Transaksi Anda aman & terenkripsi
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};
    const bisaDelivery = {{ $bisaDelivery ? 'true' : 'false' }};
    const ongkirDelivery = {{ $ongkir }};

    const radioDelivery = document.getElementById('radio-delivery');
    const radioPickup   = document.getElementById('radio-pickup');
    const labelDelivery = document.getElementById('label-delivery');
    const labelPickup   = document.getElementById('label-pickup');
    const ongkirDisplay = document.getElementById('ongkir-display');
    const totalDisplay  = document.getElementById('total-display');

    function formatRp(n) {
        return 'Rp' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function setDeliverySelected(label, isSelected) {
        label.classList.toggle('border-[#8B1A1A]', isSelected);
        label.classList.toggle('bg-[#fdf5f5]', isSelected);
        label.classList.toggle('border-maroon-200', !isSelected);

        const topBar = label.querySelector('.delivery-top-bar');
        if (topBar) {
            topBar.classList.toggle('scale-x-100', isSelected);
            topBar.classList.toggle('scale-x-0', !isSelected);
        }

        const iconBox = label.querySelector('.delivery-icon-box');
        if (iconBox) {
            iconBox.classList.toggle('bg-[#8B1A1A]', isSelected);
            iconBox.classList.toggle('text-white', isSelected);
            iconBox.classList.toggle('bg-maroon-100', !isSelected);
            iconBox.classList.toggle('text-maroon', !isSelected);
        }

        const dot = label.querySelector('.delivery-check-dot');
        if (dot) {
            dot.classList.toggle('border-[#8B1A1A]', isSelected);
            dot.classList.toggle('bg-[#8B1A1A]', isSelected);
            dot.classList.toggle('border-[#ddd]', !isSelected);
            const span = dot.querySelector('span');
            if (span) span.classList.toggle('hidden', !isSelected);
        }
    }

    function updateSummary() {
        const isDelivery = radioDelivery && !radioDelivery.disabled && radioDelivery.checked;
        const ongkir = isDelivery ? ongkirDelivery : 0;
        ongkirDisplay.textContent = ongkir > 0 ? formatRp(ongkir) : 'Gratis';
        totalDisplay.textContent  = formatRp(subtotal + ongkir);
        if (labelDelivery) setDeliverySelected(labelDelivery, isDelivery);
        if (labelPickup)   setDeliverySelected(labelPickup, !isDelivery);
    }

    updateSummary();
    if (radioDelivery && !radioDelivery.disabled) radioDelivery.addEventListener('change', updateSummary);
    if (radioPickup) radioPickup.addEventListener('change', updateSummary);

    document.querySelectorAll('.payment-option input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-option').forEach(el => {
                el.classList.remove('border-[#8B1A1A]', 'bg-[#fdf5f5]');
                el.classList.add('border-maroon-200');
                const dot = el.querySelector('.payment-dot');
                if (dot) {
                    dot.classList.remove('border-[#8B1A1A]', 'bg-[#8B1A1A]');
                    dot.classList.add('border-[#ddd]');
                    const span = dot.querySelector('span');
                    if (span) span.classList.add('hidden');
                }
            });
            const selected = this.closest('.payment-option');
            selected.classList.add('border-[#8B1A1A]', 'bg-[#fdf5f5]');
            selected.classList.remove('border-maroon-200');
            const dot = selected.querySelector('.payment-dot');
            if (dot) {
                dot.classList.add('border-[#8B1A1A]', 'bg-[#8B1A1A]');
                dot.classList.remove('border-[#ddd]');
                const span = dot.querySelector('span');
                if (span) span.classList.remove('hidden');
            }
            const errorMsg = document.getElementById('payment-error-msg');
            const payCard  = document.getElementById('payment-card');
            errorMsg.classList.add('hidden');
            errorMsg.classList.remove('flex');
            payCard.classList.remove('border-[#ef9a9a]', 'ring-2', 'ring-[rgba(198,40,40,0.1)]');
        });
    });

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const pembayaranDipilih = document.querySelector('input[name="metode_pembayaran"]:checked');
        if (!pembayaranDipilih) {
            e.preventDefault();
            const errorMsg = document.getElementById('payment-error-msg');
            const payCard  = document.getElementById('payment-card');
            errorMsg.classList.remove('hidden');
            errorMsg.classList.add('flex');
            payCard.classList.add('border-[#ef9a9a]', 'ring-2', 'ring-[rgba(198,40,40,0.1)]');
            payCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endpush
