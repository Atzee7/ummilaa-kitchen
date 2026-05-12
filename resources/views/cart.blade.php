@extends('layouts.app')

@section('content')

<div class="px-4 md:px-10 lg:px-[80px] py-8 lg:py-[60px]">

    @if(session('success'))
    <div class="bg-green-100 text-green-800 px-5 py-[14px] rounded-xl text-[0.9rem] flex items-center gap-[10px] mb-6">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="mb-9">
        <h1 class="font-playfair text-[2rem] text-[#1a1a1a]">Keranjang Belanja</h1>
        <p class="text-[#999] mt-1.5 text-[0.9rem]">{{ $carts->count() }} produk dalam keranjang Anda</p>
    </div>

    @if($carts->isEmpty())
    <div class="text-center py-[80px] px-10 text-[#bbb]">
        <i class="fas fa-shopping-cart text-[3.5rem] mb-5 block"></i>
        <h3 class="text-[1.2rem] text-[#999] mb-3">Keranjang Anda masih kosong</h3>
        <p>Yuk mulai belanja produk favorit dari Ummilaa Kitchen</p>
        <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 bg-maroon text-white px-7 py-3 rounded-xl font-bold text-[0.9rem] mt-4 no-underline hover:bg-maroon-dark transition-colors">
            <i class="fas fa-utensils"></i> Mulai Belanja
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 lg:gap-10 items-start">

        <div class="flex flex-col gap-4">
            @foreach($carts as $cart)
            <div class="grid grid-cols-[90px_1fr_auto] gap-5 items-center bg-white border-[1.5px] border-maroon-200 rounded-[18px] p-5 transition-shadow hover:shadow-[0_6px_24px_rgba(139,26,26,0.07)]">
                <img class="w-[90px] h-[90px] object-cover rounded-[14px]"
                    src="{{ $cart->product->image && Str::startsWith($cart->product->image, 'products/') ? asset('storage/' . $cart->product->image) : $cart->product->image }}"
                    alt="{{ $cart->product->name }}">
                <div>
                    <h3 class="font-extrabold text-[#1a1a1a] text-base mb-1">{{ $cart->product->name }}</h3>
                    <p class="text-[0.85rem] text-[#999] mb-2.5">{{ ucfirst($cart->product->category) }}</p>
                    <span class="font-extrabold text-maroon text-[0.97rem]">Rp{{ number_format($cart->product->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('cart.update', $cart->id) }}">
                        @csrf @method('PATCH')
                        <div class="flex items-center border-[1.5px] border-maroon-200 rounded-[10px] overflow-hidden">
                            <button type="submit" name="quantity" value="{{ $cart->quantity - 1 }}"
                                class="w-9 h-9 bg-[#fafafa] border-none text-base font-bold cursor-pointer text-maroon hover:bg-maroon-100 transition-colors">−</button>
                            <span class="w-11 text-center text-[0.95rem] font-bold">{{ $cart->quantity }}</span>
                            <button type="submit" name="quantity" value="{{ $cart->quantity + 1 }}"
                                class="w-9 h-9 bg-[#fafafa] border-none text-base font-bold cursor-pointer text-maroon hover:bg-maroon-100 transition-colors">+</button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('cart.destroy', $cart->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-9 h-9 bg-pink-100 border-none rounded-[10px] cursor-pointer text-red-700 flex items-center justify-center text-[0.85rem] transition-all duration-200 hover:bg-red-700 hover:text-white">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 shadow-[0_4px_24px_rgba(139,26,26,0.05)] lg:sticky lg:top-[90px]">
            <div class="font-extrabold text-[#1a1a1a] text-base mb-6 pb-4 border-b border-maroon-200">Ringkasan Pesanan</div>
            @foreach($carts as $cart)
            <div class="flex justify-between items-center mb-[14px] text-[0.9rem]">
                <span class="text-[#777]">{{ $cart->product->name }} x{{ $cart->quantity }}</span>
                <strong class="text-[#1a1a1a] font-bold">Rp{{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</strong>
            </div>
            @endforeach
            <div class="h-px bg-maroon-200 my-4"></div>
            <div class="flex justify-between items-center mb-[14px] text-[0.9rem]">
                <span class="text-[#777]">Subtotal</span>
                <strong class="text-[#1a1a1a] font-bold">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
            </div>
            <div class="h-px bg-maroon-200 my-4"></div>
            <div class="flex justify-between items-center mb-2">
                <span class="font-extrabold text-[#1a1a1a] text-base">Subtotal</span>
                <strong class="font-extrabold text-maroon text-[1.2rem]">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
            </div>
            <p class="text-[0.78rem] text-[#999] mb-6 text-center">
                <i class="fas fa-info-circle"></i> Ongkos kirim dihitung saat checkout
            </p>
            <a href="{{ route('checkout') }}"
                class="w-full py-[15px] bg-maroon text-white border-none rounded-xl text-base font-bold font-sans cursor-pointer transition-all duration-200 flex items-center justify-center gap-[10px] hover:bg-maroon-dark hover:-translate-y-px hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)] no-underline">
                <i class="fas fa-credit-card"></i> Lanjut ke Checkout
            </a>
            <a href="{{ route('catalogue') }}"
                class="w-full py-3 mt-[10px] bg-white text-maroon border-[1.5px] border-maroon rounded-xl text-[0.9rem] font-bold font-sans cursor-pointer transition-all duration-200 text-center block no-underline hover:bg-maroon-100">
                Lanjut Belanja
            </a>
        </div>

    </div>
    @endif

</div>

@endsection
