@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="px-4 md:px-10 lg:px-[80px] pt-5">
    <div class="bg-green-100 text-green-800 px-5 py-[14px] rounded-xl text-[0.9rem] flex items-center gap-[10px]">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
</div>
@endif

@if($errors->has('quantity'))
<div class="px-4 md:px-10 lg:px-[80px] pt-5">
    <div class="bg-red-100 text-red-800 px-5 py-[14px] rounded-xl text-[0.9rem] flex items-center gap-[10px]">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first('quantity') }}
    </div>
</div>
@endif

<div class="px-4 md:px-10 lg:px-[80px] py-10 lg:py-[60px]">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.2fr_auto] gap-8 lg:gap-[60px] items-start">

        {{-- GAMBAR --}}
        <div class="text-center">
            <img class="w-full max-w-[420px] h-[240px] sm:h-[320px] lg:h-[380px] object-cover rounded-[20px] sm:rounded-[24px] shadow-[0_20px_60px_rgba(139,26,26,0.15)] mb-5"
                src="{{ $product->image && Str::startsWith($product->image, 'products/') ? asset('storage/' . $product->image) : $product->image }}"
                alt="{{ $product->name }}">
        </div>

        {{-- INFO --}}
        <div>
            <div class="inline-flex items-center gap-1.5 bg-maroon-100 text-maroon px-4 py-1.5 rounded-[20px] text-[0.78rem] font-extrabold tracking-[1px] uppercase mb-4">
                <i class="fas fa-tag"></i> {{ ucfirst($product->category) }}
            </div>
            <h1 class="font-playfair text-[1.6rem] sm:text-[2rem] lg:text-[2.4rem] text-[#1a1a1a] mb-2 sm:mb-3">{{ $product->name }}</h1>
            <div class="text-[1.3rem] sm:text-[1.6rem] lg:text-[1.8rem] font-extrabold text-maroon mb-3 sm:mb-4">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
            <span class="inline-flex items-center gap-1 text-[0.72rem] sm:text-[0.8rem] px-3 sm:px-[14px] py-[4px] sm:py-[5px] rounded-[20px] font-bold mb-4 sm:mb-5 {{ $product->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-pink-100 text-red-800' }}">
                ● @if($product->status === 'ready') Ready Stock @else Habis @endif
            </span>
            <div class="h-px bg-maroon-200 my-4 sm:my-5"></div>
            <div class="text-[0.72rem] sm:text-[0.78rem] font-extrabold tracking-[2px] uppercase text-[#bbb] mb-2">Deskripsi Produk</div>
            <p class="text-[#666] leading-[1.75] text-[0.88rem] sm:text-[0.97rem]">{{ $product->description }}</p>
            <p class="mt-3 text-[0.82rem] sm:text-[0.88rem] text-[#999]">Stok tersedia: <span class="text-[#1a1a1a] font-bold">{{ $product->stock }} pcs</span></p>
        </div>

        {{-- ORDER BOX --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[18px] sm:rounded-[20px] p-5 sm:p-7 w-full lg:w-[280px] shadow-[0_4px_24px_rgba(139,26,26,0.06)] lg:sticky lg:top-[90px]">
            <div class="font-extrabold text-[#1a1a1a] text-[0.88rem] sm:text-[0.95rem] mb-4 sm:mb-5">Pesan Sekarang</div>
            <div class="flex items-center border-[1.5px] border-maroon-200 rounded-xl overflow-hidden mb-4 sm:mb-5">
                <button class="w-9 h-9 sm:w-11 sm:h-11 bg-[#fafafa] border-none text-[1rem] sm:text-[1.1rem] font-bold cursor-pointer text-maroon hover:bg-maroon-100 transition-colors" onclick="changeQty(-1)">−</button>
                <input class="flex-1 text-center border-none text-[0.9rem] sm:text-base font-bold font-sans outline-none text-[#1a1a1a] [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" type="number" id="qty" value="1" min="1" max="{{ $product->stock }}">
                <button class="w-9 h-9 sm:w-11 sm:h-11 bg-[#fafafa] border-none text-[1rem] sm:text-[1.1rem] font-bold cursor-pointer text-maroon hover:bg-maroon-100 transition-colors" onclick="changeQty(1)">+</button>
            </div>
            <p id="qty-error-msg" style="display:none" class="text-[0.75rem] text-red-600 flex items-center gap-[6px] -mt-3 mb-3">
                <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                <span id="qty-error-text"></span>
            </p>
            <div class="bg-maroon-50 rounded-xl px-3 sm:px-4 py-[10px] sm:py-[14px] mb-4 sm:mb-5">
                <p class="text-[0.75rem] sm:text-[0.82rem] text-[#999] mb-0.5 sm:mb-1">Subtotal</p>
                <strong id="subtotalText" class="text-[0.95rem] sm:text-[1.1rem] text-maroon font-extrabold">Rp{{ number_format($product->price, 0, ',', '.') }}</strong>
            </div>

            @auth
                @if($product->status !== 'habis')
                    <form method="POST" action="{{ route('cart.add') }}" id="formCart">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="hiddenQty" value="1">
                        <input type="hidden" name="action" id="formAction" value="add_cart">
                        <button type="submit" onclick="document.getElementById('formAction').value='add_cart'"
                            class="w-full py-[10px] sm:py-[13px] bg-white text-maroon border-2 border-maroon rounded-xl text-[0.85rem] sm:text-[0.95rem] font-bold font-sans cursor-pointer transition-all duration-200 mb-[10px] flex items-center justify-center gap-2 hover:bg-maroon-100">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                        @if(\App\Models\Setting::get('store_open', '1') === '1')
                        <button type="submit" onclick="document.getElementById('formAction').value='buy_now'"
                            class="w-full py-[10px] sm:py-[13px] bg-maroon text-white border-none rounded-xl text-[0.85rem] sm:text-[0.95rem] font-bold font-sans cursor-pointer transition-all duration-200 flex items-center justify-center gap-2 hover:bg-maroon-dark hover:-translate-y-px hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)]">
                            <i class="fas fa-bolt"></i> Beli Sekarang
                        </button>
                        @else
                        <button type="button" disabled
                            class="w-full py-[13px] bg-maroon text-white border-none rounded-xl text-[0.95rem] font-bold font-sans opacity-50 cursor-not-allowed pointer-events-none flex items-center justify-center gap-2">
                            <i class="fas fa-store-slash"></i> Toko Sedang Tutup
                        </button>
                        @endif
                    </form>
                @else
                    <button class="w-full py-[13px] bg-white text-maroon border-2 border-maroon rounded-xl text-[0.95rem] font-bold font-sans opacity-50 cursor-not-allowed pointer-events-none flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i> Stok Habis
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="w-full py-[13px] bg-white text-maroon border-2 border-maroon rounded-xl text-[0.95rem] font-bold font-sans flex items-center justify-center gap-2 no-underline hover:bg-maroon-100 transition-colors">
                    <i class="fas fa-sign-in-alt"></i> Login untuk Membeli
                </a>
            @endauth
        </div>

    </div>
</div>

{{-- RELATED --}}
@if($related->count() > 0)
<div class="px-4 md:px-10 lg:px-[80px] py-10 lg:py-[60px] border-t border-maroon-200">
    <div class="text-center mb-9">
        <h2 class="font-playfair text-[1.8rem] text-[#1a1a1a]">Produk Terkait</h2>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
        @foreach($related as $r)
        <a href="{{ $r->status !== 'habis' ? route('product.show', $r->id) : '#' }}"
           class="rounded-[18px] overflow-hidden bg-white border-[1.5px] border-maroon-200 transition-all duration-300 no-underline {{ $r->status === 'habis' ? 'cursor-default' : 'cursor-pointer hover:shadow-[0_12px_36px_rgba(139,26,26,0.1)] hover:-translate-y-1' }}">
            <div class="relative overflow-hidden h-[160px]">
                <img src="{{ $r->image && Str::startsWith($r->image, 'products/') ? asset('storage/' . $r->image) : $r->image }}"
                     alt="{{ $r->name }}"
                     class="w-full h-full object-cover transition-transform duration-[400ms] {{ $r->status === 'habis' ? 'grayscale brightness-50' : 'hover:scale-[1.06]' }}">
            </div>
            <div class="p-[14px]">
                <span class="inline-flex items-center gap-1 text-[0.6rem] px-2 py-[2px] rounded-[20px] font-bold mb-1.5 {{ $r->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-pink-100 text-red-800' }}">
                    ● @if($r->status === 'ready') Ready Stock @else Habis @endif
                </span>
                <h4 class="font-extrabold text-[#1a1a1a] text-[0.9rem] mb-1">{{ $r->name }}</h4>
                <p class="text-[0.82rem] text-[#999] leading-[1.4] mb-2.5">{{ Str::limit($r->description, 55) }}</p>
                <div class="flex justify-between items-center">
                    <span class="font-extrabold text-maroon text-[0.92rem]">Rp{{ number_format($r->price, 0, ',', '.') }}</span>
                    <button class="border-none rounded-lg w-[34px] h-[34px] flex items-center justify-center text-[0.8rem] transition-all duration-200 {{ $r->status === 'habis' ? 'bg-[#ccc] text-white cursor-not-allowed pointer-events-none' : 'bg-maroon text-white cursor-pointer hover:bg-maroon-dark' }}">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
const price    = {{ $product->price }};
const maxStock = {{ $product->stock }};
const MAX_QTY  = maxStock > 0 ? maxStock : 0;

function showQtyError(qty) {
    const el = document.getElementById('qty-error-msg');
    const txt = document.getElementById('qty-error-text');
    if (txt) txt.textContent = `Tidak dapat memesan ${qty} item, stok hanya tersisa ${MAX_QTY} item.`;
    if (el) el.style.display = 'flex';
}

function hideQtyError() {
    const el = document.getElementById('qty-error-msg');
    if (el) el.style.display = 'none';
}

function changeQty(delta) {
    const input = document.getElementById('qty');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > MAX_QTY) {
        showQtyError(val);
        val = MAX_QTY;
    } else {
        hideQtyError();
    }
    input.value = val;
    const hiddenQty = document.getElementById('hiddenQty');
    if (hiddenQty) hiddenQty.value = val;
    document.getElementById('subtotalText').textContent = 'Rp' + (price * val).toLocaleString('id-ID');
}

document.getElementById('qty')?.addEventListener('focus', function () {
    this.select();
});

document.getElementById('qty')?.addEventListener('input', function () {
    let val = parseInt(this.value);
    if (isNaN(val) || val < 1) val = 1;
    if (val > MAX_QTY) {
        showQtyError(val);
        val = MAX_QTY;
    } else {
        hideQtyError();
    }
    this.value = val;
    const hiddenQty = document.getElementById('hiddenQty');
    if (hiddenQty) hiddenQty.value = val;
    document.getElementById('subtotalText').textContent = 'Rp' + (price * val).toLocaleString('id-ID');
});
</script>
@endpush
