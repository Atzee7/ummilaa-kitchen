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
        <p id="cart-product-count" class="text-[#999] mt-1.5 text-[0.9rem]">{{ $carts->count() }} produk dalam keranjang Anda</p>
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
            <div data-cart-row="{{ $cart->id }}" class="grid grid-cols-[90px_1fr] sm:grid-cols-[90px_1fr_auto] gap-4 sm:gap-5 items-center bg-white border-[1.5px] border-maroon-200 rounded-[18px] p-4 sm:p-5 transition-shadow hover:shadow-[0_6px_24px_rgba(139,26,26,0.07)]">
                <img class="w-[90px] h-[90px] object-cover rounded-[14px]"
                    src="{{ $cart->product->image && Str::startsWith($cart->product->image, 'products/') ? asset('storage/' . $cart->product->image) : $cart->product->image }}"
                    alt="{{ $cart->product->name }}">
                <div>
                    <h3 class="font-extrabold text-[#1a1a1a] text-base mb-1">{{ $cart->product->name }}</h3>
                    <p class="text-[0.85rem] text-[#999] mb-2.5">{{ ucfirst($cart->product->category) }}</p>
                    <span class="font-extrabold text-maroon text-[0.97rem]">Rp{{ number_format($cart->product->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-3 col-span-2 sm:col-span-1">
                    <div class="flex items-center border-[1.5px] border-maroon-200 rounded-[10px] overflow-hidden">
                        <button type="button"
                            data-cart-id="{{ $cart->id }}"
                            data-action="decrement"
                            data-quantity="{{ $cart->quantity - 1 }}"
                            data-url="{{ route('cart.update', $cart->id) }}"
                            {{ $cart->quantity <= 1 ? 'disabled' : '' }}
                            class="w-9 h-9 bg-[#fafafa] border-none text-base font-bold cursor-pointer text-maroon hover:bg-maroon-100 transition-colors disabled:opacity-40 disabled:hover:bg-[#fafafa]">−</button>
                        <span id="qty-{{ $cart->id }}" class="w-11 text-center text-[0.95rem] font-bold">{{ $cart->quantity }}</span>
                        <button type="button"
                            data-cart-id="{{ $cart->id }}"
                            data-action="increment"
                            data-quantity="{{ $cart->quantity + 1 }}"
                            data-url="{{ route('cart.update', $cart->id) }}"
                            {{ $cart->quantity >= 10 ? 'disabled' : '' }}
                            class="w-9 h-9 bg-[#fafafa] border-none text-base font-bold cursor-pointer text-maroon hover:bg-maroon-100 transition-colors disabled:opacity-40 disabled:hover:bg-[#fafafa]">+</button>
                    </div>
                    <form method="POST" action="{{ route('cart.destroy', $cart->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-9 h-9 bg-pink-100 border-none rounded-[10px] cursor-pointer text-red-700 flex items-center justify-center text-[0.85rem] transition-all duration-200 hover:bg-red-700 hover:text-white">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                <p id="qty-error-{{ $cart->id }}" class="col-span-2 sm:col-span-3 text-red-600 text-[0.8rem] mt-1 hidden">
                    <i class="fas fa-exclamation-circle"></i> <span></span>
                </p>
            </div>
            @endforeach
        </div>

        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] p-7 shadow-[0_4px_24px_rgba(139,26,26,0.05)] lg:sticky lg:top-[90px]">
            <div class="font-extrabold text-[#1a1a1a] text-base mb-6 pb-4 border-b border-maroon-200">Ringkasan Pesanan</div>
            @foreach($carts as $cart)
            <div data-summary-row="{{ $cart->id }}" class="flex justify-between items-center mb-[14px] text-[0.9rem]">
                <span class="text-[#777]">{{ $cart->product->name }} <span class="item-qty">x{{ $cart->quantity }}</span></span>
                <strong class="text-[#1a1a1a] font-bold item-total">Rp{{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</strong>
            </div>
            @endforeach
            <div class="h-px bg-maroon-200 my-4"></div>
            <div class="flex justify-between items-center mb-[14px] text-[0.9rem]">
                <span class="text-[#777]">Subtotal</span>
                <strong class="text-[#1a1a1a] font-bold subtotal-display">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
            </div>
            <div class="h-px bg-maroon-200 my-4"></div>
            <div class="flex justify-between items-center mb-2">
                <span class="font-extrabold text-[#1a1a1a] text-base">Subtotal</span>
                <strong class="font-extrabold text-maroon text-[1.2rem] subtotal-display">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
            </div>
            <p class="text-[0.78rem] text-[#999] mb-6 text-center">
                <i class="fas fa-info-circle"></i> Ongkos kirim dihitung saat checkout
            </p>
            @if(\App\Models\Setting::get('store_open', '1') === '1')
            <a href="{{ route('checkout') }}"
                class="w-full py-[15px] bg-maroon text-white border-none rounded-xl text-base font-bold font-sans cursor-pointer transition-all duration-200 flex items-center justify-center gap-[10px] hover:bg-maroon-dark hover:-translate-y-px hover:shadow-[0_6px_20px_rgba(139,26,26,0.25)] no-underline">
                <i class="fas fa-credit-card"></i> Lanjut ke Checkout
            </a>
            @else
            <div class="w-full py-[15px] bg-gray-200 text-gray-400 rounded-xl text-base font-bold font-sans flex items-center justify-center gap-[10px] cursor-not-allowed select-none pointer-events-none">
                <i class="fas fa-store-slash"></i> Toko Sedang Tutup
            </div>
            @endif
            <a href="{{ route('catalogue') }}"
                class="w-full py-3 mt-[10px] bg-white text-maroon border-[1.5px] border-maroon rounded-xl text-[0.9rem] font-bold font-sans cursor-pointer transition-all duration-200 text-center block no-underline hover:bg-maroon-100">
                Lanjut Belanja
            </a>
        </div>

    </div>
    @endif

</div>

<script>
const CART_MAX_QTY = {{ $maxQty ?? 10 }};

(function () {
    function getCsrf() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    function updateTotals(subtotal, count) {
        const fmt = 'Rp' + Number(subtotal).toLocaleString('id-ID');
        document.querySelectorAll('.subtotal-display').forEach(el => el.textContent = fmt);
        const counter = document.getElementById('cart-product-count');
        if (counter) counter.textContent = count + ' produk dalam keranjang Anda';
    }

    function showRowError(cartId, msg) {
        const errEl = document.getElementById(`qty-error-${cartId}`);
        if (errEl) {
            errEl.querySelector('span').textContent = msg;
            errEl.classList.remove('hidden');
        }
    }

    function hideRowError(cartId) {
        const errEl = document.getElementById(`qty-error-${cartId}`);
        if (errEl) errEl.classList.add('hidden');
    }

    async function handleQty(btn) {
        const cartId = btn.dataset.cartId;
        const newQty = parseInt(btn.dataset.quantity);
        const url    = btn.dataset.url;
        const decBtn = document.querySelector(`[data-cart-id="${cartId}"][data-action="decrement"]`);
        const incBtn = document.querySelector(`[data-cart-id="${cartId}"][data-action="increment"]`);

        if (newQty > CART_MAX_QTY) {
            showRowError(cartId, 'Maksimal pesanan per produk adalah ' + CART_MAX_QTY + ' item.');
            return;
        }

        decBtn.disabled = true;
        incBtn.disabled = true;

        try {
            const res = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN':     getCsrf(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type':     'application/json',
                    'Accept':           'application/json',
                },
                body: JSON.stringify({ quantity: newQty }),
            });
            const data = await res.json();

            if (!res.ok) {
                showRowError(cartId, data.error ?? 'Terjadi kesalahan. Coba lagi.');
                decBtn.disabled = false;
                incBtn.disabled = false;
                return;
            }

            hideRowError(cartId);
            document.getElementById(`qty-${cartId}`).textContent = data.quantity;
            decBtn.dataset.quantity = data.quantity - 1;
            incBtn.dataset.quantity = data.quantity + 1;
            document.querySelector(`[data-summary-row="${cartId}"] .item-qty`).textContent   = `x${data.quantity}`;
            document.querySelector(`[data-summary-row="${cartId}"] .item-total`).textContent = 'Rp' + Number(data.item_total).toLocaleString('id-ID');
            decBtn.disabled = data.quantity <= 1;
            incBtn.disabled = data.quantity >= CART_MAX_QTY;
            updateTotals(data.subtotal, data.cart_count);
        } catch (e) {
            if (document.body.contains(decBtn)) {
                decBtn.disabled = false;
                incBtn.disabled = false;
            }
        }
    }

    document.addEventListener('click', function (e) {
        const qtyBtn = e.target.closest('[data-action="increment"],[data-action="decrement"]');
        if (qtyBtn) { handleQty(qtyBtn); }
    });
})();
</script>

@endsection
