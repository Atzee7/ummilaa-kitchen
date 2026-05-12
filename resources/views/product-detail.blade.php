@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="px-[80px] pt-5">
    <div class="bg-green-100 text-green-800 px-5 py-[14px] rounded-xl text-[0.9rem] flex items-center gap-[10px]">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
</div>
@endif

<div class="px-[80px] py-[60px]">
    <div class="grid grid-cols-[1fr_1.2fr_auto] gap-[60px] items-start">

        {{-- GAMBAR --}}
        <div class="text-center">
            <img class="w-full max-w-[420px] h-[380px] object-cover rounded-[24px] shadow-[0_20px_60px_rgba(139,26,26,0.15)] mb-5"
                src="{{ $product->image && Str::startsWith($product->image, 'products/') ? asset('storage/' . $product->image) : $product->image }}"
                alt="{{ $product->name }}">
        </div>

        {{-- INFO --}}
        <div>
            <div class="inline-flex items-center gap-1.5 bg-maroon-100 text-maroon px-4 py-1.5 rounded-[20px] text-[0.78rem] font-extrabold tracking-[1px] uppercase mb-4">
                <i class="fas fa-tag"></i> {{ ucfirst($product->category) }}
            </div>
            <h1 class="font-playfair text-[2.4rem] text-[#1a1a1a] mb-3">{{ $product->name }}</h1>
            <div class="text-[1.8rem] font-extrabold text-maroon mb-4">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
            <span class="inline-flex items-center gap-1.5 text-[0.8rem] px-[14px] py-[5px] rounded-[20px] font-bold mb-5 {{ $product->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-pink-100 text-red-800' }}">
                ● @if($product->status === 'ready') Ready Stock @else Habis @endif
            </span>
            <div class="h-px bg-maroon-200 my-5"></div>
            <div class="text-[0.78rem] font-extrabold tracking-[2px] uppercase text-[#bbb] mb-2.5">Deskripsi Produk</div>
            <p class="text-[#666] leading-[1.8] text-[0.97rem]">{{ $product->description }}</p>
            <p class="mt-4 text-[0.88rem] text-[#999]">Stok tersedia: <span class="text-[#1a1a1a] font-bold">{{ $product->stock }} pcs</span></p>
        </div>

        {{-- ORDER BOX --}}
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 w-[280px] shadow-[0_4px_24px_rgba(139,26,26,0.06)] sticky top-[90px]">
            <div class="font-extrabold text-[#1a1a1a] text-[0.95rem] mb-5">Pesan Sekarang</div>
            <div class="flex items-center border-[1.5px] border-maroon-200 rounded-xl overflow-hidden mb-5">
                <button class="w-11 h-11 bg-[#fafafa] border-none text-[1.1rem] font-bold cursor-pointer text-maroon hover:bg-maroon-100 transition-colors" onclick="changeQty(-1)">−</button>
                <input class="flex-1 text-center border-none text-base font-bold font-sans outline-none text-[#1a1a1a]" type="number" id="qty" value="1" min="1" max="{{ $product->stock }}">
                <button class="w-11 h-11 bg-[#fafafa] border-none text-[1.1rem] font-bold cursor-pointer text-maroon hover:bg-maroon-100 transition-colors" onclick="changeQty(1)">+</button>
            </div>
            <div class="bg-maroon-50 rounded-xl px-4 py-[14px] mb-5">
                <p class="text-[0.82rem] text-[#999] mb-1">Subtotal</p>
                <strong id="subtotalText" class="text-[1.1rem] text-maroon font-extrabold">Rp{{ number_format($product->price, 0, ',', '.') }}</strong>
            </div>

            @auth
                @if($product->status !== 'habis')
                    <form method="POST" action="{{ route('cart.add') }}" id="formCart">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="hiddenQty" value="1">
                        <input type="hidden" name="action" id="formAction" value="add_cart">
                        <button type="submit" onclick="document.getElementById('formAction').value='add_cart'"
                            class="w-full py-[13px] bg-white text-maroon border-2 border-maroon rounded-xl text-[0.95rem] font-bold font-sans cursor-pointer transition-all duration-200 mb-[10px] flex items-center justify-center gap-2 hover:bg-maroon-100">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                        <button type="submit" onclick="document.getElementById('formAction').value='buy_now'"
                            class="w-full py-[13px] bg-maroon text-white border-none rounded-xl text-[0.95rem] font-bold font-sans cursor-pointer transition-all duration-200 flex items-center justify-center gap-2 hover:bg-maroon-dark hover:-translate-y-px hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)]">
                            <i class="fas fa-bolt"></i> Beli Sekarang
                        </button>
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
<div class="px-[80px] py-[60px] border-t border-maroon-200">
    <div class="text-center mb-9">
        <h2 class="font-playfair text-[1.8rem] text-[#1a1a1a]">Produk Terkait</h2>
    </div>
    <div class="grid grid-cols-4 gap-6">
        @foreach($related as $r)
        <a href="{{ route('product.show', $r->id) }}" class="rounded-[18px] overflow-hidden bg-white border-[1.5px] border-maroon-200 transition-all duration-300 cursor-pointer no-underline hover:shadow-[0_12px_36px_rgba(139,26,26,0.1)] hover:-translate-y-1">
            <img src="{{ $r->image && Str::startsWith($r->image, 'products/') ? asset('storage/' . $r->image) : $r->image }}" alt="{{ $r->name }}" class="w-full h-[160px] object-cover">
            <div class="p-[14px]">
                <h4 class="font-extrabold text-[#1a1a1a] text-[0.9rem] mb-1">{{ $r->name }}</h4>
                <p class="text-[0.82rem] text-[#999] leading-[1.4] mb-2.5">{{ Str::limit($r->description, 55) }}</p>
                <div class="flex justify-between items-center">
                    <span class="font-extrabold text-maroon text-[0.92rem]">Rp{{ number_format($r->price, 0, ',', '.') }}</span>
                    <button class="bg-maroon text-white border-none rounded-lg w-[34px] h-[34px] flex items-center justify-center text-[0.8rem] cursor-pointer transition-all duration-200 hover:bg-maroon-dark">
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
const price = {{ $product->price }};
const maxStock = {{ $product->stock }};

function changeQty(delta) {
    const input = document.getElementById('qty');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (maxStock > 0 && val > maxStock) val = maxStock;
    input.value = val;
    const hiddenQty = document.getElementById('hiddenQty');
    if (hiddenQty) hiddenQty.value = val;
    document.getElementById('subtotalText').textContent = 'Rp' + (price * val).toLocaleString('id-ID');
}

document.getElementById('qty')?.addEventListener('input', function() {
    let val = parseInt(this.value);
    if (isNaN(val) || val < 1) val = 1;
    if (maxStock > 0 && val > maxStock) val = maxStock;
    this.value = val;
    const hiddenQty = document.getElementById('hiddenQty');
    if (hiddenQty) hiddenQty.value = val;
    document.getElementById('subtotalText').textContent = 'Rp' + (price * val).toLocaleString('id-ID');
});
</script>
@endpush
