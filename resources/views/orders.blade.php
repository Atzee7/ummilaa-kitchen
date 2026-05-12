@extends('layouts.app')

@push('styles')
<style>
/* Star rating sibling selectors — not expressible in Tailwind */
.star-rating input { display: none; }
.star-rating label { font-size: 1.8rem; color: #ddd; cursor: pointer; transition: color 0.15s; }
.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label { color: #f59e0b; }
/* JS-toggled form visibility */
.testimoni-form-wrapper { display: none; }
.testimoni-form-wrapper.open { display: block; }
/* JS-toggled active tab */
.status-tab.active { background: #8B1A1A; color: #fff; border-color: #8B1A1A; }
</style>
@endpush

@section('content')
<div class="px-[80px] py-[60px] min-h-[70vh]">
    <div class="mb-10">
        <h1 class="font-playfair text-[2rem] text-[#1a1a1a]">Pesanan Saya</h1>
        <p class="text-[#999] mt-1.5 text-[0.9rem]">Pantau status dan riwayat pesanan Anda</p>
    </div>

    <div class="flex gap-2 mb-8 flex-wrap">
        <button class="status-tab active px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="semua">Semua</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="pending">Menunggu</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="diproses">Diproses</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="dikirim">Dikirim</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="selesai">Selesai</button>
        <button class="status-tab px-5 py-[9px] rounded-[25px] border-[1.5px] border-maroon-200 text-[0.85rem] font-bold cursor-pointer bg-white text-[#777] hover:border-maroon hover:text-maroon transition-all" data-status="dibatalkan">Dibatalkan</button>
    </div>

    @if($orders->isEmpty())
    <div class="text-center py-[80px] px-10 text-[#bbb]">
        <i class="fas fa-box-open text-[3.5rem] mb-5 block"></i>
        <h3 class="text-[1.2rem] text-[#999] mb-3">Belum ada pesanan</h3>
        <p>Yuk mulai pesan produk favorit dari Ummilaa Kitchen</p>
        <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 bg-maroon text-white px-7 py-[13px] rounded-xl font-bold text-[0.95rem] mt-2 no-underline hover:bg-maroon-dark transition-colors">
            <i class="fas fa-utensils"></i> Belanja Sekarang
        </a>
    </div>
    @else
    <div class="flex flex-col gap-5" id="ordersList">
        @foreach($orders as $order)
        <div class="bg-white border-[1.5px] border-maroon-200 rounded-[20px] overflow-hidden transition-all duration-200 hover:shadow-[0_8px_30px_rgba(139,26,26,0.08)] hover:-translate-y-0.5 order-card" data-status="{{ $order->status }}">

            {{-- HEADER --}}
            <div class="flex justify-between items-center px-6 py-[18px] bg-[#fafafa] border-b border-maroon-200">
                <div>
                    <div class="font-extrabold text-[#1a1a1a] text-[0.92rem]">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-[0.82rem] text-[#999] mt-[3px]"><i class="fas fa-calendar-alt mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-[14px] py-[6px] rounded-[20px] text-[0.78rem] font-extrabold
                    @if($order->status === 'pending') bg-amber-50 text-orange-700
                    @elseif($order->status === 'diproses') bg-blue-50 text-blue-800
                    @elseif($order->status === 'dikirim') bg-purple-50 text-purple-800
                    @elseif($order->status === 'selesai') bg-green-100 text-green-800
                    @else bg-pink-100 text-red-700 @endif">
                    @if($order->status === 'pending') <i class="fas fa-clock"></i> Menunggu Konfirmasi
                    @elseif($order->status === 'diproses') <i class="fas fa-cog fa-spin"></i> Sedang Diproses
                    @elseif($order->status === 'dikirim') <i class="fas fa-truck"></i> Sedang Dikirim
                    @elseif($order->status === 'selesai') <i class="fas fa-check-circle"></i> Selesai
                    @else <i class="fas fa-times-circle"></i> Dibatalkan
                    @endif
                </span>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-5">
                <div class="flex gap-3 items-center">
                    @foreach($order->items->take(3) as $item)
                        @if($item->product)
                            <img class="w-[60px] h-[60px] object-cover rounded-xl border-[1.5px] border-maroon-200 flex-shrink-0"
                                src="{{ $item->product->image && Str::startsWith($item->product->image, 'products/') ? asset('storage/' . $item->product->image) : $item->product->image }}"
                                alt="{{ $item->product->name }}">
                        @endif
                    @endforeach
                    @if($order->items->count() > 3)
                        <div class="w-[60px] h-[60px] rounded-xl bg-maroon-100 flex items-center justify-center text-maroon font-extrabold text-[0.88rem] flex-shrink-0">
                            +{{ $order->items->count() - 3 }}
                        </div>
                    @endif
                    <div>
                        @if($order->items->count() > 0 && $order->items->first()->product)
                            <h4 class="font-bold text-[#1a1a1a] text-[0.95rem] mb-1">{{ $order->items->first()->product->name }}
                                @if($order->items->count() > 1)
                                    <span class="text-[#999] font-medium">& {{ $order->items->count() - 1 }} produk lainnya</span>
                                @endif
                            </h4>
                        @else
                            <h4 class="text-[#999] text-[0.95rem] mb-1">Produk tidak tersedia</h4>
                        @endif
                        <p class="text-[0.83rem] text-[#999]">{{ $order->items->sum('quantity') }} item · {{ ucfirst($order->metode_pembayaran) }}</p>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="flex justify-between items-center px-6 py-4 border-t border-maroon-200">
                <div>
                    <div class="text-[0.82rem] text-[#999] mb-[3px]">Total Pembayaran</div>
                    <div class="font-extrabold text-maroon text-[1.05rem]">Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                </div>
                <div class="flex items-center gap-2">
                    @if($order->status === 'selesai')
                        @if($order->testimonial)
                            <span class="inline-flex items-center gap-1.5 text-green-700 text-[0.85rem] font-bold mr-2">
                                <i class="fas fa-check-circle"></i> Sudah Diulas
                            </span>
                        @else
                            <button class="inline-flex items-center gap-2 bg-white text-maroon px-5 py-[10px] rounded-[10px] font-bold text-[0.85rem] border-[1.5px] border-maroon hover:bg-maroon-100 transition-all cursor-pointer" onclick="toggleForm({{ $order->id }})">
                                <i class="fas fa-star"></i> Tulis Ulasan
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center gap-2 bg-maroon text-white px-5 py-[10px] rounded-[10px] font-bold text-[0.85rem] no-underline hover:bg-maroon-dark hover:-translate-y-px transition-all">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>

            {{-- FORM TESTIMONI --}}
            @if($order->status === 'selesai' && !$order->testimonial)
            <div class="testimoni-form-wrapper px-6 py-5 border-t border-maroon-200 bg-[#fffaf9]" id="form-{{ $order->id }}">
                @if(session('success') && session('order_id') == $order->id)
                    <div class="text-green-800 font-bold mb-[10px]">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('testimonial.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <p class="font-bold text-[#1a1a1a] mb-2">Beri Rating</p>
                    <div class="star-rating flex flex-row-reverse justify-end gap-1.5 mb-3">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" id="star{{ $i }}-{{ $order->id }}" value="{{ $i }}">
                            <label for="star{{ $i }}-{{ $order->id }}">★</label>
                        @endfor
                    </div>
                    <p class="font-bold text-[#1a1a1a] mb-2">Komentar</p>
                    <textarea name="komentar" rows="3" required
                        class="w-full border-[1.5px] border-maroon-200 rounded-[10px] p-3 font-sans text-[0.88rem] resize-y outline-none focus:border-maroon transition-colors box-border"
                        placeholder="Ceritakan pengalaman kamu memesan di Ummilaa Kitchen..."></textarea>
                    <div class="mt-[10px]">
                        <button type="submit" class="bg-maroon text-white border-none px-6 py-[10px] rounded-[10px] font-bold text-[0.85rem] cursor-pointer">
                            <i class="fas fa-paper-plane"></i> Kirim Ulasan
                        </button>
                        <button type="button" onclick="toggleForm({{ $order->id }})"
                            class="bg-transparent border-none text-[#999] text-[0.85rem] cursor-pointer ml-[10px]">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.status-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.status-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const status = tab.dataset.status;
            document.querySelectorAll('.order-card').forEach(card => {
                card.style.display = (status === 'semua' || card.dataset.status === status) ? 'block' : 'none';
            });
        });
    });

    function toggleForm(orderId) {
        const form = document.getElementById('form-' + orderId);
        form.classList.toggle('open');
    }
</script>
@endpush
