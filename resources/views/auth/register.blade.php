<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Ummilaa Kitchen</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans min-h-screen flex flex-col md:flex-row bg-white">

{{-- KIRI --}}
<div class="hidden md:flex md:w-[45%] bg-[linear-gradient(160deg,#8B1A1A_0%,#4a0c0c_100%)] flex-col justify-between p-[50px] relative overflow-hidden">
    <div class="absolute bottom-[-60px] left-[-40px] w-[500px] h-[300px] bg-white/[0.05] rounded-full rotate-[-10deg]"></div>

    <div class="font-playfair text-[1.6rem] text-white font-bold relative z-[1]">
        Ummilaa
        <span class="block text-[0.7rem] font-sans tracking-[3px] opacity-70 mt-0.5">KITCHEN</span>
    </div>

    <div class="text-white relative z-[1]">
        <h1 class="font-playfair text-[2.4rem] leading-[1.3] mb-4">Bergabung & Nikmati Kemudahannya</h1>
        <p class="opacity-80 text-[0.95rem] leading-[1.7]">Daftar sekarang dan dapatkan akses penuh ke katalog produk, pemesanan, dan riwayat transaksi.</p>
        <div class="mt-8 flex flex-col gap-[14px]">
            <div class="flex items-center gap-3 text-white text-[0.9rem]">
                <div class="w-9 h-9 bg-white/15 rounded-full flex items-center justify-center text-[0.85rem] flex-shrink-0"><i class="fas fa-gift"></i></div>
                Akses katalog lengkap semua produk
            </div>
            <div class="flex items-center gap-3 text-white text-[0.9rem]">
                <div class="w-9 h-9 bg-white/15 rounded-full flex items-center justify-center text-[0.85rem] flex-shrink-0"><i class="fas fa-truck"></i></div>
                Pesan catering & frozen food mudah
            </div>
            <div class="flex items-center gap-3 text-white text-[0.9rem]">
                <div class="w-9 h-9 bg-white/15 rounded-full flex items-center justify-center text-[0.85rem] flex-shrink-0"><i class="fas fa-star"></i></div>
                Pantau status pesanan real-time
            </div>
        </div>
    </div>

    <div class="text-white/40 text-[0.8rem] relative z-[1]">© 2025 Ummilaa Kitchen</div>
</div>

{{-- KANAN --}}
<div class="w-full md:w-[55%] bg-white flex items-center justify-center px-6 py-10 sm:px-10 sm:py-12 md:p-[60px] min-h-screen">
    <div class="w-full max-w-[360px]">

        {{-- Logo mobile only --}}
        <div class="md:hidden mb-8">
            <a href="{{ route('home') }}" class="no-underline">
                <span class="font-playfair text-[1.5rem] text-maroon font-bold">Ummilaa</span>
                <span class="block text-[0.58rem] tracking-[3px] text-[#bbb] uppercase mt-0.5">Kitchen</span>
            </a>
        </div>

        <h2 class="font-playfair text-[1.6rem] md:text-[2rem] text-[#1a1a1a] mb-1">Buat Akun Baru</h2>
        <p class="text-[#aaa] text-[0.82rem] md:text-[0.9rem] mb-6 md:mb-8">Isi data diri Anda untuk mulai berbelanja</p>

        @if($errors->any())
        <div class="bg-[#fce4ec] text-[#c62828] px-4 py-3 rounded-[10px] text-[0.85rem] mb-4 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="block font-bold text-[0.75rem] text-[#888] mb-1.5 tracking-[0.5px] uppercase">Nama Lengkap</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-[#ccc] text-[0.82rem]"></i>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required autofocus
                        class="w-full py-[11px] pr-4 pl-10 border border-[#e8e8e8] rounded-xl text-[0.9rem] font-sans text-[#333] bg-[#fafafa] transition-all duration-200 focus:outline-none focus:border-maroon focus:bg-white focus:shadow-[0_0_0_3px_rgba(139,26,26,0.07)]">
                </div>
            </div>
            <div class="mb-3">
                <label class="block font-bold text-[0.75rem] text-[#888] mb-1.5 tracking-[0.5px] uppercase">Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-[#ccc] text-[0.82rem]"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required
                        class="w-full py-[11px] pr-4 pl-10 border border-[#e8e8e8] rounded-xl text-[0.9rem] font-sans text-[#333] bg-[#fafafa] transition-all duration-200 focus:outline-none focus:border-maroon focus:bg-white focus:shadow-[0_0_0_3px_rgba(139,26,26,0.07)]">
                </div>
            </div>
            <div class="mb-3">
                <label class="block font-bold text-[0.75rem] text-[#888] mb-1.5 tracking-[0.5px] uppercase">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-[#ccc] text-[0.82rem]"></i>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" required
                        class="w-full py-[11px] pr-4 pl-10 border border-[#e8e8e8] rounded-xl text-[0.9rem] font-sans text-[#333] bg-[#fafafa] transition-all duration-200 focus:outline-none focus:border-maroon focus:bg-white focus:shadow-[0_0_0_3px_rgba(139,26,26,0.07)]">
                </div>
            </div>
            <div class="mb-5">
                <label class="block font-bold text-[0.75rem] text-[#888] mb-1.5 tracking-[0.5px] uppercase">Konfirmasi Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-[#ccc] text-[0.82rem]"></i>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password Anda" required
                        class="w-full py-[11px] pr-4 pl-10 border border-[#e8e8e8] rounded-xl text-[0.9rem] font-sans text-[#333] bg-[#fafafa] transition-all duration-200 focus:outline-none focus:border-maroon focus:bg-white focus:shadow-[0_0_0_3px_rgba(139,26,26,0.07)]">
                </div>
            </div>
            <button type="submit"
                class="w-full py-[12px] bg-maroon text-white border-none rounded-xl text-[0.9rem] font-bold font-sans cursor-pointer transition-all duration-200 flex items-center justify-center gap-2 hover:bg-maroon-dark hover:shadow-[0_4px_16px_rgba(139,26,26,0.25)]">
                <i class="fas fa-user-plus text-[0.85rem]"></i> Daftar Sekarang
            </button>
        </form>

        <div class="flex items-center gap-3 my-5 text-[#ddd] text-[0.78rem] before:flex-1 before:h-px before:bg-[#f0f0f0] after:flex-1 after:h-px after:bg-[#f0f0f0]">
            atau
        </div>

        <div class="text-center text-[0.85rem] text-[#aaa]">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-maroon font-bold no-underline">Masuk di sini</a>
        </div>

    </div>
</div>

<a href="#" class="fixed bottom-6 right-6 bg-maroon text-white w-[48px] h-[48px] rounded-full flex items-center justify-center text-[1.3rem] shadow-[0_4px_14px_rgba(0,0,0,0.15)] no-underline hover:scale-110 transition-transform duration-200">
    <i class="fab fa-whatsapp"></i>
</a>

</body>
</html>
