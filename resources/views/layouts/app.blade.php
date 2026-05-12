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
<nav class="flex items-center justify-between px-[60px] py-4 bg-white shadow-sm sticky top-0 z-[100]">
    <a href="{{ route('home') }}" class="font-playfair text-[1.6rem] font-bold text-maroon no-underline">
        Ummilaa
        <span class="text-[0.75rem] block text-[#666] font-sans font-medium tracking-[2px]">KITCHEN</span>
    </a>

    <div class="flex gap-9 items-center">
        <a href="{{ route('home') }}"
           class="font-bold text-base transition-colors duration-200 hover:text-maroon {{ request()->routeIs('home') ? 'text-maroon' : 'text-[#333]' }}">Home</a>
        <a href="{{ route('about') }}"
           class="font-bold text-base transition-colors duration-200 hover:text-maroon {{ request()->routeIs('about') ? 'text-maroon' : 'text-[#333]' }}">About Us</a>
        <a href="{{ route('catalogue') }}"
           class="font-bold text-base transition-colors duration-200 hover:text-maroon {{ request()->routeIs('catalogue') ? 'text-maroon' : 'text-[#333]' }}">Catalogue</a>
        <a href="{{ route('contact') }}"
           class="font-bold text-base transition-colors duration-200 hover:text-maroon {{ request()->routeIs('contact') ? 'text-maroon' : 'text-[#333]' }}">Contact Us</a>
    </div>

    <div class="flex items-center gap-5">
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
</nav>

@yield('content')

{{-- FOOTER --}}
<footer class="bg-maroon-dark text-white pt-10 px-[60px] pb-5 mt-20">
    <div class="grid grid-cols-[2fr_1fr_1fr] gap-10 mb-[30px]">
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
<a href="#" class="fixed bottom-6 right-6 bg-maroon text-white w-16 h-16 rounded-full flex items-center justify-center text-[1.9rem] shadow-[0_4px_14px_rgba(0,0,0,0.25)] z-[999] hover:scale-110 transition-transform duration-200 no-underline">
    <i class="fab fa-whatsapp"></i>
</a>

@stack('scripts')
</body>
</html>
