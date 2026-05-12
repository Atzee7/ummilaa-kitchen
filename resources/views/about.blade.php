@extends('layouts.app')

@push('styles')
<style>
/* Visi/Misi card animated top-border on hover — requires ::before pseudo-element */
.visimisi-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 4px; background: linear-gradient(90deg, #8B1A1A, #c0392b);
    border-radius: 28px 28px 0 0; transform: scaleX(0); transform-origin: left;
    transition: transform 0.3s ease;
}
.visimisi-card:hover::before { transform: scaleX(1); }
</style>
@endpush

@section('content')

{{-- ========== HERO ========== --}}
<section class="relative overflow-hidden bg-[linear-gradient(135deg,#8B1A1A_0%,#5a0e0e_60%,#3a0808_100%)] px-[80px] pt-[100px] pb-[80px] text-white">
    <div class="absolute -right-[150px] -top-[150px] w-[600px] h-[600px] rounded-full bg-white/[0.04] pointer-events-none"></div>
    <div class="absolute -left-[80px] -bottom-[120px] w-[400px] h-[400px] rounded-full bg-white/[0.03] pointer-events-none"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-[50px] items-center relative z-[1] max-w-[1200px] mx-auto">
        <div>
            <div class="inline-flex items-center gap-2 bg-white/[0.15] backdrop-blur-[6px] text-[#ffd9d9] px-5 py-2 rounded-[30px] text-[0.78rem] font-bold tracking-[1.8px] uppercase mb-6 border border-white/[0.15]">
                <i class="fas fa-heart"></i> Tentang Kami
            </div>
            <h1 class="font-playfair text-[2rem] md:text-[2.5rem] lg:text-[3rem] leading-[1.2] mb-5 tracking-[-0.5px]">Dapur Penuh <em class="italic text-[#ffcccc]">Cinta</em>, Rasa yang Tak Terlupakan</h1>
            <p class="opacity-85 text-[1rem] leading-[1.9] max-w-[480px] mb-9">Ummilaa Kitchen hadir untuk memudahkan Anda mendapatkan makanan lezat dan fresh. Dari camilan harian hingga catering acara spesial, semua kami siapkan dengan penuh dedikasi.</p>
            <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-[10px] bg-white text-maroon px-7 py-[13px] rounded-[50px] font-extrabold text-[0.92rem] no-underline transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_14px_32px_rgba(0,0,0,0.25)] shadow-[0_8px_24px_rgba(0,0,0,0.2)]">
                <i class="fas fa-utensils"></i> Lihat Menu Kami
            </a>
        </div>
        <div class="flex justify-center">
            <div class="relative w-full max-w-[440px]">
                <div class="absolute -top-5 -right-5 w-[120px] h-[120px] rounded-xl pointer-events-none" style="background-image: radial-gradient(rgba(255,255,255,0.3) 1.5px, transparent 1.5px); background-size: 12px 12px;"></div>
                <img class="w-full h-[260px] md:h-[340px] lg:h-[400px] object-cover rounded-[20px] shadow-[0_20px_50px_rgba(0,0,0,0.1)]" src="{{ asset('images/dapur.jpeg') }}" alt="Dapur Ummilaa Kitchen">
                <div class="absolute -bottom-5 -left-5 bg-white text-maroon px-[22px] py-4 rounded-[18px] shadow-[0_8px_32px_rgba(0,0,0,0.15)] flex items-center gap-3">
                    <div class="w-11 h-11 bg-maroon-100 rounded-xl flex items-center justify-center text-[1.3rem] text-maroon flex-shrink-0">
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <strong class="block text-[1.1rem] text-[#1a1a1a]">Sejak 2019</strong>
                        <span class="text-[0.75rem] text-[#999]">Melayani dengan sepenuh hati</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== STORY ========== --}}
<section class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8 lg:gap-[60px] px-5 md:px-10 lg:px-[80px] py-10 lg:py-[80px] min-h-0 lg:min-h-[90vh] bg-white relative overflow-hidden">
    <div class="relative">
        <img class="w-full h-[280px] md:h-[400px] lg:h-[500px] object-cover rounded-[24px] shadow-[0_30px_70px_rgba(139,26,26,0.2)]" src="{{ asset('images/dapur.jpeg') }}" alt="Dapur Ummilaa Kitchen">
        <div class="absolute top-8 -left-6 bg-maroon text-white px-6 py-[18px] rounded-[20px] text-center shadow-[0_12px_32px_rgba(139,26,26,0.35)]">
            <strong class="block font-playfair text-[2rem]">2019</strong>
            <span class="text-[0.72rem] opacity-85 tracking-[1px] uppercase">Berdiri</span>
        </div>
        <div class="absolute -bottom-7 -right-7 bg-white rounded-[20px] px-[22px] py-[18px] shadow-[0_12px_40px_rgba(0,0,0,0.12)] flex items-center gap-[14px]">
            <div class="w-12 h-12 bg-maroon-100 rounded-[14px] flex items-center justify-center text-maroon text-[1.4rem] flex-shrink-0">
                <i class="fas fa-fire-alt"></i>
            </div>
            <div>
                <strong class="block text-[#1a1a1a] text-[0.95rem]">Dibuat Fresh</strong>
                <span class="text-[#bbb] text-[0.78rem]">Setiap hari</span>
            </div>
        </div>
    </div>
    <div>
        <div class="flex items-center gap-2 text-[0.78rem] font-bold tracking-[2.5px] uppercase text-maroon mb-[14px]">
            <span class="inline-block w-6 h-0.5 bg-maroon rounded-sm"></span> Cerita Kami
        </div>
        <h2 class="font-playfair text-[2.4rem] text-[#1a1a1a] mb-[22px] leading-[1.3]">Berawal dari Dapur Rumahan, Kini Melayani Ratusan Pelanggan</h2>
        <p class="text-[#777] leading-[1.9] mb-[18px] text-[0.97rem]">Ummilaa Kitchen lahir dari kecintaan terhadap dunia kuliner dan semangat untuk menghadirkan makanan berkualitas dengan harga terjangkau bagi masyarakat Malang dan sekitarnya.</p>
        <p class="text-[#777] leading-[1.9] mb-[18px] text-[0.97rem]">Berawal dari dapur kecil di rumah, kami terus berkembang dan kini menawarkan berbagai produk mulai dari dimsum, crunchy series, perpentolan, risol, hingga layanan catering untuk berbagai acara.</p>
        <p class="text-[#777] leading-[1.9] mb-[18px] text-[0.97rem]">Setiap produk dibuat dengan bahan-bahan pilihan, dimasak dengan higienis, dan dikemas dengan baik agar sampai ke tangan pelanggan dalam kondisi terbaik.</p>
        <div class="flex gap-5 mt-8 flex-wrap">
            <div class="flex items-center gap-2 bg-[#fdf5f5] border border-[#f0e0e0] text-maroon px-[18px] py-[9px] rounded-[50px] text-[0.85rem] font-bold">
                <i class="fas fa-leaf"></i> Bahan Segar
            </div>
            <div class="flex items-center gap-2 bg-[#fdf5f5] border border-[#f0e0e0] text-maroon px-[18px] py-[9px] rounded-[50px] text-[0.85rem] font-bold">
                <i class="fas fa-shield-alt"></i> Higienis
            </div>
            <div class="flex items-center gap-2 bg-[#fdf5f5] border border-[#f0e0e0] text-maroon px-[18px] py-[9px] rounded-[50px] text-[0.85rem] font-bold">
                <i class="fas fa-truck"></i> Pengiriman Cepat
            </div>
        </div>
    </div>
</section>

{{-- ========== VISI MISI ========== --}}
<section class="bg-[linear-gradient(135deg,#fdf5f5_0%,#fff5f0_100%)] px-[80px] py-[100px]">
    <div class="text-center mb-16">
        <div class="flex items-center justify-center gap-2 text-[0.78rem] font-bold tracking-[2.5px] uppercase text-maroon mb-2">
            <span class="inline-block w-6 h-0.5 bg-maroon rounded-sm"></span> Arah & Tujuan
        </div>
        <h2 class="font-playfair text-[1.6rem] md:text-[2rem] lg:text-[2.2rem] text-[#1a1a1a]">Visi & <span class="text-maroon">Misi</span></h2>
        <p class="text-[#999] text-[0.95rem] mt-[10px]">Komitmen kami dalam menghadirkan yang terbaik untuk setiap pelanggan</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-[1000px] mx-auto">
        <div class="visimisi-card bg-white rounded-[28px] p-11 shadow-[0_8px_40px_rgba(139,26,26,0.07)] border border-[#f5eded] transition-all duration-[250ms] hover:-translate-y-[6px] hover:shadow-[0_24px_60px_rgba(139,26,26,0.13)] relative overflow-hidden">
            <div class="w-16 h-16 bg-[linear-gradient(135deg,#fdf0f0,#ffe8e8)] rounded-[18px] flex items-center justify-center text-maroon text-[1.6rem] mb-[26px] shadow-[0_4px_16px_rgba(139,26,26,0.1)]">
                <i class="fas fa-eye"></i>
            </div>
            <h3 class="font-playfair text-[1.6rem] text-[#1a1a1a] mb-4">Visi</h3>
            <p class="text-[#777] leading-[1.85] text-[0.97rem]">Menjadi usaha kuliner UMKM terpercaya di Malang yang dikenal atas kualitas rasa, kebersihan, dan pelayanan terbaik kepada setiap pelanggan.</p>
        </div>
        <div class="visimisi-card bg-white rounded-[28px] p-11 shadow-[0_8px_40px_rgba(139,26,26,0.07)] border border-[#f5eded] transition-all duration-[250ms] hover:-translate-y-[6px] hover:shadow-[0_24px_60px_rgba(139,26,26,0.13)] relative overflow-hidden">
            <div class="w-16 h-16 bg-[linear-gradient(135deg,#fdf0f0,#ffe8e8)] rounded-[18px] flex items-center justify-center text-maroon text-[1.6rem] mb-[26px] shadow-[0_4px_16px_rgba(139,26,26,0.1)]">
                <i class="fas fa-bullseye"></i>
            </div>
            <h3 class="font-playfair text-[1.6rem] text-[#1a1a1a] mb-4">Misi</h3>
            <ul class="list-none m-0 mt-1">
                @foreach([
                    'Menyajikan produk kuliner berkualitas dengan bahan-bahan pilihan yang segar dan higienis.',
                    'Memberikan kemudahan akses informasi produk dan pemesanan melalui platform digital.',
                    'Menghadirkan pelayanan yang ramah, cepat, dan memuaskan untuk setiap pelanggan.',
                    'Terus berinovasi dalam menu dan layanan demi memenuhi kebutuhan pelanggan.',
                ] as $misi)
                <li class="flex items-start gap-[13px] text-[#666] text-[0.95rem] leading-[1.75] mb-4 pb-4 border-b border-[#f8f0f0] last:mb-0 last:pb-0 last:border-0">
                    <div class="w-[22px] h-[22px] bg-[linear-gradient(135deg,#8B1A1A,#c0392b)] rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-check text-white text-[0.65rem]"></i>
                    </div>
                    {{ $misi }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- ========== TESTIMONI MARQUEE ========== --}}
<section class="px-5 md:px-10 lg:px-[80px] py-10 lg:py-[80px] bg-[linear-gradient(180deg,#fdf5f5_0%,#fff_100%)] overflow-hidden">
    <div class="text-center mb-14 px-[80px]">
        <div class="flex items-center justify-center gap-2 text-[0.78rem] font-bold tracking-[2.5px] uppercase text-maroon mb-2">
            <span class="inline-block w-6 h-0.5 bg-maroon rounded-sm"></span> Apa Kata Mereka
        </div>
        <h2 class="font-playfair text-[1.6rem] md:text-[2rem] lg:text-[2.2rem] text-[#1a1a1a]">Cerita <span class="text-maroon">Kami</span></h2>
        <p class="text-[#999] mt-[10px] text-[0.95rem]">Kepuasan pelanggan adalah prioritas utama kami</p>
    </div>

    @if($testimonials->isEmpty())
        <div class="text-center px-10 py-[60px] text-[#bbb] text-[0.95rem]">
            <i class="fas fa-comment-slash text-[2.5rem] mb-[14px] block text-[#e0cccc]"></i>
            Belum ada testimoni yang disetujui.
        </div>
    @else
        <div class="relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-[160px] z-[2] pointer-events-none bg-[linear-gradient(to_right,#fdf5f5,transparent)]"></div>
            <div class="absolute right-0 top-0 bottom-0 w-[160px] z-[2] pointer-events-none bg-[linear-gradient(to_left,#fdf5f5,transparent)]"></div>
            <div class="flex gap-6 w-max py-5 pb-10 animate-marquee hover:[animation-play-state:paused]">
                @foreach([1,2] as $loop)
                    @foreach($testimonials as $t)
                    <div class="bg-white rounded-[24px] p-[34px] border-[1.5px] border-maroon-200 shadow-[0_6px_28px_rgba(139,26,26,0.07)] transition-all duration-300 relative w-[330px] flex-shrink-0 hover:-translate-y-2 hover:shadow-[0_20px_56px_rgba(139,26,26,0.13)] hover:border-[#e0cccc]">
                        <div class="h-px bg-gradient-to-r from-transparent via-maroon-200 to-transparent mx-5 md:mx-10 lg:mx-[80px]"></div>
                        <div class="absolute top-4 right-4 bg-maroon-100 text-maroon rounded-[20px] px-[10px] py-1 text-[0.75rem] font-bold">{{ $t->rating }}/5</div>
                        <div class="flex justify-between items-start mb-[18px]">
                            <div class="text-[0.9rem] tracking-[2px]">
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color: {{ $i <= $t->rating ? '#F5A623' : '#e0e0e0' }}">★</span>
                                @endfor
                            </div>
                            <div class="text-[2.8rem] text-[#f0e0e0] font-[Georgia,serif] leading-[0.8]">"</div>
                        </div>
                        <q class="text-[#555] text-[0.94rem] leading-[1.85] italic block mb-[26px] [quotes:none]">{{ $t->komentar }}</q>
                        <div class="flex items-center gap-[13px] pt-5 border-t border-[#f8f0f0]">
                            <div class="w-12 h-12 rounded-full bg-[linear-gradient(135deg,#8B1A1A,#c0392b)] flex items-center justify-center text-white font-extrabold text-[1.1rem] flex-shrink-0 shadow-[0_4px_12px_rgba(139,26,26,0.25)]">
                                {{ strtoupper(substr($t->nama, 0, 1)) }}
                            </div>
                            <div>
                                <strong class="block text-[#1a1a1a] text-[0.9rem]">{{ $t->nama }}</strong>
                                <span class="text-[#bbb] text-[0.78rem]">Pelanggan Setia</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif
</section>

@endsection
