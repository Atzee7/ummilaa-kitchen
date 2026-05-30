@extends('admin.layouts.app')
@section('title', 'Pesanan Catering')

@php
    $statusMeta = [
        'pengajuan'           => ['Pengajuan', 'bg-yellow-100 text-yellow-700'],
        'menunggu_pembayaran' => ['Menunggu Pembayaran', 'bg-amber-100 text-amber-700'],
        'diproses'            => ['Diproses', 'bg-blue-100 text-blue-700'],
        'dikirim'             => ['Dikirim', 'bg-purple-100 text-purple-700'],
        'selesai'             => ['Selesai', 'bg-green-100 text-green-700'],
        'dibatalkan'          => ['Dibatalkan', 'bg-red-100 text-red-700'],
    ];
    $tabs = [
        'semua'               => 'Aktif',
        'pengajuan'           => 'Pengajuan',
        'menunggu_pembayaran' => 'Menunggu Bayar',
        'diproses'            => 'Diproses',
        'dikirim'             => 'Dikirim',
        'selesai'             => 'Selesai',
        'dibatalkan'          => 'Dibatalkan',
    ];
@endphp

@section('content')
<div class="mb-8">
    <h2 class="font-playfair text-3xl font-bold text-gray-800">Pesanan Catering</h2>
    <p class="text-gray-500 mt-1">Kelola pengajuan & pembayaran catering pelanggan</p>
</div>

{{-- Tabs status --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach($tabs as $key => $label)
    <a href="{{ route('admin.catering-orders.index', ['status' => $key]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition border
           {{ $status === $key ? 'bg-[#8B1A1A] text-white border-[#8B1A1A]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
        {{ $label }}
        <span id="tab-count-{{ $key }}" class="ml-1 text-xs {{ $status === $key ? 'text-white/80' : 'text-gray-400' }}">{{ $counts[$key] ?? 0 }}</span>
    </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="border-b border-gray-100 bg-gray-50">
            <tr>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">ID</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Acara</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Pemesan</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Tanggal Acara</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Pax</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Total</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Status</th>
                <th class="text-left py-3 px-6 text-gray-500 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            @php
                [$label, $color] = $statusMeta[$order->status] ?? [ucfirst($order->status), 'bg-gray-100 text-gray-700'];
                $nextAction = match($order->status) {
                    'diproses' => ['dikirim',  '→ Kirim',   'bg-purple-600 hover:bg-purple-700 text-white'],
                    'dikirim'  => ['selesai',  '→ Selesai', 'bg-green-600 hover:bg-green-700 text-white'],
                    default    => null,
                };
            @endphp
            <tr id="catering-row-{{ $order->id }}" data-status="{{ $order->status }}"
                class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="py-4 px-6 font-semibold text-gray-700">#{{ $order->id }}</td>
                <td class="py-4 px-6">
                    <p class="font-semibold text-gray-800">{{ $order->nama_acara }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $order->package->name ?? 'Custom' }}</p>
                </td>
                <td class="py-4 px-6">
                    <p class="text-gray-700">{{ $order->nama_pemesan }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
                </td>
                <td class="py-4 px-6 text-gray-600">
                    {{ $order->tanggal_acara->translatedFormat('d M Y') }}
                    @if($order->jam_pengantaran)
                    <p class="text-xs text-purple-600 mt-0.5">⏰ {{ \Carbon\Carbon::parse($order->jam_pengantaran)->format('H:i') }}</p>
                    @endif
                </td>
                <td class="py-4 px-6 text-gray-600">{{ $order->jumlah_pax }}</td>
                <td class="py-4 px-6 font-semibold text-gray-800">{{ $order->total ? 'Rp' . number_format($order->total, 0, ',', '.') : '—' }}</td>
                <td class="py-4 px-6">
                    <span id="catering-status-badge-{{ $order->id }}" class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $color }}">{{ $label }}</span>
                </td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if($nextAction)
                        <button onclick="quickCateringStatus({{ $order->id }}, '{{ $nextAction[0] }}', this)"
                            class="px-2.5 py-1.5 text-xs font-bold rounded-lg transition {{ $nextAction[2] }}">
                            {{ $nextAction[1] }}
                        </button>
                        @endif
                        <a href="{{ route('admin.catering-orders.show', $order->id) }}"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition">Detail</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-16 text-center">
                    <div class="text-gray-300 text-4xl mb-3">🍱</div>
                    <p class="text-gray-400 text-sm">Belum ada pesanan catering</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $orders->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
const CSRF_CATERING = '{{ csrf_token() }}';

const CATERING_STATUS_BADGE = {
    pengajuan:           'bg-yellow-100 text-yellow-700',
    menunggu_pembayaran: 'bg-amber-100 text-amber-700',
    diproses:            'bg-blue-100 text-blue-700',
    dikirim:             'bg-purple-100 text-purple-700',
    selesai:             'bg-green-100 text-green-700',
    dibatalkan:          'bg-red-100 text-red-700',
};
const CATERING_STATUS_LABEL = {
    pengajuan: 'Pengajuan', menunggu_pembayaran: 'Menunggu Pembayaran',
    diproses: 'Diproses', dikirim: 'Dikirim', selesai: 'Selesai', dibatalkan: 'Dibatalkan',
};
const CATERING_NEXT = {
    diproses: { status: 'dikirim',  label: '→ Kirim',   color: 'bg-purple-600 hover:bg-purple-700 text-white' },
    dikirim:  { status: 'selesai',  label: '→ Selesai', color: 'bg-green-600 hover:bg-green-700 text-white' },
};

function quickCateringStatus(orderId, newStatus, triggerEl) {
    triggerEl.disabled = true;
    triggerEl.classList.add('opacity-50');

    fetch(`/admin/catering-orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN':  CSRF_CATERING,
            'Content-Type':  'application/json',
            'Accept':        'application/json',
        },
        body: JSON.stringify({ status: newStatus }),
    })
    .then(r => r.json())
    .then(data => {
        updateCateringRowUI(orderId, data.status);
        if (data.sent_wa) {
            showCateringToast('✅ WA notifikasi pengiriman berhasil dikirim ke pelanggan.');
        }
    })
    .catch(() => {
        triggerEl.disabled = false;
        triggerEl.classList.remove('opacity-50');
        alert('Gagal memperbarui status. Coba lagi.');
    });
}

function updateCateringRowUI(orderId, newStatus) {
    const badge = document.getElementById(`catering-status-badge-${orderId}`);
    if (badge) {
        badge.className = `px-2.5 py-1 rounded-full text-xs font-semibold ${CATERING_STATUS_BADGE[newStatus] || 'bg-gray-100 text-gray-700'}`;
        badge.textContent = CATERING_STATUS_LABEL[newStatus] || newStatus;
    }

    const row = document.getElementById(`catering-row-${orderId}`);
    if (!row) return;
    const cell = row.querySelector('td:last-child > div');
    if (!cell) return;

    const next = CATERING_NEXT[newStatus];
    let html = '';
    if (next) {
        html += `<button onclick="quickCateringStatus(${orderId},'${next.status}',this)"
            class="px-2.5 py-1.5 text-xs font-bold rounded-lg transition ${next.color}">${next.label}</button>`;
    }
    html += `<a href="/admin/catering-orders/${orderId}"
        class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition">Detail</a>`;
    cell.innerHTML = html;

    const oldStatus = row.dataset.status;
    const activeStatuses = ['pengajuan', 'menunggu_pembayaran', 'diproses', 'dikirim'];
    if (oldStatus && oldStatus !== newStatus) {
        const oldSpan = document.getElementById(`tab-count-${oldStatus}`);
        if (oldSpan) { const n = parseInt(oldSpan.textContent) || 0; if (n > 0) oldSpan.textContent = n - 1; }
        const newSpan = document.getElementById(`tab-count-${newStatus}`);
        if (newSpan) newSpan.textContent = (parseInt(newSpan.textContent) || 0) + 1;

        const aktifSpan = document.getElementById('tab-count-semua');
        if (aktifSpan) {
            const wasActive = activeStatuses.includes(oldStatus);
            const isActive  = activeStatuses.includes(newStatus);
            if (wasActive && !isActive) { const n = parseInt(aktifSpan.textContent) || 0; if (n > 0) aktifSpan.textContent = n - 1; }
            else if (!wasActive && isActive) { aktifSpan.textContent = (parseInt(aktifSpan.textContent) || 0) + 1; }
        }
        row.dataset.status = newStatus;
    }

    if (['selesai', 'dibatalkan'].includes(newStatus)) {
        const sidebarBadge = document.getElementById('catering-badge');
        if (sidebarBadge) {
            const n = parseInt(sidebarBadge.textContent) || 0;
            if (n <= 1) { sidebarBadge.classList.add('hidden'); sidebarBadge.classList.remove('flex'); }
            else sidebarBadge.textContent = n - 1;
        }
        row.style.transition = 'opacity 0.4s, transform 0.4s';
        row.style.opacity = '0';
        row.style.transform = 'translateX(20px)';
        setTimeout(() => row.remove(), 420);
    }
}

function showCateringToast(msg) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-6 right-6 z-50 bg-green-700 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg flex items-center gap-2';
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.transition = 'opacity 0.4s'; toast.style.opacity = '0'; setTimeout(() => toast.remove(), 400); }, 3500);
}
</script>
@endpush
