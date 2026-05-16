<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ummilaa Kitchen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans text-[#333] bg-white">

{{-- NAVBAR --}}
<nav x-data="{ mobileMenu: false }" class="flex items-center justify-between px-5 md:px-10 lg:px-[60px] py-4 bg-white shadow-sm sticky top-0 z-[100]">
    <a href="{{ route('home') }}" class="font-playfair text-[1.4rem] lg:text-[1.6rem] font-bold text-maroon no-underline">
        Ummilaa
        <span class="text-[0.75rem] block text-[#666] font-sans font-medium tracking-[2px]">KITCHEN</span>
    </a>

    {{-- Desktop Nav Links --}}
    <div class="hidden lg:flex gap-9 items-center">
        <a href="{{ route('home') }}"
           class="font-bold text-base transition-colors duration-200 hover:text-maroon {{ request()->routeIs('home') ? 'text-maroon' : 'text-[#333]' }}">Home</a>
        <a href="{{ route('about') }}"
           class="font-bold text-base transition-colors duration-200 hover:text-maroon {{ request()->routeIs('about') ? 'text-maroon' : 'text-[#333]' }}">About Us</a>
        <a href="{{ route('catalogue') }}"
           class="font-bold text-base transition-colors duration-200 hover:text-maroon {{ request()->routeIs('catalogue') ? 'text-maroon' : 'text-[#333]' }}">Catalogue</a>
        <a href="{{ route('contact') }}"
           class="font-bold text-base transition-colors duration-200 hover:text-maroon {{ request()->routeIs('contact') ? 'text-maroon' : 'text-[#333]' }}">Contact Us</a>
    </div>

    {{-- Desktop Right Actions --}}
    <div class="hidden lg:flex items-center gap-5">
        @auth
        <a href="{{ route('cart') }}" class="relative text-[1.2rem] text-maroon cursor-pointer">
            <i class="fas fa-shopping-cart"></i>
            <span class="absolute top-[-8px] right-[-8px] bg-maroon text-white rounded-full w-[18px] h-[18px] text-[0.65rem] flex items-center justify-center font-bold">
                {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
            </span>
        </a>

        <div class="relative group">
            <button class="bg-maroon text-white px-6 py-[10px] rounded-lg font-bold flex items-center gap-2 text-[0.95rem] hover:bg-maroon-dark transition-colors duration-200 border-none cursor-pointer font-sans">
                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                <i class="fas fa-chevron-down text-[0.7rem] ml-0.5"></i>
            </button>
            <div class="absolute top-[calc(100%+10px)] right-0 bg-white rounded-[14px] min-w-[200px] shadow-[0_10px_40px_rgba(0,0,0,0.12)] border border-maroon-200 p-2 opacity-0 invisible -translate-y-2 transition-all duration-200 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 z-[200]">
                <a href="{{ route('orders') }}" class="flex items-center gap-[10px] px-[14px] py-[10px] rounded-[10px] text-[0.9rem] font-semibold text-[#444] hover:bg-maroon-100 hover:text-maroon transition-colors duration-150 no-underline">
                    <i class="fas fa-box w-4 text-maroon"></i> Pesanan Saya
                </a>
                <a href="{{ route('profile.user') }}" class="flex items-center gap-[10px] px-[14px] py-[10px] rounded-[10px] text-[0.9rem] font-semibold text-[#444] hover:bg-maroon-100 hover:text-maroon transition-colors duration-150 no-underline">
                    <i class="fas fa-user-edit w-4 text-maroon"></i> Profile
                </a>
                <div class="h-px bg-maroon-200 my-[6px]"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-[10px] px-[14px] py-[10px] rounded-[10px] text-[0.9rem] font-semibold text-[#444] hover:bg-maroon-100 hover:text-maroon transition-colors duration-150 w-full text-left bg-transparent border-none cursor-pointer font-sans">
                        <i class="fas fa-sign-out-alt w-4 text-maroon"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        @else
        <a href="{{ route('login') }}" class="bg-maroon text-white px-6 py-[10px] rounded-lg font-bold flex items-center gap-2 text-[0.95rem] hover:bg-maroon-dark transition-colors duration-200 no-underline">
            <i class="fas fa-user"></i> Login
        </a>
        @endauth
    </div>

    {{-- Mobile Right: Cart + Hamburger --}}
    <div class="flex items-center gap-4 lg:hidden">
        @auth
        <a href="{{ route('cart') }}" class="relative text-[1.1rem] text-maroon">
            <i class="fas fa-shopping-cart"></i>
            <span class="absolute top-[-8px] right-[-8px] bg-maroon text-white rounded-full w-[16px] h-[16px] text-[0.6rem] flex items-center justify-center font-bold">
                {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
            </span>
        </a>
        @endauth
        <button @click="mobileMenu = !mobileMenu" class="text-[1.3rem] text-[#333] bg-transparent border-none cursor-pointer p-1">
            <i x-show="!mobileMenu" class="fas fa-bars"></i>
            <i x-show="mobileMenu" class="fas fa-times" x-cloak></i>
        </button>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
         @click.away="mobileMenu = false"
         class="absolute top-full left-0 right-0 bg-white shadow-[0_10px_40px_rgba(0,0,0,0.1)] border-t border-maroon-200 z-[200] lg:hidden" x-cloak>
        <div class="flex flex-col px-5 py-4 gap-1">
            <a href="{{ route('home') }}" class="py-3 px-4 rounded-xl font-bold text-[0.95rem] no-underline transition-colors {{ request()->routeIs('home') ? 'bg-maroon-100 text-maroon' : 'text-[#333] hover:bg-maroon-50 hover:text-maroon' }}">
                <i class="fas fa-home w-5 mr-2 text-maroon"></i> Home
            </a>
            <a href="{{ route('about') }}" class="py-3 px-4 rounded-xl font-bold text-[0.95rem] no-underline transition-colors {{ request()->routeIs('about') ? 'bg-maroon-100 text-maroon' : 'text-[#333] hover:bg-maroon-50 hover:text-maroon' }}">
                <i class="fas fa-info-circle w-5 mr-2 text-maroon"></i> About Us
            </a>
            <a href="{{ route('catalogue') }}" class="py-3 px-4 rounded-xl font-bold text-[0.95rem] no-underline transition-colors {{ request()->routeIs('catalogue') ? 'bg-maroon-100 text-maroon' : 'text-[#333] hover:bg-maroon-50 hover:text-maroon' }}">
                <i class="fas fa-utensils w-5 mr-2 text-maroon"></i> Catalogue
            </a>
            <a href="{{ route('contact') }}" class="py-3 px-4 rounded-xl font-bold text-[0.95rem] no-underline transition-colors {{ request()->routeIs('contact') ? 'bg-maroon-100 text-maroon' : 'text-[#333] hover:bg-maroon-50 hover:text-maroon' }}">
                <i class="fas fa-envelope w-5 mr-2 text-maroon"></i> Contact Us
            </a>

            <div class="h-px bg-maroon-200 my-2"></div>

            @auth
            <a href="{{ route('orders') }}" class="py-3 px-4 rounded-xl font-bold text-[0.95rem] no-underline text-[#333] hover:bg-maroon-50 hover:text-maroon transition-colors">
                <i class="fas fa-box w-5 mr-2 text-maroon"></i> Pesanan Saya
            </a>
            <a href="{{ route('profile.user') }}" class="py-3 px-4 rounded-xl font-bold text-[0.95rem] no-underline text-[#333] hover:bg-maroon-50 hover:text-maroon transition-colors">
                <i class="fas fa-user-edit w-5 mr-2 text-maroon"></i> Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold text-[0.95rem] text-[#333] hover:bg-maroon-50 hover:text-maroon transition-colors bg-transparent border-none cursor-pointer font-sans text-left">
                    <i class="fas fa-sign-out-alt w-5 mr-2 text-maroon"></i> Logout
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="py-3 px-4 rounded-xl font-bold text-[0.95rem] bg-maroon text-white text-center no-underline hover:bg-maroon-dark transition-colors mt-1">
                <i class="fas fa-user mr-2"></i> Login
            </a>
            @endauth
        </div>
    </div>
</nav>

@auth
    @php
        $activeOrders = \App\Models\Order::where('user_id', Auth::id())
            ->whereIn('status', ['dikirim', 'siap_diambil'])
            ->latest()
            ->get();
    @endphp
    @foreach ($activeOrders as $notifOrder)
        @if ($notifOrder->status === 'dikirim')
            <div class="w-full bg-blue-600 text-white px-5 md:px-10 lg:px-[60px] py-3 flex items-center justify-between gap-4 text-sm font-semibold">
                <div class="flex items-center gap-3">
                    <i class="fas fa-truck text-lg"></i>
                    <span>Pesanan <span class="font-bold">#{{ $notifOrder->id }}</span> sedang dalam perjalanan ke alamat Anda!</span>
                </div>
                <a href="{{ route('orders.show', $notifOrder->id) }}" class="shrink-0 bg-white text-blue-600 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-50 transition-colors no-underline">
                    Lihat Detail
                </a>
            </div>
        @elseif ($notifOrder->status === 'siap_diambil')
            <div class="w-full bg-orange-500 text-white px-5 md:px-10 lg:px-[60px] py-3 flex items-center justify-between gap-4 text-sm font-semibold">
                <div class="flex items-center gap-3">
                    <i class="fas fa-store text-lg"></i>
                    <span>Pesanan <span class="font-bold">#{{ $notifOrder->id }}</span> sudah siap diambil! Silahkan datang ke toko.</span>
                </div>
                <a href="{{ route('orders.show', $notifOrder->id) }}" class="shrink-0 bg-white text-orange-500 px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-orange-50 transition-colors no-underline">
                    Lihat Detail
                </a>
            </div>
        @endif
    @endforeach
@endauth

@yield('content')

{{-- FOOTER --}}
<footer class="bg-maroon-dark text-white pt-10 px-5 md:px-10 lg:px-[60px] pb-5 mt-20">
    <div class="grid grid-cols-1 md:grid-cols-[2fr_1fr_1fr] gap-10 mb-[30px]">
        <div>
            <div class="font-playfair text-[1.6rem] font-bold text-white">
                Ummilaa
                <span class="text-[0.75rem] block font-sans font-medium tracking-[2px] text-white/70">KITCHEN</span>
            </div>
            <p class="mt-3 text-[0.9rem] opacity-80 leading-[1.7]">Sajian kuliner lezat & fresh untuk berbagai kebutuhan Anda. Dimsum, risol, catering, dan masih banyak lagi.</p>
        </div>
        <div>
            <h4 class="text-base font-bold mb-[14px]">Menu</h4>
            <a href="{{ route('home') }}" class="block text-[0.9rem] opacity-80 mb-2 hover:opacity-100 transition-opacity no-underline text-white">Home</a>
            <a href="{{ route('catalogue') }}" class="block text-[0.9rem] opacity-80 mb-2 hover:opacity-100 transition-opacity no-underline text-white">Catalogue</a>
            <a href="{{ route('about') }}" class="block text-[0.9rem] opacity-80 mb-2 hover:opacity-100 transition-opacity no-underline text-white">About Us</a>
            <a href="{{ route('contact') }}" class="block text-[0.9rem] opacity-80 mb-2 hover:opacity-100 transition-opacity no-underline text-white">Contact</a>
        </div>
        <div>
            <h4 class="text-base font-bold mb-[14px]">Kontak</h4>
            <a href="#" class="block text-[0.9rem] opacity-80 mb-2 hover:opacity-100 transition-opacity no-underline text-white"><i class="fab fa-instagram mr-1"></i> @ummilaakitchen</a>
            <a href="#" class="block text-[0.9rem] opacity-80 mb-2 hover:opacity-100 transition-opacity no-underline text-white"><i class="fab fa-whatsapp mr-1"></i> WhatsApp</a>
        </div>
    </div>
    <div class="border-t border-white/20 pt-4 text-center text-[0.85rem] opacity-70">© 2025 Ummilaa Kitchen. All rights reserved.</div>
</footer>

{{-- WA BUTTON --}}
<a href="#" class="fixed bottom-6 right-6 bg-maroon text-white w-14 h-14 lg:w-16 lg:h-16 rounded-full flex items-center justify-center text-[1.6rem] lg:text-[1.9rem] shadow-[0_4px_14px_rgba(0,0,0,0.25)] z-[999] hover:scale-110 transition-transform duration-200 no-underline">
    <i class="fab fa-whatsapp"></i>
</a>

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectors = ['div.bg-green-100'];
        const flashes = document.querySelectorAll(selectors.join(','));

        flashes.forEach(function (el) {
            if (el.hasAttribute('data-permanent')) return;
            // Siapkan styling untuk animasi
            el.style.transition = 'opacity 0.5s ease, max-height 0.5s ease, padding 0.5s ease, margin 0.5s ease';
            el.style.overflow = 'hidden';
            el.style.position = 'relative';

            // Tombol tutup ×
            const btn = document.createElement('button');
            btn.innerHTML = '&times;';
            btn.style.cssText = 'position:absolute;top:50%;right:14px;transform:translateY(-50%);background:none;border:none;font-size:1.2rem;line-height:1;cursor:pointer;opacity:0.5;padding:0;';
            btn.addEventListener('click', function () { dismiss(el); });
            el.appendChild(btn);

            // Auto dismiss setelah 3 detik
            setTimeout(function () { dismiss(el); }, 3000);
        });

        function dismiss(el) {
            el.style.opacity = '0';
            el.style.maxHeight = '0';
            el.style.paddingTop = '0';
            el.style.paddingBottom = '0';
            el.style.marginBottom = '0';
            setTimeout(function () { el.remove(); }, 500);
        }
    });
</script>
</body>
</html>
