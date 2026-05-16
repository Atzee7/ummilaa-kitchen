@extends('admin.layouts.app')
@section('title', 'Pesanan')

@section('content')
<div class="mb-8">
    <h2 class="font-playfair text-3xl font-bold text-gray-800">Pesanan</h2>
    <p class="text-gray-500 mt-1">Kelola semua pesanan pelanggan</p>
</div>

@php
    $tabs = ['semua','pending','diproses','dikirim','selesai','dibatalkan'];
    $tabColor = [
        'semua'      => 'bg-gray-100 text-gray-700',
        'pending'    => 'bg-yellow-100 text-yellow-700',
        'diproses'   => 'bg-blue-100 text-blue-700',
        'dikirim'    => 'bg-purple-100 text-purple-700',
        'selesai'    => 'bg-green-100 text-green-700',
        'dibatalkan' => 'bg-red-100 text-red-700',
    ];
@endphp

<div class="flex flex-wrap gap-2 mb-6">
    @foreach($tabs as $tab)
    <a href="{{ route('admin.orders.index', ['status' => $tab]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition
              {{ $status === $tab ? $tabColor[$tab] . ' ring-2 ring-offset-1 ring-gray-300' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
        {{ ucfirst($tab) }}
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs bg-black bg-opacity-10">{{ $counts[$tab] }}</span>
    </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="border-b border-gray-100">
            <tr>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">ID</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Pelanggan</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Total</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Pengiriman</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Pembayaran</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Status</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Tanggal</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php
                $sc = match($order->status) {
                    'pending'    => 'bg-yellow-100 text-yellow-700',
                    'diproses'   => 'bg-blue-100 text-blue-700',
                    'dikirim'    => 'bg-purple-100 text-purple-700',
                    'selesai'    => 'bg-green-100 text-green-700',
                    'dibatalkan' => 'bg-red-100 text-red-700',
                    default      => 'bg-gray-100 text-gray-700',
                };
                $isDelivery = ($order->metode_pengiriman ?? 'delivery') === 'delivery';
                $nextStatus = match($order->status) {
                    'pending'  => 'diproses',
                    'diproses' => 'dikirim',
                    'dikirim'  => 'selesai',
                    default    => null,
                };
                $nextLabel = match($order->status) {
                    'pending'  => '→ Proses',
                    'diproses' => '→ Kirim',
                    'dikirim'  => '→ Selesai',
                    default    => null,
                };
                $nextColor = match($order->status) {
                    'pending'  => 'bg-blue-600 hover:bg-blue-700 text-white',
                    'diproses' => 'bg-purple-600 hover:bg-purple-700 text-white',
                    'dikirim'  => 'bg-green-600 hover:bg-green-700 text-white',
                    default    => '',
                };
                $canCancel = in_array($order->status, ['pending', 'diproses']);
            @endphp
            <tr id="order-row-{{ $order->id }}" class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="py-4 px-6 font-bold text-gray-700">#{{ $order->id }}</td>
                <td class="py-4 px-6">
                    <p class="font-semibold text-gray-800">{{ $order->user->name ?? $order->nama_penerima ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
                </td>
                <td class="py-4 px-6 font-bold text-[#8B1A1A]">
                    Rp{{ number_format($order->total, 0, ',', '.') }}
                </td>
                <td class="py-4 px-6">
                    @if($isDelivery)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                            Delivery
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Ambil Sendiri
                        </span>
                    @endif
                </td>
                <td class="py-4 px-6 text-gray-600 text-xs font-semibold uppercase">{{ $order->metode_pembayaran }}</td>
                <td class="py-4 px-6">
                    <span id="status-badge-{{ $order->id }}" class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc }}">{{ ucfirst($order->status) }}</span>
                </td>
                <td class="py-4 px-6 text-gray-400 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        {{-- Tombol next status --}}
                        @if($nextStatus)
                        <button
                            data-order-id="{{ $order->id }}"
                            data-next-status="{{ $nextStatus }}"
                            onclick="quickUpdateStatus({{ $order->id }}, '{{ $nextStatus }}', this)"
                            class="quick-status-btn px-2.5 py-1.5 text-xs font-bold rounded-lg transition {{ $nextColor }}">
                            {{ $nextLabel }}
                        </button>
                        @endif

                        {{-- Tombol batalkan --}}
                        @if($canCancel)
                        <button
                            onclick="quickUpdateStatus({{ $order->id }}, 'dibatalkan', this)"
                            class="cancel-btn px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition">
                            Batal
                        </button>
                        @endif

                        {{-- Tombol detail (buka modal) --}}
                        <button
                            onclick="openOrderModal({{ $order->id }})"
                            class="px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                            Detail
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="py-16 text-center text-gray-400">
                <div class="text-4xl mb-3">📭</div>
                <p class="font-semibold">Tidak ada pesanan</p>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $orders->appends(['status' => $status])->links() }}</div>
</div>

{{-- MODAL OVERLAY --}}
<div id="order-modal-backdrop"
     class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden items-center justify-center p-4"
     onclick="handleBackdropClick(event)">
    <div id="order-modal"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Konten dimuat via AJAX --}}
        <div id="order-modal-content" class="flex-1 overflow-hidden flex flex-col">
            <div class="flex items-center justify-center h-48 text-gray-400">
                <div class="text-center">
                    <div class="animate-spin w-6 h-6 border-2 border-[#8B1A1A] border-t-transparent rounded-full mx-auto mb-2"></div>
                    <p class="text-sm">Memuat...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';

const STATUS_BADGE = {
    pending:    'bg-yellow-100 text-yellow-700',
    diproses:   'bg-blue-100 text-blue-700',
    dikirim:    'bg-purple-100 text-purple-700',
    selesai:    'bg-green-100 text-green-700',
    dibatalkan: 'bg-red-100 text-red-700',
};

const NEXT_STATUS = {
    pending:  { status: 'diproses', label: '→ Proses',  color: 'bg-blue-600 hover:bg-blue-700 text-white' },
    diproses: { status: 'dikirim',  label: '→ Kirim',   color: 'bg-purple-600 hover:bg-purple-700 text-white' },
    dikirim:  { status: 'selesai',  label: '→ Selesai', color: 'bg-green-600 hover:bg-green-700 text-white' },
};

function quickUpdateStatus(orderId, newStatus, triggerEl) {
    triggerEl.disabled = true;
    triggerEl.classList.add('opacity-50');

    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: newStatus }),
    })
    .then(r => r.json())
    .then(data => {
        updateRowUI(orderId, data.status);
    })
    .catch(() => {
        triggerEl.disabled = false;
        triggerEl.classList.remove('opacity-50');
        alert('Gagal memperbarui status. Coba lagi.');
    });
}

function updateRowUI(orderId, newStatus) {
    // Update badge status
    const badge = document.getElementById(`status-badge-${orderId}`);
    if (badge) {
        badge.className = `px-2.5 py-1 rounded-full text-xs font-semibold ${STATUS_BADGE[newStatus] || 'bg-gray-100 text-gray-700'}`;
        badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    }

    // Rebuild tombol aksi
    const row = document.getElementById(`order-row-${orderId}`);
    if (!row) return;
    const aksiCell = row.querySelector('td:last-child');
    if (!aksiCell) return;

    const next = NEXT_STATUS[newStatus];
    const canCancel = ['pending', 'diproses'].includes(newStatus);

    let buttonsHtml = '';
    if (next) {
        buttonsHtml += `<button
            onclick="quickUpdateStatus(${orderId}, '${next.status}', this)"
            class="quick-status-btn px-2.5 py-1.5 text-xs font-bold rounded-lg transition ${next.color}">
            ${next.label}
        </button>`;
    }
    if (canCancel) {
        buttonsHtml += `<button
            onclick="quickUpdateStatus(${orderId}, 'dibatalkan', this)"
            class="cancel-btn px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition">
            Batal
        </button>`;
    }
    buttonsHtml += `<button
        onclick="openOrderModal(${orderId})"
        class="px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 transition">
        Detail
    </button>`;

    aksiCell.innerHTML = `<div class="flex items-center gap-1.5 flex-wrap">${buttonsHtml}</div>`;
}

function openOrderModal(orderId) {
    const backdrop = document.getElementById('order-modal-backdrop');
    const content  = document.getElementById('order-modal-content');

    // Reset ke loading state
    content.innerHTML = `
        <div class="flex items-center justify-center h-48 text-gray-400">
            <div class="text-center">
                <div class="animate-spin w-6 h-6 border-2 border-[#8B1A1A] border-t-transparent rounded-full mx-auto mb-2"></div>
                <p class="text-sm">Memuat...</p>
            </div>
        </div>`;

    backdrop.classList.remove('hidden');
    backdrop.classList.add('flex');
    document.body.style.overflow = 'hidden';

    fetch(`/admin/orders/${orderId}/modal`)
        .then(r => r.text())
        .then(html => { content.innerHTML = html; })
        .catch(() => {
            content.innerHTML = `<div class="p-6 text-center text-red-500 text-sm">Gagal memuat detail pesanan.</div>`;
        });
}

function closeOrderModal() {
    const backdrop = document.getElementById('order-modal-backdrop');
    backdrop.classList.add('hidden');
    backdrop.classList.remove('flex');
    document.body.style.overflow = '';
}

function handleBackdropClick(e) {
    if (e.target === document.getElementById('order-modal-backdrop')) {
        closeOrderModal();
    }
}

// Dipanggil dari tombol di dalam modal (_modal_content.blade.php)
function modalUpdateStatus(orderId, newStatus) {
    const btn = event.currentTarget;
    btn.disabled = true;
    btn.classList.add('opacity-50');

    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: newStatus }),
    })
    .then(r => r.json())
    .then(data => {
        updateRowUI(orderId, data.status);
        // Reload konten modal dengan status terbaru
        const content = document.getElementById('order-modal-content');
        content.innerHTML = `
            <div class="flex items-center justify-center h-48 text-gray-400">
                <div class="text-center">
                    <div class="animate-spin w-6 h-6 border-2 border-[#8B1A1A] border-t-transparent rounded-full mx-auto mb-2"></div>
                    <p class="text-sm">Memperbarui...</p>
                </div>
            </div>`;
        fetch(`/admin/orders/${orderId}/modal`)
            .then(r => r.text())
            .then(html => { content.innerHTML = html; });
    })
    .catch(() => {
        btn.disabled = false;
        btn.classList.remove('opacity-50');
        alert('Gagal memperbarui status. Coba lagi.');
    });
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeOrderModal();
});
</script>
@endsection
