<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Ummilaa Kitchen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex">

    {{-- Kiri: Branding --}}
    <div class="hidden lg:flex w-1/2 flex-col justify-center items-center text-white p-12 bg-[#8B1A1A]">
        <div class="text-center">
            <h1 class="font-playfair text-5xl font-bold mb-2">Ummilaa</h1>
            <p class="text-lg tracking-widest uppercase opacity-80 mb-10">Kitchen</p>
            <div class="w-20 h-1 bg-white opacity-30 mx-auto mb-10"></div>
            <p class="text-xl font-light opacity-90">Panel Admin</p>
            <p class="text-sm opacity-60 mt-2">Kelola toko Anda dengan mudah</p>
        </div>
    </div>

    {{-- Kanan: Form Login --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white">
        <div class="w-full max-w-md">

            {{-- Logo mobile --}}
            <div class="lg:hidden text-center mb-8">
                <h1 class="font-playfair text-3xl font-bold text-[#8B1A1A]">Ummilaa Kitchen</h1>
                <p class="text-gray-500 text-sm mt-1">Panel Admin</p>
            </div>

            <h2 class="font-playfair text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
            <p class="text-gray-500 mb-8">Masuk ke akun admin Anda</p>

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@ummilaakitchen.com"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[rgba(139,26,26,0.1)] focus:border-[#8B1A1A] text-sm"
                    >
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[rgba(139,26,26,0.1)] focus:border-[#8B1A1A] text-sm"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full py-3 rounded-xl text-white font-semibold text-sm tracking-wide transition hover:opacity-90 bg-[#8B1A1A]"
                >
                    Masuk ke Dashboard
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-8">
                © {{ date('Y') }} Ummilaa Kitchen. All rights reserved.
            </p>
        </div>
    </div>

</body>
</html>