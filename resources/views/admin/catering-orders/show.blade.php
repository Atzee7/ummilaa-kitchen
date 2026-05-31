@extends('admin.layouts.app')
@section('title', 'Detail Catering #' . $order->id)

@php
    $statusMeta = [
        'pengajuan'           => ['Pengajuan', 'bg-yellow-100 text-yellow-700'],
        'menunggu_pembayaran' => ['Menunggu Pembayaran', 'bg-amber-100 text-amber-700'],
        'diproses'            => ['Diproses', 'bg-blue-100 text-blue-700'],
        'dikirim'             => ['Dikirim', 'bg-purple-100 text-purple-700'],
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
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jam Pengantaran</p>
                    <p class="font-semibold text-gray-700">{{ $order->jam_pengantaran ? \Carbon\Carbon::parse($order->jam_pengantaran)->format('H:i') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jumlah Pax</p>
                    <p class="font-semibold text-gray-700">{{ $order->jumlah_pax }} porsi</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Lokasi Acara</p>
                    <p class="font-semibold text-gray-700 leading-relaxed">{{ $order->lokasi_acara }}</p>
                    @if($order->detail_lokasi_acara)
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1 mt-3">Detail Lokasi</p>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $order->detail_lokasi_acara }}</p>
                    @endif
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
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Metode Pembayaran</p>
                    @php
                        $paymentLabels = [
                            'qris'          => 'QRIS',
                            'bank_transfer' => 'Transfer Bank',
                            'credit_card'   => 'Kartu Kredit',
                            'gopay'         => 'GoPay',
                            'shopeepay'     => 'ShopeePay',
                            'echannel'      => 'Mandiri Bill',
                            'cstore'        => 'Convenience Store',
                        ];
                    @endphp
                    <p class="font-semibold text-gray-700">
                        {{ $paymentLabels[$order->payment_type ?? ''] ?? ($order->payment_type ? ucfirst(str_replace('_', ' ', $order->payment_type)) : '—') }}
                    </p>
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

        {{-- TETAPKAN TOTAL (status pengajuan) --}}
        @if($order->status === 'pengajuan')
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-2">Tetapkan Total Biaya</h3>
            <p class="text-xs text-gray-400 mb-4">Masukkan rincian biaya pesanan ini.</p>
            <form method="POST" action="{{ route('admin.catering-orders.openPayment', $order->id) }}" id="costForm">
                @csrf

                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Rincian Biaya <span class="text-red-500">*</span></label>
                <div id="costItems" class="space-y-2 mb-3">
                    @if($order->package && $order->package->price_per_pax)
                    <div class="cost-row flex gap-2 items-center">
                        <input type="text" name="cost_items[0][label]"
                            value="Paket {{ $order->package->name }} × {{ $order->jumlah_pax }} pax"
                            placeholder="Keterangan"
                            class="flex-1 px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800">
                        <input type="number" name="cost_items[0][amount]"
                            value="{{ $order->package->price_per_pax * $order->jumlah_pax }}"
                            min="0" placeholder="0"
                            class="w-28 px-3 py-2 rounded-xl border border-gray-200 text-sm cost-amount focus:outline-none focus:border-red-800">
                        <button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 text-xl leading-none">×</button>
                    </div>
                    @endif
                </div>
                @error('cost_items')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror

                <button type="button" onclick="addRow()"
                    class="w-full py-2 rounded-xl border border-dashed border-gray-300 text-xs text-gray-500 hover:border-red-300 hover:text-red-500 transition mb-4">
                    + Tambah Biaya
                </button>

                <div class="flex items-center gap-2 mb-4 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
                    <span class="text-sm text-amber-700 font-semibold flex-1">Total Akhir</span>
                    <span class="text-sm text-gray-500 font-semibold">Rp</span>
                    <input type="number" name="total" id="totalInput" required min="1"
                        class="w-32 px-2 py-1 rounded-lg border border-amber-300 text-sm font-bold text-right bg-white focus:outline-none focus:border-amber-500"
                        placeholder="0">
                </div>
                @error('total')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror

                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Catatan Admin</label>
                <textarea name="admin_notes" rows="2"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm mb-4 resize-none focus:outline-none focus:border-red-800"
                    placeholder="Catatan revisi harga, kondisi khusus, dll...">{{ old('admin_notes') }}</textarea>

                <button type="submit" class="w-full py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition bg-[#8B1A1A]">
                    Tetapkan Total & Buka Pembayaran
                </button>
            </form>
        </div>
        @endif

        {{-- BATALKAN dari pengajuan --}}
        @if($order->status === 'pengajuan')
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-red-700 mb-2">Batalkan Pesanan</h3>
            <p class="text-xs text-gray-400 mb-4">Tolak pengajuan ini jika tidak dapat dipenuhi.</p>
            <form method="POST" action="{{ route('admin.catering-orders.updateStatus', $order->id) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="dibatalkan">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                    Alasan Pembatalan <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan_pembatalan" rows="3" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800 resize-none mb-3"
                    placeholder="Masukkan alasan pembatalan...">{{ old('alasan_pembatalan') }}</textarea>
                @error('alasan_pembatalan')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror
                <button type="submit"
                    onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                    class="w-full py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition bg-red-700">
                    Batalkan Pesanan
                </button>
            </form>
        </div>
        @endif

        {{-- BATALKAN dari menunggu_pembayaran --}}
        @if($order->status === 'menunggu_pembayaran')
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-red-700 mb-2">Batalkan Pesanan</h3>
            <p class="text-xs text-gray-400 mb-4">Batalkan jika customer tidak jadi membayar atau deal batal.</p>
            <form method="POST" action="{{ route('admin.catering-orders.updateStatus', $order->id) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="dibatalkan">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                    Alasan Pembatalan <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan_pembatalan" rows="3" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800 resize-none mb-3"
                    placeholder="Masukkan alasan pembatalan..."></textarea>
                <button type="submit"
                    onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                    class="w-full py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition bg-red-700">
                    Batalkan Pesanan
                </button>
            </form>
        </div>
        @endif

        {{-- KONFIRMASI PEMBAYARAN (status terkonfirmasi) --}}
        @if($order->status === 'terkonfirmasi')
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-teal-400">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-2">Pembayaran Masuk</h3>
            <p class="text-xs text-gray-400 mb-4">Pembayaran customer sudah dikonfirmasi. Mulai proses pesanan jika siap.</p>
            <div class="bg-teal-50 border border-teal-200 rounded-xl px-4 py-3 mb-4 flex justify-between items-center">
                <span class="text-sm text-teal-700 font-semibold">Total Diterima</span>
                <span class="text-lg font-bold text-[#8B1A1A]">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
            <form method="POST" action="{{ route('admin.catering-orders.updateStatus', $order->id) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="diproses">
                <button type="submit"
                    class="w-full py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition bg-[#8B1A1A]">
                    ✓ Mulai Proses
                </button>
            </form>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-red-700 mb-2">Batalkan Pesanan</h3>
            <p class="text-xs text-gray-400 mb-4">Batalkan jika ada masalah setelah pembayaran masuk.</p>
            <form method="POST" action="{{ route('admin.catering-orders.updateStatus', $order->id) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="dibatalkan">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                    Alasan Pembatalan <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan_pembatalan" rows="3" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800 resize-none mb-3"
                    placeholder="Masukkan alasan pembatalan..."></textarea>
                <button type="submit"
                    onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                    class="w-full py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition bg-red-700">
                    Batalkan Pesanan
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
        @if(in_array($order->status, ['diproses', 'dikirim', 'selesai']))
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4">Ubah Status</h3>
            <form method="POST" action="{{ route('admin.catering-orders.updateStatus', $order->id) }}">
                @csrf @method('PATCH')
                <select name="status" id="statusSelect" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800 bg-white mb-4">
                    <option value="diproses"  {{ $order->status === 'diproses'  ? 'selected' : '' }}>Diproses</option>
                    <option value="dikirim"   {{ $order->status === 'dikirim'   ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai"   {{ $order->status === 'selesai'   ? 'selected' : '' }}>Selesai</option>
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

        {{-- RINCIAN BIAYA --}}
        @if($order->costItems->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4">Rincian Biaya</h3>
            <div class="space-y-2 text-sm">
                @foreach($order->costItems as $item)
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">{{ $item->label }}</span>
                    <span class="font-semibold text-gray-700">Rp{{ number_format($item->amount, 0, ',', '.') }}</span>
                </div>
                @endforeach
                <div class="flex justify-between items-center pt-2 border-t border-gray-100 font-bold">
                    <span class="text-gray-800">Total</span>
                    <span class="text-[#8B1A1A]">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
            @if($order->admin_notes)
            <p class="mt-3 text-xs text-gray-500 italic border-t border-gray-50 pt-3">📝 {{ $order->admin_notes }}</p>
            @endif
        </div>
        @endif

        {{-- RIWAYAT STATUS --}}
        @if($order->histories->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-playfair text-lg font-bold text-gray-800 mb-4">Riwayat Status</h3>
            <div class="space-y-3">
                @foreach($order->histories as $h)
                <div class="flex gap-3 text-sm">
                    <span class="text-gray-400 text-xs whitespace-nowrap mt-0.5 w-20 shrink-0">{{ $h->created_at->format('d M H:i') }}</span>
                    <div>
                        <span class="font-semibold text-gray-700">{{ $statusMeta[$h->status][0] ?? ucfirst($h->status) }}</span>
                        @if($h->notes)<p class="text-gray-500 text-xs mt-0.5">{{ $h->notes }}</p>@endif
                    </div>
                </div>
                @endforeach
            </div>
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
const WA_PROMPT_ID = {{ session()->pull('catering_wa_prompt') ?? 'null' }};

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

    // Dynamic cost items
    const costItems = document.getElementById('costItems');
    if (costItems) {
        recalcTotal();
        costItems.addEventListener('input', recalcTotal);
    }
});

@if($order->status === 'pengajuan')
let _rowCount = {{ $order->package && $order->package->price_per_pax ? 1 : 0 }};

function addRow() {
    const container = document.getElementById('costItems');
    const div = document.createElement('div');
    div.className = 'cost-row flex gap-2 items-center';
    div.innerHTML = `
        <input type="text" name="cost_items[${_rowCount}][label]" placeholder="Keterangan"
            class="flex-1 px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800">
        <input type="number" name="cost_items[${_rowCount}][amount]" placeholder="0" min="0"
            class="w-28 px-3 py-2 rounded-xl border border-gray-200 text-sm cost-amount focus:outline-none focus:border-red-800" oninput="recalcTotal()">
        <button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 text-xl leading-none">×</button>`;
    container.appendChild(div);
    _rowCount++;
}

function removeRow(btn) {
    btn.closest('.cost-row').remove();
    recalcTotal();
}

function recalcTotal() {
    const amounts = document.querySelectorAll('.cost-amount');
    let sum = 0;
    amounts.forEach(el => sum += parseInt(el.value || 0, 10));
    const totalInput = document.getElementById('totalInput');
    if (totalInput) totalInput.value = sum || '';
}
@endif
</script>
@endpush
@endsection
