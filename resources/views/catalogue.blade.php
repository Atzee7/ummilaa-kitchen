@extends('layouts.app')

@section('content')

<div class="grid grid-cols-[280px_1fr] gap-10 px-[80px] py-[60px] min-h-[80vh]">

    {{-- SIDEBAR --}}
    <aside class="sticky top-[90px] h-fit">
        <div class="mb-8">
            <h2 class="font-playfair text-[1.8rem] text-[#1a1a1a]">Catalogue</h2>
            <p class="text-[#999] text-[0.88rem] mt-1.5">Temukan produk favorit Anda</p>
        </div>

        <div class="relative mb-8">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[#bbb] text-[0.9rem]"></i>
            <input type="text" id="searchInput" placeholder="Cari produk..."
                class="w-full py-[13px] pr-4 pl-11 border-2 border-maroon-200 rounded-xl text-[0.9rem] font-sans bg-[#fafafa] transition-all duration-200 focus:outline-none focus:border-maroon focus:bg-white">
        </div>

        <div class="text-[0.72rem] font-extrabold tracking-[2px] uppercase text-[#bbb] mb-[14px]">Kategori</div>
        <div class="flex flex-col gap-1 category-list">
            <button class="cat-btn flex items-center justify-between px-4 py-3 rounded-xl border-none font-sans text-[0.92rem] font-semibold cursor-pointer text-left transition-all duration-200
                {{ $kategori === 'semua' ? 'bg-maroon text-white' : 'bg-transparent text-[#555] hover:bg-maroon-100 hover:text-maroon' }}" data-cat="semua">
                <span><i class="fas fa-th mr-2.5"></i>Semua</span>
                <span class="text-[0.75rem] px-[10px] py-0.5 rounded-[20px] font-bold {{ $kategori === 'semua' ? 'bg-white/25' : 'bg-black/[0.08]' }}">{{ $products->count() }}</span>
            </button>
            @foreach($categories as $cat)
            <button class="cat-btn flex items-center justify-between px-4 py-3 rounded-xl border-none font-sans text-[0.92rem] font-semibold cursor-pointer text-left transition-all duration-200
                {{ $kategori === $cat->slug ? 'bg-maroon text-white' : 'bg-transparent text-[#555] hover:bg-maroon-100 hover:text-maroon' }}" data-cat="{{ $cat->slug }}">
                <span>{{ $cat->name }}</span>
                <span class="text-[0.75rem] px-[10px] py-0.5 rounded-[20px] font-bold {{ $kategori === $cat->slug ? 'bg-white/25' : 'bg-black/[0.08]' }}">{{ $cat->products_count }}</span>
            </button>
            @endforeach
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="catalogue-main">
        <div class="flex justify-between items-center mb-8 pb-5 border-b-[1.5px] border-maroon-200">
            <div>
                <h3 id="activeLabel" class="font-playfair text-[1.4rem] text-[#1a1a1a]">Semua Produk</h3>
                <span id="productCount" class="text-[#999] text-[0.88rem]">{{ $products->count() }} produk ditemukan</span>
            </div>
            <select id="sortSelect"
                class="px-4 py-[9px] border-2 border-maroon-200 rounded-[10px] font-sans text-[0.88rem] font-semibold text-[#555] bg-white cursor-pointer focus:outline-none focus:border-maroon">
                <option value="terbaru">Terbaru</option>
                <option value="harga_asc">Harga Terendah</option>
                <option value="harga_desc">Harga Tertinggi</option>
            </select>
        </div>

        <div class="grid grid-cols-3 gap-6" id="productsGrid">
            @foreach($products as $product)
            <div class="prod-card rounded-[18px] overflow-hidden bg-white border-[1.5px] border-maroon-200 transition-all duration-300 relative {{ $product->status === 'habis' ? 'cursor-default' : 'cursor-pointer hover:shadow-[0_16px_48px_rgba(139,26,26,0.1)] hover:-translate-y-[5px]' }}"
                data-cat="{{ $product->categoryRelation->slug ?? '' }}"
                data-name="{{ strtolower($product->name) }}"
                @if($product->status !== 'habis')
                    onclick="window.location='{{ route('product.show', $product->id) }}'"
                @endif>

                <div class="relative overflow-hidden h-[200px]">
                    @if($product->image && Str::startsWith($product->image, 'products/'))
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-[400ms] {{ $product->status === 'habis' ? 'grayscale brightness-50' : 'hover:scale-[1.06]' }}">
                    @else
                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-[400ms] {{ $product->status === 'habis' ? 'grayscale brightness-50' : 'hover:scale-[1.06]' }}">
                    @endif
                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-maroon text-[0.72rem] font-extrabold px-3 py-1 rounded-[20px] tracking-[0.5px]">
                        {{ $product->categoryRelation->name ?? $product->category }}
                    </span>
                </div>

                <div class="p-[18px]">
                    <span class="inline-flex items-center gap-1 text-[0.7rem] px-[10px] py-[3px] rounded-[20px] font-bold mb-2 {{ $product->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-pink-100 text-red-800' }}">
                        ● @if($product->status === 'ready') Ready Stock @else Habis @endif
                    </span>
                    <h3 class="font-extrabold text-[#1a1a1a] text-[0.97rem] mb-1.5">{{ $product->name }}</h3>
                    <p class="text-[0.82rem] text-[#aaa] leading-[1.5] mb-[14px] min-h-[36px]">{{ Str::limit($product->description, 60) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-maroon text-base">Rp{{ number_format($product->price, 0, ',', '.') }}</span>

                        @if($product->status === 'habis')
                            <button class="bg-[#ccc] text-white border-none rounded-[10px] w-[38px] h-[38px] flex items-center justify-center text-[0.85rem] cursor-not-allowed pointer-events-none" onclick="event.stopPropagation()">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        @elseif(auth()->check())
                            <button class="bg-maroon text-white border-none rounded-[10px] w-[38px] h-[38px] flex items-center justify-center text-[0.85rem] cursor-pointer transition-all duration-200 hover:bg-maroon-dark hover:scale-110" onclick="event.stopPropagation(); window.location='{{ route('product.show', $product->id) }}'">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="bg-maroon text-white no-underline rounded-[10px] w-[38px] h-[38px] flex items-center justify-center text-[0.85rem] transition-all duration-200 hover:bg-maroon-dark hover:scale-110" onclick="event.stopPropagation()">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="hidden text-center py-[80px] px-10 text-[#bbb]" id="emptyState">
            <i class="fas fa-search text-[3rem] mb-4 block"></i>
            <p class="text-[0.95rem]">Produk tidak ditemukan</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const cards = document.querySelectorAll('.prod-card');
    const catBtns = document.querySelectorAll('.cat-btn');
    const searchInput = document.getElementById('searchInput');
    const activeLabel = document.getElementById('activeLabel');
    const productCount = document.getElementById('productCount');
    const emptyState = document.getElementById('emptyState');
    const grid = document.getElementById('productsGrid');
    const sortSelect = document.getElementById('sortSelect');

    const catLabels = {
        semua: 'Semua Produk',
        @foreach($categories as $cat)
        '{{ $cat->slug }}': '{{ $cat->name }}',
        @endforeach
    };

    let currentCat = 'semua';

    function getPrice(card) {
        return parseInt(card.querySelector('.prod-price, [class*="text-maroon"]').textContent.replace(/[^0-9]/g, ''));
    }

    function filterProducts() {
        const keyword = searchInput.value.toLowerCase();
        let count = 0;
        const cardsArray = Array.from(cards);
        const sortValue = sortSelect.value;
        cardsArray.sort((a, b) => {
            const pa = parseInt(a.querySelector('[class*="font-extrabold text-maroon"]')?.textContent.replace(/[^0-9]/g,'') || 0);
            const pb = parseInt(b.querySelector('[class*="font-extrabold text-maroon"]')?.textContent.replace(/[^0-9]/g,'') || 0);
            if (sortValue === 'harga_asc') return pa - pb;
            if (sortValue === 'harga_desc') return pb - pa;
            return 0;
        });
        cardsArray.forEach(card => grid.appendChild(card));
        cardsArray.forEach(card => {
            const catMatch = currentCat === 'semua' || card.dataset.cat === currentCat;
            const searchMatch = card.dataset.name.includes(keyword);
            if (catMatch && searchMatch) { card.style.display = 'block'; count++; }
            else { card.style.display = 'none'; }
        });
        activeLabel.textContent = catLabels[currentCat] || 'Semua Produk';
        productCount.textContent = `${count} produk ditemukan`;
        emptyState.style.display = count === 0 ? 'block' : 'none';
        grid.style.display = count === 0 ? 'none' : 'grid';
    }

    catBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            catBtns.forEach(b => {
                b.classList.remove('bg-maroon', 'text-white');
                b.classList.add('bg-transparent', 'text-[#555]');
                b.querySelector('span:last-child')?.classList.replace('bg-white/25', 'bg-black/[0.08]');
            });
            btn.classList.add('bg-maroon', 'text-white');
            btn.classList.remove('bg-transparent', 'text-[#555]');
            btn.querySelector('span:last-child')?.classList.replace('bg-black/[0.08]', 'bg-white/25');
            currentCat = btn.dataset.cat;
            filterProducts();
        });
    });

    searchInput.addEventListener('input', filterProducts);
    sortSelect.addEventListener('change', filterProducts);
</script>
@endpush
