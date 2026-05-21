@extends('admin.layouts.app')
@section('title', 'Detail Catering #' . $order->id)

@php
    $statusMeta = [
        'pengajuan'           => ['Pengajuan', 'bg-yellow-100 text-yellow-700'],
        'menunggu_pembayaran' => ['Menunggu Pembayaran', 'bg-amber-100 text-amber-700'],
        'diproses'            => ['Diproses', 'bg-blue-100 text-blue-700'],
        'selesai'             => ['Selesai', 'bg-green-100 text-green-700'],
        'dibatalkan'          => ['Dibatalkan', 'bg-red-100 text-red-700'],
    ];
    [$statusLabel, $sc] = $statusMeta[$order->status] ?? [ucfirst($order->status), 'bg-gray-100 text-gray-700'];
@endphp

@section('content')

<div id="wa-toast" class="hidden mb-5 px-4 py-3 rounded-xl text-sm flex items-center gap-2.5 relative overflow-hidden border" style="transition: opacity 0.5s ease;">
    <span id="wa-toast-msg"></span>
</div>

<div class="mb-8">
    <a href="{{ route('admin.catering-orders.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Pesanan Catering
    </a>
    <div class="flex items-start justify-between">
        <div>
            <h2 class="font-playfair text-3xl font-bold text-gray-800">Detail Catering <span class="text-[#8B1A1A]">#{{ $order->id }}</span></h2>
            <p class="text-gray-400 text-sm mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="px-4 py-2 rounded-xl text-sm font-bold {{ $sc }}">{{ $statusLabel }}</span>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- KIRI --}}
    <div class="xl:col-span-2 flex flex-col gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100">Detail Acara</h3>
            <div class="grid grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Acara</p>
                    <p class="font-bold text-gray-800 text-base">{{ $order->nama_acara }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Paket</p>
                    <p class="font-semibold text-gray-700">{{ $order->package->name ?? 'Custom (tanpa paket)' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Acara</p>
                    <p class="font-semibold text-gray-700">{{ $order->tanggal_acara->translatedFormat('l, d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jumlah Pax</p>
                    <p class="font-semibold text-gray-700">{{ $order->jumlah_pax }} porsi</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Lokasi Acara</p>
                    <p class="font-semibold text-gray-700 leading-relaxed">{{ $order->lokasi_acara }}</p>
                </div>
            </div>
            @if($order->catatan)
            <div class="mt-5 bg-yellow-50 border border-yellow-100 rounded-xl p-4 flex gap-3">
                <div class="text-xl flex-shrink-0">💬</div>
                <p class="text-sm text-gray-700 leading-relaxed italic">"{{ $order->catatan }}"</p>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100">Informasi Pemesan</h3>
            <div class="grid grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Pemesan</p>
                    <p class="font-bold text-gray-800 text-base">{{ $order->nama_pemesan }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">No. Telepon</p>
                    <p class="font-bold text-gray-800 text-base">{{ $order->no_telepon }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Akun</p>
                    <p class="font-semibold text-gray-700">{{ $order->user->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status Pembayaran</p>
                    <p class="font-semibold text-gray-700">{{ $order->paid_at ? 'Lunas · ' . $order->paid_at->format('d M Y H:i') : 'Belum dibayar' }}</p>
                </div>
            </div>
        </div>

        @if($order->status === 'dibatalkan' && $order->alasan_pembatalan)
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-red-700 mb-4 pb-3 border-b border-red-100">Alasan Pembatalan</h3>
            <div class="bg-red-50 border border-red-100 rounded-xl p-4 flex gap-3">
                <span class="text-xl flex-shrink-0">❌</span>
                <p class="text-sm text-red-800 leading-relaxed">{{ $order->alasan_pembatalan }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- KANAN --}}
    <div class="flex flex-col gap-6">

        {{-- BUKA PEMBAYARAN (status pengajuan) --}}
        @if($order->status === 'pengajuan')
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-2">Buka Pembayaran</h3>
            <p class="text-xs text-gray-400 mb-4">Masukkan total tagihan hasil kesepakatan via WhatsApp.</p>
            <form method="POST" action="{{ route('admin.catering-orders.openPayment', $order->id) }}">
                @csrf
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Total Tagihan (Rp) <span class="text-red-500">*</span></label>
                <div class="relative mb-4">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-semibold">Rp</span>
                    <input type="number" name="total" value="{{ old('total') }}" required min="1" placeholder="0"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-800 transition">
                </div>
                @error('total')<p class="text-red-500 text-xs mb-3">{{ $message }}</p>@enderror
                <button type="submit" class="w-full py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition bg-[#8B1A1A]">
                    Buka Pembayaran
                </button>
            </form>
        </div>
        @endif

        {{-- INFO PEMBAYARAN + KIRIM WA (status menunggu_pembayaran) --}}
        @if($order->status === 'menunggu_pembayaran')
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4">Pembayaran</h3>
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-4 flex justify-between items-center">
                <span class="text-sm text-amber-700 font-semibold">Total Tagihan</span>
                <span class="text-lg font-bold text-[#8B1A1A]">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
            <button type="button" onclick="sendCateringWa({{ $order->id }})"
                class="w-full py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition flex items-center justify-center gap-2"
                style="background-color:#25D366">
                <i class="fab fa-whatsapp"></i> Kirim Info Pembayaran (WA)
            </button>
            <p class="text-xs text-gray-400 mt-3 text-center">Pelanggan membayar via halaman Riwayat Catering.</p>
        </div>
        @endif

        {{-- UPDATE STATUS --}}
        @if(in_array($order->status, ['diproses', 'menunggu_pembayaran', 'selesai']))
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4">Ubah Status</h3>
            <form method="POST" action="{{ route('admin.catering-orders.updateStatus', $order->id) }}">
                @csrf @method('PATCH')
                <select name="status" id="statusSelect" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800 bg-white mb-4">
                    <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai"  {{ $order->status === 'selesai'  ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
                <div id="alasanSection" class="hidden mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Alasan Pembatalan <span class="text-red-500">(wajib)</span></label>
                    <textarea name="alasan_pembatalan" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800 resize-none"
                        placeholder="Masukkan alasan pembatalan...">{{ old('alasan_pembatalan') }}</textarea>
                </div>
                <button type="submit" class="w-full py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition bg-[#8B1A1A]">Perbarui Status</button>
            </form>
        </div>
        @endif

        {{-- RINGKASAN --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4">Ringkasan</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-semibold">ID Pesanan</span>
                    <span class="font-bold text-gray-800">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-semibold">Diajukan</span>
                    <span class="font-semibold text-gray-700">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-400 font-semibold">Total</span>
                    <span class="font-bold text-base text-[#8B1A1A]">{{ $order->total ? 'Rp' . number_format($order->total, 0, ',', '.') : '—' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script>
const CSRF = '{{ csrf_token() }}';
const WA_PROMPT_ID = {{ session('catering_wa_prompt') ?? 'null' }};

function showToast(success, message) {
    const t = document.getElementById('wa-toast');
    t.classList.remove('hidden', 'bg-green-50', 'border-green-200', 'text-green-800', 'bg-red-50', 'border-red-200', 'text-red-800');
    t.classList.add(success ? 'bg-green-50' : 'bg-red-50', success ? 'border-green-200' : 'border-red-200', success ? 'text-green-800' : 'text-red-800');
    document.getElementById('wa-toast-msg').textContent = (success ? '✅ ' : '❌ ') + message;
    t.style.opacity = '1';
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.classList.add('hidden'), 500); }, 3500);
}

function sendCateringWa(id) {
    fetch(`/admin/catering-orders/${id}/send-whatsapp`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(d => showToast(d.success, d.success ? 'Info pembayaran terkirim via WhatsApp!' : d.message))
    .catch(() => showToast(false, 'Terjadi kesalahan saat mengirim pesan.'));
}

document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('statusSelect');
    const alasan = document.getElementById('alasanSection');
    if (sel && alasan) {
        const sync = () => {
            const cancel = sel.value === 'dibatalkan';
            alasan.classList.toggle('hidden', !cancel);
            const ta = alasan.querySelector('textarea');
            if (cancel) ta.setAttribute('required', ''); else ta.removeAttribute('required');
        };
        sync();
        sel.addEventListener('change', sync);
    }

    if (WA_PROMPT_ID && WA_PROMPT_ID === {{ $order->id }}) {
        sendCateringWa(WA_PROMPT_ID);
    }
});
</script>
@endpush
@endsection
