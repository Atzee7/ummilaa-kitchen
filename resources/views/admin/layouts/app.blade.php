<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Ummilaa Kitchen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-['Nunito'] bg-[#f4f6f9]">

{{-- ══════════════ SIDEBAR ══════════════ --}}
<aside class="fixed top-0 left-0 w-[260px] min-h-screen bg-[#8B1A1A] flex flex-col z-50">

    {{-- Brand --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-white/[0.07]">
        <div class="w-9 h-9 bg-[#8B1A1A] rounded-xl flex items-center justify-center text-base">🍽️</div>
        <div>
            <h1 class="font-['Playfair_Display'] text-lg font-bold text-white m-0 leading-tight">Ummilaa</h1>
            <p class="text-[11px] text-white/40 m-0 tracking-widest uppercase">Kitchen — Admin</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

        @php
            $isActive = fn($route) => request()->routeIs($route) || request()->routeIs($route . '.*');
        @endphp

        {{-- UTAMA --}}
        <div class="text-[10px] font-bold tracking-[0.1em] uppercase text-white/55 px-3 pt-1 pb-1.5">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-sm font-semibold no-underline transition-all duration-200 relative
               {{ $isActive('admin.dashboard')
                   ? 'bg-black/20 text-white [&>svg]:opacity-100 before:content-[\'\'] before:absolute before:left-0 before:top-[20%] before:bottom-[20%] before:w-[3px] before:bg-white before:rounded-r-[3px]'
                   : 'text-white/55 hover:bg-white/[0.06] hover:text-white/90' }}">
            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- KATALOG --}}
        <div class="text-[10px] font-bold tracking-[0.1em] uppercase text-white/55 px-3 pt-4 pb-1.5">Katalog</div>

        <a href="{{ route('admin.products.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-sm font-semibold no-underline transition-all duration-200 relative
               {{ $isActive('admin.products')
                   ? 'bg-black/20 text-white [&>svg]:opacity-100 before:content-[\'\'] before:absolute before:left-0 before:top-[20%] before:bottom-[20%] before:w-[3px] before:bg-white before:rounded-r-[3px]'
                   : 'text-white/55 hover:bg-white/[0.06] hover:text-white/90' }}">
            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Produk
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-sm font-semibold no-underline transition-all duration-200 relative
               {{ $isActive('admin.categories')
                   ? 'bg-black/20 text-white [&>svg]:opacity-100 before:content-[\'\'] before:absolute before:left-0 before:top-[20%] before:bottom-[20%] before:w-[3px] before:bg-white before:rounded-r-[3px]'
                   : 'text-white/55 hover:bg-white/[0.06] hover:text-white/90' }}">
            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Kategori
        </a>

        {{-- TRANSAKSI --}}
        <div class="text-[10px] font-bold tracking-[0.1em] uppercase text-white/55 px-3 pt-4 pb-1.5">Transaksi</div>

        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-sm font-semibold no-underline transition-all duration-200 relative
               {{ $isActive('admin.orders')
                   ? 'bg-black/20 text-white [&>svg]:opacity-100 before:content-[\'\'] before:absolute before:left-0 before:top-[20%] before:bottom-[20%] before:w-[3px] before:bg-white before:rounded-r-[3px]'
                   : 'text-white/55 hover:bg-white/[0.06] hover:text-white/90' }}">
            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Pesanan
        </a>

        <a href="{{ route('admin.kasir.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-sm font-semibold no-underline transition-all duration-200 relative
               {{ $isActive('admin.kasir')
                   ? 'bg-black/20 text-white [&>svg]:opacity-100 before:content-[\'\'] before:absolute before:left-0 before:top-[20%] before:bottom-[20%] before:w-[3px] before:bg-white before:rounded-r-[3px]'
                   : 'text-white/55 hover:bg-white/[0.06] hover:text-white/90' }}">
            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Kasir
        </a>

        <a href="{{ route('admin.laporan.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-sm font-semibold no-underline transition-all duration-200 relative
               {{ $isActive('admin.laporan')
                   ? 'bg-black/20 text-white [&>svg]:opacity-100 before:content-[\'\'] before:absolute before:left-0 before:top-[20%] before:bottom-[20%] before:w-[3px] before:bg-white before:rounded-r-[3px]'
                   : 'text-white/55 hover:bg-white/[0.06] hover:text-white/90' }}">
            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Laporan
        </a>

        {{-- PENGGUNA --}}
        <div class="text-[10px] font-bold tracking-[0.1em] uppercase text-white/55 px-3 pt-4 pb-1.5">Pengguna</div>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-sm font-semibold no-underline transition-all duration-200 relative
               {{ $isActive('admin.users')
                   ? 'bg-black/20 text-white [&>svg]:opacity-100 before:content-[\'\'] before:absolute before:left-0 before:top-[20%] before:bottom-[20%] before:w-[3px] before:bg-white before:rounded-r-[3px]'
                   : 'text-white/55 hover:bg-white/[0.06] hover:text-white/90' }}">
            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Pengguna
        </a>

        <a href="{{ route('admin.testimonials.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-sm font-semibold no-underline transition-all duration-200 relative
               {{ $isActive('admin.testimonials')
                   ? 'bg-black/20 text-white [&>svg]:opacity-100 before:content-[\'\'] before:absolute before:left-0 before:top-[20%] before:bottom-[20%] before:w-[3px] before:bg-white before:rounded-r-[3px]'
                   : 'text-white/55 hover:bg-white/[0.06] hover:text-white/90' }}">
            <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            Testimoni
        </a>

    </nav>

    {{-- Footer user --}}
    <div class="px-3 py-3.5 border-t border-white/[0.07]">
        <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg mb-1.5">
            <div class="w-[34px] h-[34px] rounded-full bg-black/25 flex items-center justify-center text-sm font-black text-white shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="text-sm font-bold text-white leading-tight">{{ auth()->user()->name }}</div>
                <div class="text-[11px] text-white/40">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-2.5 w-full px-3 py-2.5 rounded-lg border-none bg-transparent text-white/45 text-sm font-semibold font-['Nunito'] cursor-pointer transition-all duration-200 text-left hover:bg-white/[0.06] hover:text-white/85">
                <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>

</aside>

{{-- ══════════════ MAIN ══════════════ --}}
<div class="ml-[260px] min-h-screen">

    {{-- Top bar --}}
    <div class="bg-white px-8 h-[60px] flex items-center justify-between border-b border-gray-100 sticky top-0 z-40">
        <span class="font-['Playfair_Display'] text-lg font-bold text-[#1e1e2d]">@yield('title', 'Dashboard')</span>
        <div class="flex items-center gap-3 text-sm text-gray-400">
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            <div class="w-8 h-8 rounded-full bg-[#8B1A1A] text-white flex items-center justify-center text-sm font-black">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    {{-- Page content --}}
    <div class="p-8">
        @if(session('success'))
            <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2.5">
                ✅ {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

</div>

@stack('scripts')
</body>
</html>