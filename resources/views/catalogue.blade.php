@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
@endpush

@section('content')

@if(session('success'))
<div class="px-4 md:px-10 lg:px-[80px] pt-5">
    <div class="bg-green-100 text-green-800 px-5 py-[14px] rounded-xl text-[0.9rem] flex items-center gap-[10px]">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
</div>
@endif

{{-- Mobile filter toggle --}}
<div data-aos="fade-down" data-aos-duration="600" class="lg:hidden px-4 pt-6 pb-2 flex items-center justify-between">
    <div>
        <h2 class="font-playfair text-[1.5rem] text-[#1a1a1a]">Catalogue</h2>
        <p class="text-[#999] text-[0.85rem]">Temukan produk favorit Anda</p>
    </div>
    <button id="filter-toggle"
        class="flex items-center gap-2 px-4 py-2 bg-maroon text-white rounded-xl text-sm font-bold border-none cursor-pointer">
        <i class="fas fa-sliders-h text-xs"></i> Filter
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-6 lg:gap-10 px-4 md:px-10 lg:px-[80px] py-6 lg:pt-[30px] lg:pb-[60px] min-h-[80vh]">

    {{-- SIDEBAR --}}
    <aside data-aos="fade-right" data-aos-duration="700" id="filter-sidebar" class="hidden lg:block lg:sticky lg:top-[90px] h-fit">
        <div class="hidden lg:block mb-8">
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
        <div data-aos="fade-up" data-aos-duration="700" class="flex justify-between items-center mb-8 pb-5 border-b-[1.5px] border-maroon-200">
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

        <div data-aos="fade-up" data-aos-duration="700" data-aos-delay="100" class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6" id="productsGrid">
            @foreach($products as $product)
            <div class="prod-card rounded-[18px] overflow-hidden bg-white border-[1.5px] border-maroon-200 shadow-[0_2px_8px_rgba(139,26,26,0.2)] transition-all duration-300 relative {{ $product->status === 'habis' ? 'cursor-default' : 'cursor-pointer hover:shadow-[0_16px_48px_rgba(139,26,26,0.1)] hover:-translate-y-[5px]' }}"
                data-cat="{{ $product->categoryRelation->slug ?? '' }}"
                data-name="{{ strtolower($product->name) }}"
                @if($product->status !== 'habis')
                    onclick="window.location='{{ route('product.show', $product->id) }}'"
                @endif>

                <div class="relative overflow-hidden aspect-[4/3] sm:aspect-auto sm:h-[200px]">
                    @if($product->image && Str::startsWith($product->image, 'products/'))
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-[400ms] {{ $product->status === 'habis' ? 'grayscale brightness-50' : 'hover:scale-[1.06]' }}">
                    @else
                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-[400ms] {{ $product->status === 'habis' ? 'grayscale brightness-50' : 'hover:scale-[1.06]' }}">
                    @endif
                    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 flex gap-1 sm:gap-1.5 flex-wrap">
                        <span class="bg-white/90 backdrop-blur-sm text-maroon text-[0.6rem] sm:text-[0.72rem] font-extrabold px-2 sm:px-3 py-0.5 sm:py-1 rounded-[20px] tracking-[0.5px]">
                            {{ $product->categoryRelation->name ?? $product->category }}
                        </span>
                        @if($product->badge)
                        <span class="px-2 sm:px-[12px] py-0.5 sm:py-[5px] rounded-[20px] text-[0.6rem] sm:text-[0.72rem] font-extrabold tracking-[1px] uppercase text-white backdrop-blur-sm
                            {{ $product->badge == 'new' ? 'bg-blue-700/90' : ($product->badge == 'terlaris' ? 'bg-orange-700/90' : 'bg-maroon/90') }}">
                            {{ $product->badge == 'new' ? 'New' : ($product->badge == 'terlaris' ? 'Terlaris' : 'Unggulan') }}
                        </span>
                        @endif
                    </div>
                    <div class="absolute bottom-2 right-2">
                        @if($product->status === 'habis')
                            <span class="bg-red-800/90 backdrop-blur-sm text-white text-[0.6rem] sm:text-[0.68rem] font-bold px-2 py-0.5 rounded-[20px]">Habis</span>
                        @else
                            <span class="bg-white/90 backdrop-blur-sm text-maroon text-[0.6rem] sm:text-[0.68rem] font-bold px-2 py-0.5 rounded-[20px]">Stok: {{ $product->stock }}</span>
                        @endif
                    </div>
                </div>

                <div class="p-3 sm:p-[18px]">
                    <span class="inline-flex items-center gap-1 text-[0.6rem] sm:text-[0.7rem] px-2 sm:px-[10px] py-[2px] sm:py-[3px] rounded-[20px] font-bold mb-1.5 {{ $product->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-pink-100 text-red-800' }}">
                        ● @if($product->status === 'ready') Ready Stock @else Habis @endif
                    </span>
                    <h3 class="font-extrabold text-[#1a1a1a] text-[0.82rem] sm:text-[0.97rem] mb-1 sm:mb-1.5 line-clamp-2">{{ $product->name }}</h3>
                    <p class="hidden sm:block text-[0.82rem] text-[#aaa] leading-[1.5] mb-[14px] line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                    <div class="flex justify-between items-center mt-2 sm:mt-0">
                        <span class="font-extrabold text-maroon text-[0.88rem] sm:text-base">Rp{{ number_format($product->price, 0, ',', '.') }}</span>

                        @if($product->status === 'habis')
                            <button class="bg-[#ccc] text-white border-none rounded-[10px] w-8 h-8 sm:w-[38px] sm:h-[38px] flex items-center justify-center text-[0.8rem] cursor-not-allowed pointer-events-none" onclick="event.stopPropagation()">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        @elseif(auth()->check())
                            <button class="bg-maroon text-white border-none rounded-[10px] w-8 h-8 sm:w-[38px] sm:h-[38px] flex items-center justify-center text-[0.8rem] cursor-pointer transition-all duration-200 hover:bg-maroon-dark hover:scale-110" onclick="event.stopPropagation(); window.location='{{ route('product.show', $product->id) }}'">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="bg-maroon text-white no-underline rounded-[10px] w-8 h-8 sm:w-[38px] sm:h-[38px] flex items-center justify-center text-[0.8rem] transition-all duration-200 hover:bg-maroon-dark hover:scale-110" onclick="event.stopPropagation()">
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
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 700,
        easing: 'ease-out-cubic',
        once: true,
        offset: 60,
    });
</script>
<script>
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    window.scrollTo(0, 0);

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
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    searchInput.addEventListener('input', filterProducts);
    sortSelect.addEventListener('change', filterProducts);

    // Mobile filter toggle
    const filterToggle = document.getElementById('filter-toggle');
    const filterSidebar = document.getElementById('filter-sidebar');
    if (filterToggle) {
        filterToggle.addEventListener('click', () => {
            filterSidebar.classList.toggle('hidden');
        });
    }
</script>
@endpush
