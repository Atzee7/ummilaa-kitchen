@extends('admin.layouts.kasir')

@section('content')

{{-- ══════════════════════════════════════
     HALAMAN POS UTAMA
══════════════════════════════════════ --}}
<div id="pos-screen" class="flex h-screen overflow-hidden">

    {{-- KIRI — Panel Produk --}}
    <div class="flex-1 flex flex-col min-w-0 bg-[#f0f2f5]">

        {{-- Top bar --}}
        <div class="flex-shrink-0 bg-[linear-gradient(135deg,#8B1A1A_0%,#5a0e0e_100%)] px-6 py-4 flex items-center gap-4">
            <div class="flex items-center gap-3 mr-2">
                <div class="w-9 h-9 bg-white/15 rounded-xl flex items-center justify-center text-lg">🍽️</div>
                <div>
                    <div class="font-['Playfair_Display'] text-white font-bold text-[15px] leading-tight">Ummilaa Kitchen</div>
                    <div class="text-white/50 text-[10px] uppercase tracking-widest">Point of Sale</div>
                </div>
            </div>
            <div class="w-px h-8 bg-white/15 mx-1"></div>
            <div class="relative flex-1 max-w-[340px]">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-xs"></i>
                <input type="text" id="search-product" placeholder="Cari produk..."
                    class="w-full pl-9 pr-4 py-2.5 text-sm bg-white/10 border border-white/20 rounded-xl outline-none text-white placeholder-white/40 focus:bg-white/15 focus:border-white/35 transition-all font-['Nunito']">
            </div>
            <div class="flex-1"></div>
            <div class="text-right mr-1">
                <div id="live-time" class="text-white font-bold text-[17px] leading-tight tabular-nums">00:00:00</div>
                <div id="live-date" class="text-white/50 text-[11px]">—</div>
            </div>
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-2 bg-white/10 border border-white/20 text-white px-4 py-2 rounded-xl text-xs font-bold no-underline hover:bg-white/20 transition-all">
                <i class="fas fa-arrow-left text-[10px]"></i> Dashboard
            </a>
        </div>

        {{-- Category tabs --}}
        <div class="flex-shrink-0 bg-white border-b border-gray-100 px-5 py-2.5 flex items-center gap-2 overflow-x-auto [scrollbar-width:none]">
            <button class="cat-tab flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-[#8B1A1A] text-white shadow-sm" data-cat="semua">
                <i class="fas fa-th-large mr-1 text-[10px]"></i> Semua
            </button>
            @foreach($products->pluck('category')->unique()->filter()->sort() as $cat)
            <button class="cat-tab flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-gray-100 text-gray-500 hover:bg-[#fdf5f5] hover:text-[#8B1A1A]" data-cat="{{ $cat }}">
                {{ ucfirst($cat) }}
            </button>
            @endforeach
            <div class="ml-auto flex-shrink-0 text-xs text-gray-400 pr-1">
                <span id="visible-count">{{ $products->count() }}</span> produk
            </div>
        </div>

        {{-- Product grid --}}
        <div id="product-grid" class="flex-1 overflow-y-auto p-5 grid grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3 content-start">
            @foreach($products as $product)
            @php $isHabis = $product->status === 'habis'; @endphp
            <div class="product-card relative bg-white border-2 rounded-xl overflow-hidden flex flex-col transition-all duration-150 group
                    {{ $isHabis ? 'border-gray-100 cursor-not-allowed opacity-55' : 'border-gray-100 cursor-pointer hover:border-[#8B1A1A] hover:shadow-[0_4px_16px_rgba(139,26,26,0.15)] hover:-translate-y-0.5' }}"
                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                data-price="{{ $product->price }}" data-status="{{ $product->status }}" data-cat="{{ $product->category }}"
                onclick="if(this.dataset.status !== 'habis') addToCart(this)">

                <div id="badge-{{ $product->id }}"
                    class="product-card-badge hidden absolute top-1.5 right-1.5 bg-[#8B1A1A] text-white w-5 h-5 rounded-full text-[10px] font-black items-center justify-center z-20 shadow-md"></div>

                @if($isHabis)
                <div class="absolute inset-0 bg-gray-100/80 flex items-center justify-center z-10">
                    <span class="bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wide">Habis</span>
                </div>
                @endif

                @if(!$isHabis)
                <div class="absolute inset-0 bg-[#8B1A1A]/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10 pointer-events-none">
                    <div class="w-8 h-8 bg-[#8B1A1A] rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all scale-75 group-hover:scale-100">
                        <i class="fas fa-plus text-white text-xs"></i>
                    </div>
                </div>
                @endif

                <img src="{{ $product->image && Str::startsWith($product->image, 'products/') ? asset('storage/' . $product->image) : ($product->image ?? '') }}"
                    alt="{{ $product->name }}"
                    class="w-full h-24 object-cover flex-shrink-0 {{ $isHabis ? 'grayscale' : '' }}"
                    onerror="this.src='https://placehold.co/200x96/f9f9f9/d1d5db?text=No+Image'">
                <div class="p-2.5 flex flex-col gap-1">
                    <div class="text-[11px] font-bold text-gray-800 leading-tight line-clamp-2 min-h-[28px]">{{ $product->name }}</div>
                    <div class="text-[11px] font-extrabold text-[#8B1A1A]">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
            </div>
            @endforeach
            <div id="no-results" class="hidden col-span-full text-center py-16 text-gray-300">
                <i class="fas fa-search text-4xl mb-3 block"></i>
                <p class="text-sm font-semibold">Produk tidak ditemukan</p>
            </div>
        </div>
    </div>

    {{-- KANAN — Panel Kasir --}}
    <div class="w-[390px] flex-shrink-0 flex flex-col bg-white border-l border-gray-100 shadow-[-4px_0_24px_rgba(0,0,0,0.05)]">

        <div class="flex-shrink-0 bg-[linear-gradient(135deg,#8B1A1A_0%,#6B1414_100%)] px-5 py-4 flex items-center justify-between">
            <div>
                <h3 class="font-['Playfair_Display'] text-[15px] font-bold text-white leading-tight">Transaksi Kasir</h3>
                <p class="text-[11px] text-white/55 mt-0.5">Pembelian langsung di outlet</p>
            </div>
            <div class="relative">
                <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-white text-sm"></i>
                </div>
                <div id="cart-badge" class="hidden absolute -top-1 -right-1 bg-yellow-400 text-[#8B1A1A] w-5 h-5 rounded-full text-[10px] font-black items-center justify-center leading-none">0</div>
            </div>
        </div>

        {{-- Data Pembeli --}}
        <div class="flex-shrink-0 px-5 pt-4 pb-3.5 border-b border-gray-100">
            <div class="flex items-center gap-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">
                <i class="fas fa-user text-[#8B1A1A] text-[11px]"></i> Data Pembeli
            </div>
            <div class="space-y-2">
                <div class="relative">
                    <i class="fas fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                    <input type="text" id="nama_penerima" placeholder="Nama pembeli..."
                        class="w-full pl-9 pr-3 py-2.5 text-sm border-2 border-gray-100 rounded-xl outline-none bg-gray-50 placeholder-gray-300 focus:border-[#8B1A1A] focus:bg-[#fdf5f5] transition-all font-['Nunito']">
                </div>
            </div>
        </div>

        {{-- Item Pesanan --}}
        <div class="flex flex-col min-h-0 border-b border-gray-100 flex-auto">
            <div class="flex-shrink-0 flex items-center justify-between px-5 pt-3 pb-1.5">
                <div class="flex items-center gap-2 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">
                    <i class="fas fa-list-ul text-[#8B1A1A] text-[11px]"></i>
                    Item Pesanan
                    <span id="cart-count-label" class="normal-case font-bold text-gray-300">(0 item)</span>
                </div>
                <button onclick="clearCart()" id="btn-clear" class="hidden text-[10px] text-red-400 font-bold hover:text-red-600 transition-colors cursor-pointer border-none bg-transparent">
                    <i class="fas fa-trash-alt"></i> Kosongkan
                </button>
            </div>
            <div id="cart-items" class="flex-1 overflow-y-auto px-5 pb-3">
                <div class="text-center py-10 text-gray-200">
                    <i class="fas fa-shopping-basket text-4xl block mb-3"></i>
                    <p class="text-sm font-semibold text-gray-300">Keranjang kosong</p>
                    <p class="text-xs text-gray-200 mt-0.5">Pilih produk dari panel kiri</p>
                </div>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="flex-shrink-0 px-5 py-3 border-b border-gray-100">
            <textarea id="catatan" rows="2"
                placeholder="Catatan pesanan (opsional)..."
                class="w-full px-3 py-2 text-xs border-2 border-gray-100 rounded-xl outline-none resize-none transition-all focus:border-[#8B1A1A] focus:bg-[#fdf5f5] font-['Nunito'] bg-gray-50 placeholder-gray-300"></textarea>
        </div>

        {{-- Total & Lanjut --}}
        <div class="flex-shrink-0 px-5 py-4 bg-[#fdf5f5]">
            <div class="flex justify-between items-center mb-1.5">
                <span class="text-xs text-gray-400">Jumlah Item</span>
                <span id="item-count-display" class="text-xs font-bold text-gray-500">0 item</span>
            </div>
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-[#f0e8e8]">
                <span class="text-sm font-extrabold text-gray-700">Total Pembayaran</span>
                <strong id="total-display" class="text-2xl font-extrabold text-[#8B1A1A] tabular-nums">Rp0</strong>
            </div>
            <button id="btn-lanjut" onclick="bukaModalPembayaran()" disabled
                class="w-full py-3.5 bg-[#8B1A1A] text-white rounded-xl text-sm font-extrabold font-['Nunito'] cursor-pointer transition-all hover:bg-[#6B1414] hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-40 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none flex items-center justify-center gap-2.5">
                <i class="fas fa-arrow-right text-base"></i> Lanjut ke Pembayaran
            </button>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════
     MODAL PEMBAYARAN (menggantikan pay-screen)
══════════════════════════════════════ --}}
<div id="modal-pembayaran" class="hidden fixed inset-0 bg-black/60 z-50 items-center justify-center p-4 backdrop-blur-[2px]">
    <div class="bg-white rounded-2xl w-full max-w-[780px] max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">

        {{-- Modal Header --}}
        <div class="flex-shrink-0 bg-[linear-gradient(135deg,#8B1A1A_0%,#5a0e0e_100%)] px-6 py-3.5 flex items-center gap-4">
            <button id="modal-back-btn" onclick="kembaliKeMetode()"
                class="hidden items-center gap-2 bg-white/10 border border-white/20 text-white px-3 py-1.5 rounded-xl text-xs font-bold hover:bg-white/20 transition-all cursor-pointer border-none">
                <i class="fas fa-arrow-left text-[10px]"></i> Metode
            </button>
            <div class="flex items-center gap-2 text-white/50 text-sm">
                <span class="text-white font-semibold text-sm">Kasir</span>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span id="modal-breadcrumb" class="text-white/80 text-sm">Pilih Metode</span>
            </div>
            <div class="flex-1"></div>
            <div class="text-right mr-3">
                <div class="text-white/50 text-[10px] uppercase tracking-widest">Total Tagihan</div>
                <div id="modal-header-total" class="text-white font-extrabold text-lg tabular-nums leading-tight">Rp0</div>
            </div>
            <button onclick="tutupModalPembayaran()"
                class="w-8 h-8 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-sm transition-colors cursor-pointer border-none flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="flex flex-1 min-h-0 overflow-hidden">

            {{-- LEFT — Ringkasan Pesanan --}}
            <div class="w-[260px] flex-shrink-0 bg-white border-r border-gray-100 flex flex-col overflow-hidden">
                <div class="flex-shrink-0 px-4 pt-4 pb-3 border-b border-gray-100">
                    <div class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">
                        <i class="fas fa-receipt text-[#8B1A1A] mr-1.5"></i> Ringkasan
                    </div>
                    <div class="bg-[#fdf5f5] border border-[#f0e8e8] rounded-xl px-3 py-2.5 space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user text-[#8B1A1A] text-xs w-4 text-center"></i>
                            <span id="sum-nama" class="text-xs font-bold text-gray-700 truncate">—</span>
                        </div>
                        </div>
                </div>
                <div class="flex-1 overflow-y-auto px-4 py-3">
                    <div id="sum-items" class="space-y-2 text-xs"></div>
                </div>
                <div class="flex-shrink-0 px-4 py-3 border-t border-gray-100 bg-[#fdf5f5]">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-[10px] text-gray-400">Jumlah Item</span>
                        <span id="sum-qty" class="text-[10px] font-bold text-gray-600">0 item</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-extrabold text-gray-700">Total</span>
                        <strong id="sum-total" class="text-lg font-extrabold text-[#8B1A1A] tabular-nums">Rp0</strong>
                    </div>
                </div>
            </div>

            {{-- RIGHT — Langkah Pembayaran --}}
            <div class="flex-1 overflow-y-auto">

                {{-- STEP A: Pilih Metode --}}
                <div id="step-method" class="max-w-[440px] mx-auto py-6 px-5">
                    <h2 class="font-['Playfair_Display'] text-xl font-bold text-gray-800 mb-1">Pilih Metode Pembayaran</h2>
                    <p class="text-xs text-gray-400 mb-5">Pilih cara pembayaran yang digunakan pelanggan</p>

                    <div class="space-y-2.5">
                        <button onclick="pindahKe('tunai')"
                            class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl border-2 border-gray-100 bg-white hover:border-green-400 hover:bg-green-50 hover:shadow-md transition-all cursor-pointer group">
                            <div class="w-11 h-11 bg-green-100 group-hover:bg-green-500 rounded-xl flex items-center justify-center transition-colors flex-shrink-0">
                                <i class="fas fa-money-bill-wave text-green-600 group-hover:text-white text-base transition-colors"></i>
                            </div>
                            <div class="text-left flex-1">
                                <div class="text-sm font-extrabold text-gray-800">Tunai</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Bayar dengan uang cash, termasuk hitung kembalian</div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 text-sm group-hover:text-green-400 transition-colors"></i>
                        </button>

                        <button onclick="pindahKe('qris')"
                            class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl border-2 border-gray-100 bg-white hover:border-blue-400 hover:bg-blue-50 hover:shadow-md transition-all cursor-pointer group">
                            <div class="w-11 h-11 bg-blue-100 group-hover:bg-blue-500 rounded-xl flex items-center justify-center transition-colors flex-shrink-0">
                                <i class="fas fa-qrcode text-blue-600 group-hover:text-white text-base transition-colors"></i>
                            </div>
                            <div class="text-left flex-1">
                                <div class="text-sm font-extrabold text-gray-800">QRIS</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Scan QR code — semua e-wallet & mobile banking</div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 text-sm group-hover:text-blue-400 transition-colors"></i>
                        </button>

                    </div>
                </div>

                {{-- STEP B: Tunai --}}
                <div id="step-tunai" class="hidden max-w-[400px] mx-auto py-6 px-5">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-11 h-11 bg-green-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-money-bill-wave text-white text-base"></i>
                        </div>
                        <div>
                            <h2 class="font-['Playfair_Display'] text-xl font-bold text-gray-800 leading-tight">Pembayaran Tunai</h2>
                            <p class="text-[11px] text-gray-400 mt-0.5">Masukkan nominal uang dari pelanggan</p>
                        </div>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-2xl px-5 py-3.5 mb-5 flex justify-between items-center">
                        <span class="text-sm text-green-700 font-semibold">Total yang harus dibayar</span>
                        <strong id="tunai-total" class="text-xl font-extrabold text-green-800 tabular-nums">Rp0</strong>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-4 shadow-sm">
                        <label class="block text-xs font-extrabold text-gray-500 uppercase tracking-widest mb-2">Uang yang Diterima (Rp)</label>
                        <input type="number" id="tunai-input" placeholder="Masukkan nominal..."
                            oninput="hitungKembalian()"
                            class="w-full px-4 py-3 text-xl font-extrabold text-gray-800 border-2 border-gray-200 rounded-xl outline-none focus:border-green-400 focus:bg-green-50 transition-all tabular-nums font-['Nunito']">
                        <div class="grid grid-cols-4 gap-2 mt-3" id="quick-amounts"></div>
                    </div>

                    <div id="kembalian-box" class="bg-white rounded-2xl border-2 border-gray-200 p-4 mb-5 shadow-sm flex justify-between items-center">
                        <div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-0.5">Kembalian</div>
                            <div class="text-xs text-gray-400" id="kembalian-hint">Masukkan nominal uang terlebih dahulu</div>
                        </div>
                        <strong id="tunai-kembalian" class="text-xl font-extrabold text-gray-300 tabular-nums">Rp—</strong>
                    </div>

                    <button id="btn-konfirmasi-tunai" onclick="konfirmasiPembayaran('Tunai')" disabled
                        class="w-full py-3.5 bg-green-600 text-white rounded-xl text-sm font-extrabold font-['Nunito'] cursor-pointer transition-all hover:bg-green-700 hover:shadow-lg disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2.5">
                        <i class="fas fa-check-circle text-base"></i> Konfirmasi Pembayaran
                    </button>
                </div>

                {{-- STEP C: QRIS --}}
                <div id="step-qris" class="hidden max-w-[380px] mx-auto py-6 px-5">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-11 h-11 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-qrcode text-white text-base"></i>
                        </div>
                        <div>
                            <h2 class="font-['Playfair_Display'] text-xl font-bold text-gray-800 leading-tight">Pembayaran QRIS</h2>
                            <p class="text-[11px] text-gray-400 mt-0.5">Arahkan pelanggan untuk scan QR code</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-2xl px-5 py-3.5 mb-5 flex justify-between items-center">
                        <span class="text-sm text-blue-700 font-semibold">Total yang harus dibayar</span>
                        <strong id="qris-total" class="text-xl font-extrabold text-blue-800 tabular-nums">Rp0</strong>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-4 shadow-sm text-center">
                        <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-3">Scan QR Code Ini</div>
                        <div class="inline-block bg-white border-2 border-dashed border-blue-200 rounded-2xl p-3">
                            <img id="qris-img" src="" alt="QRIS Code" class="w-[180px] h-[180px] mx-auto">
                        </div>
                        <div class="mt-3 bg-gray-50 rounded-xl px-4 py-2 inline-flex items-center gap-2">
                            <i class="fas fa-store text-[#8B1A1A] text-sm"></i>
                            <span class="text-sm font-extrabold text-gray-800">Ummilaa Kitchen</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Scan dengan aplikasi mobile banking atau e-wallet manapun</p>
                    </div>

                    <button id="btn-konfirmasi-qris" onclick="konfirmasiPembayaran('QRIS')"
                        class="w-full py-3.5 bg-blue-600 text-white rounded-xl text-sm font-extrabold font-['Nunito'] cursor-pointer transition-all hover:bg-blue-700 hover:shadow-lg flex items-center justify-center gap-2.5">
                        <i class="fas fa-check-circle text-base"></i> Konfirmasi Pembayaran Diterima
                    </button>
                </div>


            </div>{{-- /right steps --}}
        </div>{{-- /modal body --}}
    </div>
</div>


{{-- ══════════════════════════════════════
     MODAL STRUK / TRANSAKSI SELESAI
══════════════════════════════════════ --}}
<div id="modal-sukses" class="hidden fixed inset-0 bg-black/60 z-[60] items-center justify-center p-4 backdrop-blur-[2px]">
    <div class="bg-white rounded-2xl w-full max-w-[400px] overflow-hidden shadow-2xl">

        {{-- Header hijau --}}
        <div class="bg-[linear-gradient(135deg,#16a34a,#15803d)] px-6 py-5 text-center">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-check text-white text-xl"></i>
            </div>
            <div class="font-['Playfair_Display'] text-white font-bold text-lg leading-tight">Transaksi Berhasil!</div>
            <div class="text-white/60 text-[11px] mt-0.5">Pesanan dicatat dan langsung diselesaikan</div>
        </div>

        <div class="overflow-y-auto max-h-[60vh]">
            {{-- Struk --}}
            <div class="px-6 py-5">
                {{-- Info --}}
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 mb-4 text-xs">
                    <div>
                        <div class="text-gray-400 mb-0.5">No. Order</div>
                        <div id="struk-order-id" class="font-extrabold text-gray-800">#—</div>
                    </div>
                    <div>
                        <div class="text-gray-400 mb-0.5">Metode Bayar</div>
                        <div id="struk-metode" class="font-extrabold text-gray-800">—</div>
                    </div>
                    <div>
                        <div class="text-gray-400 mb-0.5">Nama Pembeli</div>
                        <div id="struk-nama" class="font-extrabold text-gray-800">—</div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-gray-400 mb-0.5">Waktu</div>
                        <div id="struk-waktu" class="font-extrabold text-gray-800">—</div>
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-200 my-3"></div>
                <div id="struk-items" class="space-y-2 text-xs mb-3"></div>
                <div class="border-t border-dashed border-gray-200 my-3"></div>

                <div class="space-y-1.5 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span id="struk-subtotal" class="font-semibold text-gray-700">—</span>
                    </div>
                    <div id="struk-tunai-row" class="hidden space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Uang Diterima</span>
                            <span id="struk-uang-diterima" class="font-semibold text-gray-700">—</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kembalian</span>
                            <span id="struk-kembalian" class="font-extrabold text-green-600">—</span>
                        </div>
                    </div>
                </div>

                <div class="border-t-2 border-gray-800 mt-3 pt-3 flex justify-between items-center">
                    <span class="font-extrabold text-gray-800 text-sm uppercase tracking-wide">Total</span>
                    <strong id="struk-total" class="text-2xl font-extrabold text-[#8B1A1A] tabular-nums">Rp0</strong>
                </div>

                <div class="border-t border-dashed border-gray-200 mt-3 pt-3 text-center">
                    <p class="text-xs text-gray-400">Terima kasih telah berkunjung ke</p>
                    <p class="text-sm font-extrabold text-gray-700 mt-0.5">Ummilaa Kitchen 🍽️</p>
                </div>
            </div>
        </div>

        <div class="px-6 pb-5">
            <button onclick="transaksiBaruDanReset()"
                class="w-full py-3.5 bg-[#8B1A1A] text-white rounded-xl text-sm font-extrabold font-['Nunito'] cursor-pointer transition-all hover:bg-[#6B1414] hover:shadow-lg flex items-center justify-center gap-2.5">
                <i class="fas fa-plus-circle text-base"></i> Transaksi Baru
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let cart = [];

    // ── Clock ──
    (function tick() {
        const now = new Date();
        document.getElementById('live-time').textContent = now.toLocaleTimeString('id-ID', { hour12: false });
        document.getElementById('live-date').textContent = now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        setTimeout(tick, 1000);
    })();

    function fmt(n) { return 'Rp' + Number(n).toLocaleString('id-ID'); }
    function getTotal() { return cart.reduce((s, i) => s + i.price * i.quantity, 0); }

    // ── Cart ──
    function addToCart(el) {
        const id = parseInt(el.dataset.id);
        const ex = cart.find(c => c.product_id === id);
        if (ex) ex.quantity++;
        else cart.push({ product_id: id, name: el.dataset.name, price: parseInt(el.dataset.price), quantity: 1 });
        renderCart();
        el.classList.add('scale-95');
        setTimeout(() => el.classList.remove('scale-95'), 100);
    }

    function renderCart() {
        const container = document.getElementById('cart-items');
        const totalQty  = cart.reduce((s, i) => s + i.quantity, 0);
        const badge     = document.getElementById('cart-badge');

        document.getElementById('cart-count-label').textContent = totalQty > 0 ? `(${totalQty} item)` : '(0 item)';
        document.getElementById('btn-clear').classList.toggle('hidden', cart.length === 0);

        if (cart.length === 0) {
            badge.classList.remove('flex'); badge.classList.add('hidden');
            container.innerHTML = `
                <div class="text-center py-10">
                    <i class="fas fa-shopping-basket text-4xl text-gray-200 block mb-3"></i>
                    <p class="text-sm font-semibold text-gray-300">Keranjang kosong</p>
                    <p class="text-xs text-gray-200 mt-0.5">Pilih produk dari panel kiri</p>
                </div>`;
        } else {
            badge.classList.remove('hidden'); badge.classList.add('flex');
            badge.textContent = totalQty;
            let html = '<div class="space-y-1.5">';
            cart.forEach((item, i) => {
                html += `
                <div class="flex items-center gap-2 bg-[#fdf5f5] border border-[#f0e8e8] rounded-xl px-3 py-2">
                    <div class="flex-1 min-w-0">
                        <div class="text-[11px] font-bold text-gray-800 truncate leading-tight">${item.name}</div>
                        <div class="text-[10px] text-gray-400 mt-0.5">${fmt(item.price)}</div>
                    </div>
                    <div class="flex items-center shrink-0">
                        <button onclick="changeQty(${i},-1)" class="w-6 h-6 border-2 border-gray-200 bg-white rounded-lg text-sm font-black cursor-pointer flex items-center justify-center hover:border-[#8B1A1A] hover:text-[#8B1A1A] text-gray-400 transition-colors leading-none">−</button>
                        <span class="text-sm font-black w-7 text-center text-gray-800 tabular-nums">${item.quantity}</span>
                        <button onclick="changeQty(${i},1)" class="w-6 h-6 border-2 border-gray-200 bg-white rounded-lg text-sm font-black cursor-pointer flex items-center justify-center hover:border-[#8B1A1A] hover:text-[#8B1A1A] text-gray-400 transition-colors leading-none">+</button>
                    </div>
                    <div class="text-[11px] font-extrabold text-[#8B1A1A] shrink-0 min-w-[58px] text-right tabular-nums">${fmt(item.price * item.quantity)}</div>
                    <button onclick="removeItem(${i})" class="w-5 h-5 rounded-lg bg-red-50 text-red-400 text-[9px] border-none cursor-pointer transition-all hover:bg-red-500 hover:text-white flex items-center justify-center shrink-0"><i class="fas fa-times"></i></button>
                </div>`;
            });
            html += '</div>';
            container.innerHTML = html;
        }
        updateTotal();
        updateBadges();
    }

    function changeQty(i, d) { cart[i].quantity += d; if (cart[i].quantity <= 0) cart.splice(i, 1); renderCart(); }
    function removeItem(i)  { cart.splice(i, 1); renderCart(); }
    function clearCart()    { if (!confirm('Hapus semua item dari keranjang?')) return; cart = []; renderCart(); }

    function updateTotal() {
        const total = getTotal(), qty = cart.reduce((s, i) => s + i.quantity, 0);
        document.getElementById('total-display').textContent      = fmt(total);
        document.getElementById('item-count-display').textContent = qty + ' item';
        document.getElementById('btn-lanjut').disabled            = cart.length === 0;
    }

    function updateBadges() {
        document.querySelectorAll('.product-card').forEach(card => {
            if (card.dataset.status === 'habis') return;
            card.classList.remove('border-[#8B1A1A]', 'bg-[#fdf5f5]');
            card.classList.add('border-gray-100');
            const b = document.getElementById('badge-' + card.dataset.id);
            if (b) { b.classList.add('hidden'); b.classList.remove('flex'); }
        });
        cart.forEach(item => {
            const card = document.querySelector(`.product-card[data-id="${item.product_id}"]`);
            if (card) {
                card.classList.remove('border-gray-100');
                card.classList.add('border-[#8B1A1A]', 'bg-[#fdf5f5]');
                const b = document.getElementById('badge-' + item.product_id);
                if (b) { b.classList.remove('hidden'); b.classList.add('flex'); b.textContent = item.quantity; }
            }
        });
    }

    // ── Buka Modal Pembayaran ──
    function bukaModalPembayaran() {
        const nama = document.getElementById('nama_penerima').value.trim();
        if (!nama) { flashField('nama_penerima', 'Nama pembeli wajib diisi!', 'Nama pembeli...'); return; }
        if (!cart.length) return;

        // Populate ringkasan
        document.getElementById('sum-nama').textContent = nama;
        const qty = cart.reduce((s, i) => s + i.quantity, 0);
        document.getElementById('sum-qty').textContent = qty + ' item';
        document.getElementById('sum-total').textContent = fmt(getTotal());
        document.getElementById('modal-header-total').textContent = fmt(getTotal());

        let sumHtml = '';
        cart.forEach(item => {
            sumHtml += `
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-700 leading-tight truncate">${item.name}</div>
                    <div class="text-gray-400">${item.quantity} × ${fmt(item.price)}</div>
                </div>
                <div class="font-bold text-gray-700 tabular-nums shrink-0">${fmt(item.price * item.quantity)}</div>
            </div>`;
        });
        document.getElementById('sum-items').innerHTML = sumHtml;

        tampilkanStep('method');
        setBreadcrumb('Pilih Metode');
        document.getElementById('modal-back-btn').classList.add('hidden');
        document.getElementById('modal-back-btn').classList.remove('flex');

        const modal = document.getElementById('modal-pembayaran');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupModalPembayaran() {
        const modal = document.getElementById('modal-pembayaran');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function kembaliKeMetode() {
        tampilkanStep('method');
        setBreadcrumb('Pilih Metode');
        document.getElementById('modal-back-btn').classList.add('hidden');
        document.getElementById('modal-back-btn').classList.remove('flex');
    }

    // ── Step navigation ──
    function tampilkanStep(step) {
        ['method','tunai','qris'].forEach(s => {
            document.getElementById('step-' + s).classList.add('hidden');
        });
        if (document.getElementById('step-' + step)) {
            document.getElementById('step-' + step).classList.remove('hidden');
        }
    }

    function setBreadcrumb(label) {
        document.getElementById('modal-breadcrumb').textContent = label;
    }

    function pindahKe(step) {
        const total = getTotal();
        const backBtn = document.getElementById('modal-back-btn');

        if (step === 'tunai') {
            document.getElementById('tunai-total').textContent = fmt(total);
            document.getElementById('tunai-input').value = '';
            document.getElementById('tunai-kembalian').textContent = 'Rp—';
            document.getElementById('tunai-kembalian').className = 'text-xl font-extrabold text-gray-300 tabular-nums';
            document.getElementById('kembalian-hint').textContent = 'Masukkan nominal uang terlebih dahulu';
            document.getElementById('kembalian-box').className = 'bg-white rounded-2xl border-2 border-gray-200 p-4 mb-5 shadow-sm flex justify-between items-center';
            document.getElementById('btn-konfirmasi-tunai').disabled = true;
            const amounts = [...new Set([
                total,
                Math.ceil(total / 5000) * 5000,
                Math.ceil(total / 10000) * 10000,
                Math.ceil(total / 50000) * 50000,
            ])].slice(0, 4);
            document.getElementById('quick-amounts').innerHTML = amounts.map(a =>
                `<button onclick="setTunai(${a})" class="py-2 bg-green-50 border border-green-200 rounded-xl text-xs font-extrabold text-green-700 hover:bg-green-100 transition-colors cursor-pointer">${fmt(a)}</button>`
            ).join('');
            tampilkanStep('tunai');
            setBreadcrumb('Tunai');
        } else if (step === 'qris') {
            document.getElementById('qris-total').textContent = fmt(total);
            const qrData = encodeURIComponent('UMMILAA-KITCHEN|' + total + '|' + Date.now());
            document.getElementById('qris-img').src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrData}`;
            tampilkanStep('qris');
            setBreadcrumb('QRIS');
        }

        // Tampilkan tombol kembali ke metode
        backBtn.classList.remove('hidden');
        backBtn.classList.add('flex');
    }

    function setTunai(amount) {
        document.getElementById('tunai-input').value = amount;
        hitungKembalian();
    }

    function hitungKembalian() {
        const total    = getTotal();
        const diterima = parseInt(document.getElementById('tunai-input').value) || 0;
        const kembalian = diterima - total;
        const el   = document.getElementById('tunai-kembalian');
        const box  = document.getElementById('kembalian-box');
        const btn  = document.getElementById('btn-konfirmasi-tunai');
        const hint = document.getElementById('kembalian-hint');

        if (diterima === 0) {
            el.textContent = 'Rp—';
            el.className = 'text-xl font-extrabold text-gray-300 tabular-nums';
            box.className = 'bg-white rounded-2xl border-2 border-gray-200 p-4 mb-5 shadow-sm flex justify-between items-center';
            hint.textContent = 'Masukkan nominal uang terlebih dahulu';
            btn.disabled = true;
        } else if (kembalian >= 0) {
            el.textContent = fmt(kembalian);
            el.className = 'text-xl font-extrabold text-green-600 tabular-nums';
            box.className = 'bg-green-50 rounded-2xl border-2 border-green-300 p-4 mb-5 shadow-sm flex justify-between items-center';
            hint.textContent = kembalian === 0 ? 'Pas, tidak ada kembalian' : 'Kembalian untuk pelanggan';
            btn.disabled = false;
        } else {
            el.textContent = '− ' + fmt(Math.abs(kembalian));
            el.className = 'text-xl font-extrabold text-red-500 tabular-nums';
            box.className = 'bg-red-50 rounded-2xl border-2 border-red-300 p-4 mb-5 shadow-sm flex justify-between items-center';
            hint.textContent = 'Uang kurang — tambahkan ' + fmt(Math.abs(kembalian));
            btn.disabled = true;
        }
    }

    // ── Konfirmasi & simpan ke DB ──
    async function konfirmasiPembayaran(metode) {
        const nama    = document.getElementById('nama_penerima').value.trim();
        const catatan = document.getElementById('catatan').value.trim();
        const total   = getTotal();
        const uangDiterima = metode === 'Tunai' ? (parseInt(document.getElementById('tunai-input').value) || 0) : 0;

        const btnAktif = metode === 'Tunai'
            ? document.getElementById('btn-konfirmasi-tunai')
            : document.getElementById('btn-konfirmasi-qris');

        if (btnAktif) {
            btnAktif.disabled = true;
            btnAktif.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        }

        try {
            const res = await fetch('{{ route("admin.kasir.store") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ nama_penerima: nama, no_telepon: '-', metode_pembayaran: metode, catatan, items: cart })
            });

            if (!res.ok) {
                throw new Error('Server error: ' + res.status);
            }

            const data = await res.json();

            if (data.success) {
                tutupModalPembayaran();
                tampilkanStruk(data, nama, metode, total, uangDiterima);
            } else {
                alert('Gagal memproses pesanan. Coba lagi.');
                resetBtnKonfirmasi(metode);
            }
        } catch (err) {
            console.error('konfirmasiPembayaran error:', err);
            alert('Terjadi kesalahan. Coba lagi.');
            resetBtnKonfirmasi(metode);
        }
    }

    function resetBtnKonfirmasi(metode) {
        if (metode === 'Tunai') {
            const b = document.getElementById('btn-konfirmasi-tunai');
            b.disabled = false; b.innerHTML = '<i class="fas fa-check-circle text-base"></i> Konfirmasi Pembayaran';
        } else {
            const b = document.getElementById('btn-konfirmasi-qris');
            if (b) { b.disabled = false; b.innerHTML = '<i class="fas fa-check-circle text-base"></i> Konfirmasi Pembayaran Diterima'; }
        }
    }

    function tampilkanStruk(data, nama, metode, total, uangDiterima) {
        const now = new Date();
        document.getElementById('struk-waktu').textContent    = now.toLocaleString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit' });
        document.getElementById('struk-order-id').textContent = '#' + String(data.order_id).padStart(5, '0');
        document.getElementById('struk-nama').textContent     = nama;
        document.getElementById('struk-metode').textContent   = metode;
        document.getElementById('struk-subtotal').textContent = fmt(total);
        document.getElementById('struk-total').textContent    = fmt(total);

        let html = '';
        cart.forEach(item => {
            html += `
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-700 leading-tight">${item.name}</div>
                    <div class="text-gray-400">${item.quantity} × ${fmt(item.price)}</div>
                </div>
                <div class="font-bold text-gray-800 tabular-nums shrink-0">${fmt(item.price * item.quantity)}</div>
            </div>`;
        });
        document.getElementById('struk-items').innerHTML = html;

        const tunaiRow = document.getElementById('struk-tunai-row');
        if (metode === 'Tunai' && uangDiterima > 0) {
            tunaiRow.classList.remove('hidden');
            document.getElementById('struk-uang-diterima').textContent = fmt(uangDiterima);
            document.getElementById('struk-kembalian').textContent     = fmt(uangDiterima - total);
        } else {
            tunaiRow.classList.add('hidden');
        }

        const modal = document.getElementById('modal-sukses');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function transaksiBaruDanReset() {
        cart = [];
        ['nama_penerima','catatan'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
        renderCart();
        const modal = document.getElementById('modal-sukses');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // ── Flash helper ──
    function flashField(id, msg, ph) {
        const el = document.getElementById(id);
        el.classList.add('border-red-400', '!bg-red-50');
        el.placeholder = msg; el.focus();
        setTimeout(() => { el.classList.remove('border-red-400', '!bg-red-50'); el.placeholder = ph; }, 2500);
    }

    // ── Category filter ──
    document.querySelectorAll('.cat-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.cat-tab').forEach(t => {
                t.classList.remove('bg-[#8B1A1A]','text-white','shadow-sm');
                t.classList.add('bg-gray-100','text-gray-500');
            });
            tab.classList.add('bg-[#8B1A1A]','text-white','shadow-sm');
            tab.classList.remove('bg-gray-100','text-gray-500');
            const cat = tab.dataset.cat;
            let visible = 0;
            document.querySelectorAll('.product-card').forEach(card => {
                const show = cat === 'semua' || card.dataset.cat === cat;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            document.getElementById('visible-count').textContent = visible;
            document.getElementById('no-results').classList.toggle('hidden', visible > 0);
        });
    });

    // ── Search ──
    document.getElementById('search-product').addEventListener('input', function() {
        const kw = this.value.toLowerCase();
        let visible = 0;
        document.querySelectorAll('.product-card').forEach(card => {
            const show = card.dataset.name.toLowerCase().includes(kw);
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('visible-count').textContent = visible;
        document.getElementById('no-results').classList.toggle('hidden', visible > 0);
    });
</script>
@endpush
