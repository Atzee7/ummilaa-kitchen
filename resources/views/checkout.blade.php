@extends('layouts.app')


@section('content')

<div class="px-4 md:px-10 lg:px-[80px] py-8 lg:py-[60px] bg-[#faf8f8] min-h-screen">

    <div class="mb-10">
        <h1 class="font-playfair text-[2.2rem] text-[#1a1a1a]">Checkout</h1>
        <p class="text-[#999] mt-1.5 text-[0.9rem]">Periksa pesanan Anda dan selesaikan pembayaran</p>
    </div>

    {{-- STEP INDICATOR --}}
    <div class="flex items-center gap-0 mb-10 overflow-x-auto">
        <div class="flex items-center gap-[10px] text-[0.82rem] font-bold text-green-700 whitespace-nowrap">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[0.78rem] font-extrabold bg-green-700 text-white flex-shrink-0">
                <i class="fas fa-check text-[0.65rem]"></i>
            </div>
            <span class="hidden sm:inline">Keranjang</span>
        </div>
        <div class="w-6 sm:w-10 h-0.5 bg-green-700 mx-1 sm:mx-2 flex-shrink-0"></div>
        <div class="flex items-center gap-[10px] text-[0.82rem] font-bold text-maroon whitespace-nowrap">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[0.78rem] font-extrabold bg-maroon text-white flex-shrink-0">2</div>
            <span class="hidden sm:inline">Checkout</span>
        </div>
        <div class="w-6 sm:w-10 h-0.5 bg-[#eee] mx-1 sm:mx-2 flex-shrink-0"></div>
        <div class="flex items-center gap-[10px] text-[0.82rem] font-bold text-[#bbb] whitespace-nowrap">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[0.78rem] font-extrabold bg-[#eee] text-[#bbb] flex-shrink-0">3</div>
            <span class="hidden sm:inline">Selesai</span>
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

                    {{-- Tarif ongkir info --}}
                    <div class="mt-4 border-[1.5px] border-maroon-200 rounded-xl overflow-hidden">
                        <div class="px-4 py-[10px] bg-maroon-50 flex items-center gap-2 text-[0.78rem] font-extrabold text-maroon border-b border-maroon-200">
                            <i class="fas fa-info-circle"></i> Tarif Ongkos Kirim
                        </div>
                        <div class="grid grid-cols-2 divide-x divide-maroon-200">
                            <div class="px-4 py-[9px] flex items-center justify-between text-[0.8rem]">
                                <span class="text-[#666]">1 – 2 km</span>
                                <span class="font-extrabold text-[#1a1a1a]">Rp 5.000</span>
                            </div>
                            <div class="px-4 py-[9px] flex items-center justify-between text-[0.8rem]">
                                <span class="text-[#666]">3 – 4 km</span>
                                <span class="font-extrabold text-[#1a1a1a]">Rp 8.000</span>
                            </div>
                            <div class="px-4 py-[9px] flex items-center justify-between text-[0.8rem] border-t border-maroon-200">
                                <span class="text-[#666]">5 km</span>
                                <span class="font-extrabold text-[#1a1a1a]">Rp 10.000</span>
                            </div>
                            <div class="px-4 py-[9px] flex items-center justify-between text-[0.8rem] border-t border-maroon-200">
                                <span class="text-[#666]">> 5 km</span>
                                <span class="font-extrabold text-red-600">Tidak tersedia</span>
                            </div>
                        </div>
                        <div class="px-4 py-[8px] bg-[#fdf8f8] border-t border-maroon-200 text-[0.73rem] text-[#aaa]">
                            <i class="fas fa-circle-info text-[0.65rem]"></i> Jarak dibulatkan ke atas ke km terdekat dari lokasi GPS Anda
                        </div>
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

                    {{-- PESAN UNTUK NANTI --}}
                    <div class="mt-4 border-[1.5px] border-maroon-200 rounded-xl overflow-hidden">
                        <div id="btn-pesan-nanti"
                            class="w-full flex items-center justify-between px-4 py-[13px] text-[0.88rem] font-bold text-[#444] hover:bg-[#fdf8f8] transition-all cursor-pointer select-none">
                            <span class="flex items-center gap-[10px]">
                                <i class="fas fa-calendar-alt text-maroon w-5 text-center"></i>
                                <span>Pesan untuk nanti</span>
                            </span>
                            <span class="flex items-center gap-2">
                                <span id="label-jadwal-selected" class="hidden text-maroon text-[0.78rem] font-extrabold"></span>
                                <span id="btn-clear-jadwal" class="hidden text-[#bbb] hover:text-red-500 transition-colors cursor-pointer p-1">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                                <i class="fas fa-chevron-right text-[0.75rem] text-[#bbb]" id="icon-pesan-nanti"></i>
                            </span>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_pengiriman" id="input-tanggal">
                    <input type="hidden" name="waktu_pengiriman" id="input-waktu">
                </div>

                {{-- 4. PEMBAYARAN --}}
                <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 mb-5 shadow-[0_2px_12px_rgba(139,26,26,0.04)] transition-all duration-200" id="payment-card">
                    <div class="font-extrabold text-[#1a1a1a] text-base mb-[22px] pb-[14px] border-b border-[#f5f0f0] flex items-center gap-[10px]">
                        <i class="fas fa-credit-card text-maroon w-5 text-center"></i> Metode Pembayaran
                    </div>
                    <div class="flex flex-col gap-[10px]">
                        @foreach([['Bayar Online','Kartu kredit, GoPay, OVO, ShopeePay, QRIS, VA semua bank — via Midtrans','bg-blue-600','fa-credit-card']] as [$val,$desc,$color,$icon])
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

{{-- MODAL: PESAN UNTUK NANTI --}}
<div id="jadwal-overlay" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
    <div class="bg-white rounded-[20px] shadow-2xl w-full max-w-sm overflow-hidden">

        {{-- VIEW UTAMA --}}
        <div id="jn-main">
            <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-[#f5f0f0]">
                <h3 class="font-playfair text-[1.1rem] font-bold text-[#1a1a1a]">Pesan untuk Nanti</h3>
                <button type="button" onclick="closePesanNanti()"
                    class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-[#999]">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Baris Hari --}}
            <div onclick="showJnHari()" class="flex items-center justify-between px-5 py-4 border-b border-[#f5f0f0] cursor-pointer hover:bg-[#fdf8f8] transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#fdf0f0] flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar text-maroon text-[0.85rem]"></i>
                    </div>
                    <div>
                        <p class="text-[0.7rem] text-[#aaa] font-semibold uppercase tracking-wide leading-none mb-1">Hari</p>
                        <p id="jn-date-label" class="text-[0.9rem] font-bold text-[#444] leading-tight">Pilih hari pengiriman</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-[0.75rem] text-[#ccc] group-hover:text-maroon transition-colors flex-shrink-0"></i>
            </div>

            {{-- Baris Jam --}}
            <div onclick="showJnJam()" class="flex items-center justify-between px-5 py-4 border-b border-[#f5f0f0] cursor-pointer hover:bg-[#fdf8f8] transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#fdf0f0] flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clock text-maroon text-[0.85rem]"></i>
                    </div>
                    <div>
                        <p class="text-[0.7rem] text-[#aaa] font-semibold uppercase tracking-wide leading-none mb-1">Jam</p>
                        <p id="jn-time-label" class="text-[0.9rem] font-bold text-[#444] leading-tight">Pilih jam pengiriman</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-[0.75rem] text-[#ccc] group-hover:text-maroon transition-colors flex-shrink-0"></i>
            </div>

            {{-- Footer --}}
            <div class="px-5 py-4 flex flex-col gap-2">
                <button type="button" id="btn-confirm-jadwal" disabled onclick="confirmJadwal()"
                    class="w-full py-[13px] bg-maroon text-white rounded-xl font-bold text-[0.9rem] disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                    Konfirmasi Jadwal
                </button>
                <button type="button" onclick="pesanSekarang()"
                    class="w-full py-3 text-[0.85rem] font-bold text-[#888] hover:text-maroon transition-all">
                    Pesan Sekarang (tanpa jadwal)
                </button>
            </div>
        </div>

        {{-- VIEW PILIH HARI --}}
        <div id="jn-hari" class="hidden">
            <div class="flex items-center gap-3 px-5 pt-5 pb-4 border-b border-[#f5f0f0]">
                <button type="button" onclick="showJnMain()"
                    class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-[#999]">
                    <i class="fas fa-arrow-left text-sm"></i>
                </button>
                <h3 class="font-playfair text-[1.05rem] font-bold text-[#1a1a1a]">Pilih Hari</h3>
            </div>
            <div id="jn-hari-list" class="px-3 py-2"></div>
        </div>

        {{-- VIEW PILIH JAM --}}
        <div id="jn-jam" class="hidden">
            <div class="flex items-center gap-3 px-5 pt-5 pb-4 border-b border-[#f5f0f0]">
                <button type="button" onclick="showJnMain()"
                    class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-[#999]">
                    <i class="fas fa-arrow-left text-sm"></i>
                </button>
                <h3 class="font-playfair text-[1.05rem] font-bold text-[#1a1a1a]">Pilih Jam</h3>
            </div>
            <div class="px-5 pt-3 pb-2">
                <p class="text-[0.7rem] font-bold text-[#aaa] uppercase tracking-wide mb-2">Jam</p>
                <div id="jn-hour-grid" class="grid grid-cols-4 gap-2"></div>
            </div>
            <div class="px-5 pb-3">
                <p class="text-[0.7rem] font-bold text-[#aaa] uppercase tracking-wide mb-2">Menit</p>
                <div class="flex gap-2">
                    <button type="button" id="min-00" onclick="selectMin(0)"
                        class="flex-1 py-[10px] rounded-xl border-[1.5px] text-[0.88rem] font-bold transition-all border-maroon-200 text-[#555] hover:border-maroon hover:text-maroon">
                        : 00
                    </button>
                    <button type="button" id="min-30" onclick="selectMin(30)"
                        class="flex-1 py-[10px] rounded-xl border-[1.5px] text-[0.88rem] font-bold transition-all border-maroon-200 text-[#555] hover:border-maroon hover:text-maroon">
                        : 30
                    </button>
                </div>
            </div>
            <div class="px-5 pb-5 pt-2 border-t border-[#f0eaea]">
                <button type="button" id="btn-jam-oke" disabled onclick="showJnMain()"
                    class="w-full py-[13px] bg-maroon text-white rounded-xl font-bold text-[0.9rem] disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                    OKE
                </button>
            </div>
        </div>

    </div>
</div>

{{-- MODAL KONFIRMASI PESANAN --}}
<div id="checkout-confirm-modal"
     class="fixed inset-0 z-50 flex items-center justify-center hidden"
     style="background:rgba(0,0,0,0.5);backdrop-filter:blur(3px);">
    <div class="bg-white rounded-[24px] shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        {{-- HEADER --}}
        <div class="px-7 py-5 bg-[linear-gradient(135deg,#8B1A1A,#6B1414)] text-white">
            <h3 class="font-playfair text-[1.2rem] font-bold">Konfirmasi Pesanan</h3>
            <p class="text-[0.78rem] opacity-75 mt-0.5">Pastikan detail pesanan sudah benar</p>
        </div>
        {{-- BODY --}}
        <div class="px-7 py-5 max-h-[55vh] overflow-y-auto">
            <div id="modal-items" class="mb-4 space-y-3"></div>
            <div class="h-px bg-[#f0eaea] mb-4"></div>
            <div class="space-y-2 text-[0.88rem] text-[#555]">
                <div class="flex justify-between"><span>Penerima</span><strong class="text-[#1a1a1a]" id="modal-nama"></strong></div>
                <div class="flex justify-between"><span>Telepon</span><strong class="text-[#1a1a1a]" id="modal-telepon"></strong></div>
                <div>
                    <p class="text-[#888] mb-1">Alamat</p>
                    <p class="text-[#1a1a1a] font-semibold leading-[1.6]" id="modal-alamat"></p>
                </div>
                <div id="modal-detail-alamat-wrap" class="hidden">
                    <p class="text-[#888] mb-1">Detail Alamat</p>
                    <p class="text-[#1a1a1a] font-semibold leading-[1.6]" id="modal-detail-alamat"></p>
                </div>
                <div class="flex justify-between"><span>Pengiriman</span><strong class="text-[#1a1a1a]" id="modal-pengiriman"></strong></div>
                <div class="flex justify-between"><span>Jadwal</span><strong class="text-[#1a1a1a]" id="modal-jadwal"></strong></div>
                <div class="flex justify-between"><span>Pembayaran</span><strong class="text-[#1a1a1a]" id="modal-pembayaran"></strong></div>
                <div id="modal-catatan-wrap" class="hidden">
                    <p class="text-[#888] mb-1">Catatan</p>
                    <p class="text-[#1a1a1a] italic leading-[1.6]" id="modal-catatan"></p>
                </div>
                <div class="h-px bg-[#f0eaea] my-2"></div>
                <div class="flex justify-between"><span class="text-[#888]">Subtotal</span><strong id="modal-subtotal"></strong></div>
                <div class="flex justify-between"><span class="text-[#888]">Ongkos Kirim</span><strong id="modal-ongkir"></strong></div>
                <div class="flex justify-between items-center pt-2 border-t border-[#f0eaea]">
                    <span class="font-extrabold text-[#1a1a1a]">Total</span>
                    <span class="font-extrabold text-maroon text-[1.15rem]" id="modal-total"></span>
                </div>
            </div>
        </div>
        {{-- FOOTER --}}
        <div class="px-7 py-5 border-t border-[#f0eaea]">
            <div class="flex gap-3">
                <button type="button" id="btn-modal-batal"
                    class="flex-1 py-[13px] rounded-xl font-bold text-[0.9rem] border-[1.5px] border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
                    <i class="fas fa-times mr-1"></i> Batalkan
                </button>
                <button type="button" id="btn-modal-confirm"
                    class="flex-1 py-[13px] rounded-xl font-bold text-[0.9rem] bg-maroon text-white hover:bg-maroon-dark transition-all">
                    <i class="fas fa-check mr-1"></i> Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@php
    $checkoutItems = $carts->map(fn($c) => [
        'name'  => $c->product->name,
        'qty'   => $c->quantity,
        'price' => $c->product->price,
    ]);
@endphp
<script>
    const checkoutData = {
        items        : @json($checkoutItems),
        nama         : @json(Auth::user()->name),
        telepon      : @json(Auth::user()->no_telepon ?? '-'),
        alamat       : @json(Auth::user()->alamat ?? '-'),
        detailAlamat : @json(Auth::user()->detail_alamat),
        subtotal     : {{ $subtotal }},
        ongkir       : {{ $ongkir }},
    };

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

    // Intercept tombol Konfirmasi Pesanan untuk menampilkan modal
    (function () {
        function fmt(n) {
            return 'Rp' + Number(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function openModal() {
            const delivery = document.getElementById('radio-delivery')?.checked ?? true;
            const bayar    = document.querySelector('input[name="metode_pembayaran"]:checked')?.value ?? '-';
            const ongkir   = delivery ? checkoutData.ongkir : 0;
            const total    = checkoutData.subtotal + ongkir;

            document.getElementById('modal-items').innerHTML = checkoutData.items.map(item => `
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-[0.88rem] text-[#1a1a1a]">${item.name}</p>
                        <p class="text-[0.78rem] text-[#aaa]">${item.qty}x &middot; ${fmt(item.price)}</p>
                    </div>
                    <span class="font-bold text-maroon text-[0.88rem]">${fmt(item.price * item.qty)}</span>
                </div>`).join('');

            document.getElementById('modal-nama').textContent       = checkoutData.nama;
            document.getElementById('modal-telepon').textContent    = checkoutData.telepon;
            document.getElementById('modal-alamat').textContent     = checkoutData.alamat;

            const detailWrap = document.getElementById('modal-detail-alamat-wrap');
            if (checkoutData.detailAlamat) {
                document.getElementById('modal-detail-alamat').textContent = checkoutData.detailAlamat;
                detailWrap.classList.remove('hidden');
            } else {
                detailWrap.classList.add('hidden');
            }

            document.getElementById('modal-pengiriman').textContent = delivery ? 'Delivery' : 'Ambil Sendiri';

            const jadwalText = selectedDate && selectedSlot
                ? parseLocalDate(selectedDate).toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'long' }) + ' · ' + selectedSlot
                : 'Langsung';
            document.getElementById('modal-jadwal').textContent = jadwalText;

            document.getElementById('modal-pembayaran').textContent = bayar;

            const catatanVal  = document.querySelector('textarea[name="catatan"]')?.value.trim() ?? '';
            const catatanWrap = document.getElementById('modal-catatan-wrap');
            if (catatanVal) {
                document.getElementById('modal-catatan').textContent = catatanVal;
                catatanWrap.classList.remove('hidden');
            } else {
                catatanWrap.classList.add('hidden');
            }

            document.getElementById('modal-subtotal').textContent   = fmt(checkoutData.subtotal);
            document.getElementById('modal-ongkir').textContent     = ongkir > 0 ? fmt(ongkir) : 'Gratis';
            document.getElementById('modal-total').textContent      = fmt(total);

            document.getElementById('checkout-confirm-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('checkout-confirm-modal').classList.add('hidden');
        }

        document.getElementById('btn-place-order').addEventListener('click', function (e) {
            e.preventDefault();
            const hasPembayaran = document.querySelector('input[name="metode_pembayaran"]:checked');
            if (!hasPembayaran) {
                const errorMsg = document.getElementById('payment-error-msg');
                const payCard  = document.getElementById('payment-card');
                errorMsg.classList.remove('hidden');
                errorMsg.classList.add('flex');
                payCard.classList.add('border-[#ef9a9a]', 'ring-2', 'ring-[rgba(198,40,40,0.1)]');
                payCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            openModal();
        });

        document.getElementById('btn-modal-batal').addEventListener('click', closeModal);
        document.getElementById('btn-modal-confirm').addEventListener('click', function () {
            document.getElementById('checkout-form').submit();
        });
    })();

    // ====== PESAN UNTUK NANTI ======
    const JADWAL_TOKO = {
        0: { buka: 12, tutup: 19 }, // Minggu
        1: null,                     // Senin — Tutup
        2: { buka: 7,  tutup: 19 }, // Selasa
        3: { buka: 12, tutup: 19 }, // Rabu
        4: { buka: 12, tutup: 19 }, // Kamis
        5: { buka: 12, tutup: 19 }, // Jumat
        6: { buka: 12, tutup: 19 }, // Sabtu
    };

    function getJadwalHari(dateIso) {
        if (!dateIso) return null;
        const d = parseLocalDate(dateIso);
        return JADWAL_TOKO[d.getDay()] ?? null;
    }

    // Gunakan tanggal lokal (bukan UTC) agar cocok dengan server timezone (WIB)
    function localDateIso(d) {
        const pad = n => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    }

    function parseLocalDate(iso) {
        const [y, m, d] = iso.split('-').map(Number);
        return new Date(y, m - 1, d);
    }

    let selectedDate = null;
    let selectedSlot = null;
    let tempDate     = null;
    let tempHour     = null;
    let tempMin      = null;

    document.getElementById('btn-pesan-nanti').addEventListener('click', openPesanNanti);
    document.getElementById('btn-clear-jadwal').addEventListener('click', clearJadwal);

    function openPesanNanti() {
        tempDate = selectedDate;
        tempHour = selectedSlot ? parseInt(selectedSlot.slice(0, 2)) : null;
        tempMin  = selectedSlot ? parseInt(selectedSlot.slice(3, 5)) : null;
        showJnMain();
        document.getElementById('jadwal-overlay').classList.remove('hidden');
        document.getElementById('jadwal-overlay').classList.add('flex');
    }

    function closePesanNanti() {
        document.getElementById('jadwal-overlay').classList.add('hidden');
        document.getElementById('jadwal-overlay').classList.remove('flex');
    }

    // ---- View switching ----
    function showJnMain() {
        updateMainLabels();
        updateConfirmBtn();
        document.getElementById('jn-hari').classList.add('hidden');
        document.getElementById('jn-jam').classList.add('hidden');
        document.getElementById('jn-main').classList.remove('hidden');
    }

    function showJnHari() {
        document.getElementById('jn-main').classList.add('hidden');
        document.getElementById('jn-hari').classList.remove('hidden');
        buildHariList();
    }

    function showJnJam() {
        document.getElementById('jn-main').classList.add('hidden');
        document.getElementById('jn-jam').classList.remove('hidden');
        buildHourGrid();
        renderMinButtons();
        updateOkeBtn();
    }

    // ---- Update labels on main view ----
    function updateMainLabels() {
        const todayIso  = localDateIso(new Date());
        const dateLabel = document.getElementById('jn-date-label');
        if (tempDate) {
            const d   = parseLocalDate(tempDate);
            const lbl = tempDate === todayIso
                ? 'Hari Ini, ' + d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long' })
                : d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            dateLabel.textContent = lbl;
            dateLabel.style.color = 'var(--color-maroon, #8B1A1A)';
        } else {
            dateLabel.textContent = 'Pilih hari pengiriman';
            dateLabel.style.color = '#444';
        }
        const timeLabel = document.getElementById('jn-time-label');
        if (tempHour !== null && tempMin !== null) {
            const endH = tempMin === 30 ? tempHour + 1 : tempHour;
            const endM = tempMin === 30 ? 0 : 30;
            timeLabel.textContent = `${String(tempHour).padStart(2,'0')}:${String(tempMin).padStart(2,'0')} – ${String(endH).padStart(2,'0')}:${String(endM).padStart(2,'0')}`;
            timeLabel.style.color = 'var(--color-maroon, #8B1A1A)';
        } else {
            timeLabel.textContent = 'Pilih jam pengiriman';
            timeLabel.style.color = '#444';
        }
    }

    // ---- Build hari list (4 baris, tanpa scroll) ----
    function buildHariList() {
        const container = document.getElementById('jn-hari-list');
        container.innerHTML = '';
        const today = new Date();
        for (let i = 0; i < 4; i++) {
            const d      = new Date(today);
            d.setDate(today.getDate() + i);
            const iso    = localDateIso(d);
            const jadwal = getJadwalHari(iso);
            const tutup  = !jadwal;
            const nama   = i === 0 ? 'Hari Ini' : i === 1 ? 'Besok'
                : d.toLocaleDateString('id-ID', { weekday: 'long' });
            const tgl    = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const active = !tutup && tempDate === iso;
            const row    = document.createElement('div');
            row.className = 'flex items-center justify-between px-3 py-[13px] rounded-xl mx-1 transition-all '
                + (tutup ? 'opacity-60 cursor-not-allowed' : active ? 'bg-[#fdf0f0] cursor-pointer' : 'hover:bg-[#fdf8f8] cursor-pointer');
            row.innerHTML = `
                <div>
                    <p class="text-[0.9rem] font-bold" style="color:${tutup ? '#bbb' : active ? '#8B1A1A' : '#1a1a1a'}">${nama}</p>
                    <p class="text-[0.75rem] mt-0.5" style="color:${tutup ? '#ccc' : '#999'}">${tgl}</p>
                </div>
                ${tutup
                    ? '<span style="font-size:0.7rem;color:#e57373;font-weight:700;background:#fde8e8;padding:2px 8px;border-radius:9999px">Tutup</span>'
                    : active
                        ? '<i class="fas fa-check-circle" style="color:#8B1A1A;font-size:1rem"></i>'
                        : '<i class="fas fa-circle" style="color:#e8e0e0;font-size:0.55rem"></i>'
                }
            `;
            if (!tutup) {
                row.addEventListener('click', () => {
                    if (tempDate !== iso) {
                        if (tempHour !== null && !isHourAvailable(iso, tempHour)) {
                            tempHour = null;
                            tempMin  = null;
                        } else if (tempMin !== null && !isTimeAvailable(iso, tempHour, tempMin)) {
                            tempMin = null;
                        }
                    }
                    tempDate = iso;
                    showJnMain();
                });
            }
            container.appendChild(row);
        }
    }

    // ---- Hour grid ----
    function buildHourGrid() {
        const container = document.getElementById('jn-hour-grid');
        container.innerHTML = '';
        const jadwal = getJadwalHari(tempDate);
        const buka   = jadwal ? jadwal.buka  : 7;
        const tutup  = jadwal ? jadwal.tutup : 19;
        for (let h = buka; h < tutup; h++) {
            const available  = isHourAvailable(tempDate, h);
            const isSelected = tempHour === h;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = String(h).padStart(2, '0');
            if (!available) {
                btn.disabled = true;
                btn.className = 'py-[10px] rounded-xl border-[1.5px] border-[#eee] text-[0.88rem] text-[#ccc] cursor-not-allowed';
            } else {
                btn.className = 'py-[10px] rounded-xl border-[1.5px] text-[0.88rem] font-bold transition-all '
                    + (isSelected ? 'bg-maroon text-white border-maroon' : 'border-maroon-200 text-[#555] hover:border-maroon hover:text-maroon');
                btn.addEventListener('click', () => {
                    tempHour = h;
                    if (tempMin !== null && !isTimeAvailable(tempDate, h, tempMin)) {
                        tempMin = null;
                    }
                    buildHourGrid();
                    renderMinButtons();
                    updateOkeBtn();
                });
            }
            container.appendChild(btn);
        }
    }

    // ---- Minute buttons ----
    function renderMinButtons() {
        [0, 30].forEach(m => {
            const id  = m === 0 ? 'min-00' : 'min-30';
            const btn = document.getElementById(id);
            const available  = tempHour !== null && isTimeAvailable(tempDate, tempHour, m);
            const isSelected = tempMin === m;
            if (!available) {
                btn.disabled = true;
                btn.className = 'flex-1 py-[10px] rounded-xl border-[1.5px] border-[#eee] text-[0.88rem] text-[#ccc] cursor-not-allowed';
            } else {
                btn.disabled = false;
                btn.className = 'flex-1 py-[10px] rounded-xl border-[1.5px] text-[0.88rem] font-bold transition-all '
                    + (isSelected ? 'bg-maroon text-white border-maroon' : 'border-maroon-200 text-[#555] hover:border-maroon hover:text-maroon');
            }
        });
    }

    function selectMin(m) {
        if (tempHour === null) return;
        if (!isTimeAvailable(tempDate, tempHour, m)) return;
        tempMin = m;
        renderMinButtons();
        updateOkeBtn();
    }

    function updateOkeBtn() {
        document.getElementById('btn-jam-oke').disabled = !(tempHour !== null && tempMin !== null);
    }

    // ---- Helpers ----
    function updateConfirmBtn() {
        document.getElementById('btn-confirm-jadwal').disabled = !(tempDate && tempHour !== null && tempMin !== null);
    }

    function isHourAvailable(dateIso, h) {
        if (!dateIso) return false;
        const jadwal = getJadwalHari(dateIso);
        if (!jadwal) return false;
        if (h < jadwal.buka || h >= jadwal.tutup) return false;
        const todayIso = localDateIso(new Date());
        if (dateIso !== todayIso) return true;
        return isTimeAvailable(dateIso, h, 0) || isTimeAvailable(dateIso, h, 30);
    }

    function isTimeAvailable(dateIso, h, m) {
        if (!dateIso) return false;
        const jadwal = getJadwalHari(dateIso);
        if (!jadwal) return false;
        if (h < jadwal.buka || h >= jadwal.tutup) return false;
        const todayIso = localDateIso(new Date());
        if (dateIso !== todayIso) return true;
        const slotTime = new Date();
        slotTime.setHours(h, m, 0, 0);
        return slotTime >= new Date(Date.now() + 30 * 60 * 1000);
    }

    function confirmJadwal() {
        selectedDate = tempDate;
        const endH   = tempMin === 30 ? tempHour + 1 : tempHour;
        const endM   = tempMin === 30 ? 0 : 30;
        selectedSlot = `${String(tempHour).padStart(2,'0')}:${String(tempMin).padStart(2,'0')} - ${String(endH).padStart(2,'0')}:${String(endM).padStart(2,'0')}`;
        document.getElementById('input-tanggal').value = selectedDate;
        document.getElementById('input-waktu').value   = selectedSlot;
        const d        = parseLocalDate(selectedDate);
        const todayIso = localDateIso(new Date());
        const dayLabel = selectedDate === todayIso ? 'Hari Ini'
            : d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
        document.getElementById('label-jadwal-selected').textContent = `${dayLabel} · ${selectedSlot}`;
        document.getElementById('label-jadwal-selected').classList.remove('hidden');
        document.getElementById('btn-clear-jadwal').classList.remove('hidden');
        document.getElementById('icon-pesan-nanti').classList.add('hidden');
        closePesanNanti();
    }

    function clearJadwal(e) {
        e.stopPropagation();
        selectedDate = selectedSlot = null;
        tempHour     = null;
        tempMin      = null;
        document.getElementById('input-tanggal').value = '';
        document.getElementById('input-waktu').value   = '';
        document.getElementById('label-jadwal-selected').classList.add('hidden');
        document.getElementById('btn-clear-jadwal').classList.add('hidden');
        document.getElementById('icon-pesan-nanti').classList.remove('hidden');
    }

    function pesanSekarang() {
        clearJadwal({ stopPropagation: () => {} });
        closePesanNanti();
    }
</script>
@endpush
