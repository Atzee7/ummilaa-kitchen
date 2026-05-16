@extends('admin.layouts.app')
@section('title', 'Pesanan')

@section('content')
<div class="mb-8 flex items-start justify-between gap-4 flex-wrap">
    <div>
        <h2 class="font-playfair text-3xl font-bold text-gray-800">Pesanan</h2>
        <p class="text-gray-500 mt-1">
            @if($date === today()->format('Y-m-d'))
                Pesanan hari ini
            @else
                Pesanan — {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            @endif
        </p>
    </div>
    {{-- Filter Tanggal --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
        <input type="hidden" name="status" value="{{ $status }}">
        <label class="text-sm font-semibold text-gray-500">Tanggal:</label>
        <input type="date" name="date" value="{{ $date }}"
               onchange="this.form.submit()"
               class="px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-800 bg-white cursor-pointer">
    </form>
</div>

@php
    $tabs = ['semua','pending','diproses','dikirim','siap_diambil','selesai','dibatalkan'];
    $tabColor = [
        'semua'        => 'bg-gray-100 text-gray-700',
        'pending'      => 'bg-yellow-100 text-yellow-700',
        'diproses'     => 'bg-blue-100 text-blue-700',
        'dikirim'      => 'bg-purple-100 text-purple-700',
        'siap_diambil' => 'bg-orange-100 text-orange-700',
        'selesai'      => 'bg-green-100 text-green-700',
        'dibatalkan'   => 'bg-red-100 text-red-700',
    ];
@endphp

<div class="flex flex-wrap gap-2 mb-6">
    @foreach($tabs as $tab)
    <a href="{{ route('admin.orders.index', ['status' => $tab, 'date' => $date]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold transition
              {{ $status === $tab ? $tabColor[$tab] . ' ring-2 ring-offset-1 ring-gray-300' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
        {{ $tab === 'siap_diambil' ? 'Siap Diambil' : ucfirst($tab) }}
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
                    'pending'      => 'bg-yellow-100 text-yellow-700',
                    'diproses'     => 'bg-blue-100 text-blue-700',
                    'dikirim'      => 'bg-purple-100 text-purple-700',
                    'siap_diambil' => 'bg-orange-100 text-orange-700',
                    'selesai'      => 'bg-green-100 text-green-700',
                    'dibatalkan'   => 'bg-red-100 text-red-700',
                    default        => 'bg-gray-100 text-gray-700',
                };
                $isDelivery = ($order->metode_pengiriman ?? 'delivery') === 'delivery';
                $nextStatus = match(true) {
                    $order->status === 'pending'                          => 'diproses',
                    $order->status === 'diproses' && $isDelivery          => 'dikirim',
                    $order->status === 'diproses' && !$isDelivery         => 'siap_diambil',
                    $order->status === 'dikirim'                          => 'selesai',
                    $order->status === 'siap_diambil'                     => 'selesai',
                    default                                               => null,
                };
                $nextLabel = match(true) {
                    $order->status === 'pending'                          => '→ Proses',
                    $order->status === 'diproses' && $isDelivery          => '→ Kirim',
                    $order->status === 'diproses' && !$isDelivery         => '→ Siap Diambil',
                    $order->status === 'dikirim'                          => '→ Selesai',
                    $order->status === 'siap_diambil'                     => '→ Selesai',
                    default                                               => null,
                };
                $nextColor = match(true) {
                    $order->status === 'pending'                          => 'bg-blue-600 hover:bg-blue-700 text-white',
                    $order->status === 'diproses' && $isDelivery          => 'bg-purple-600 hover:bg-purple-700 text-white',
                    $order->status === 'diproses' && !$isDelivery         => 'bg-orange-500 hover:bg-orange-600 text-white',
                    $order->status === 'dikirim'                          => 'bg-green-600 hover:bg-green-700 text-white',
                    $order->status === 'siap_diambil'                     => 'bg-green-600 hover:bg-green-700 text-white',
                    default                                               => '',
                };
                $canCancel = in_array($order->status, ['pending', 'diproses']);
            @endphp
            <tr id="order-row-{{ $order->id }}" data-delivery="{{ $isDelivery ? '1' : '0' }}" class="border-b border-gray-50 hover:bg-gray-50 transition">
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
                    <span id="status-badge-{{ $order->id }}" class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc }}">{{ $order->status === 'siap_diambil' ? 'Siap Diambil' : ucfirst($order->status) }}</span>
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
    <div class="p-4">{{ $orders->appends(['status' => $status, 'date' => $date])->links() }}</div>
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

{{-- POPUP KONFIRMASI WHATSAPP --}}
<div id="wa-confirm-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-80 text-center mx-4">
        <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color:#25D366">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-800 text-base mb-1">Kirim Konfirmasi WhatsApp?</h3>
        <p class="text-sm text-gray-500 mb-5">Beritahu pembeli bahwa pesanannya sedang diproses.</p>
        <div class="flex gap-3">
            <button id="wa-confirm-no"
                class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                Tidak
            </button>
            <button id="wa-confirm-yes"
                class="flex-1 py-2.5 rounded-xl text-white text-sm font-bold hover:opacity-90 transition"
                style="background-color:#25D366">
                Kirim WA
            </button>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = '{{ csrf_token() }}';

const STATUS_BADGE = {
    pending:      'bg-yellow-100 text-yellow-700',
    diproses:     'bg-blue-100 text-blue-700',
    dikirim:      'bg-purple-100 text-purple-700',
    siap_diambil: 'bg-orange-100 text-orange-700',
    selesai:      'bg-green-100 text-green-700',
    dibatalkan:   'bg-red-100 text-red-700',
};

const STATUS_LABEL = {
    pending:      'Pending',
    diproses:     'Diproses',
    dikirim:      'Dikirim',
    siap_diambil: 'Siap Diambil',
    selesai:      'Selesai',
    dibatalkan:   'Dibatalkan',
};

function getNextStatus(currentStatus, isDelivery) {
    if (currentStatus === 'pending')      return { status: 'diproses',     label: '→ Proses',       color: 'bg-blue-600 hover:bg-blue-700 text-white' };
    if (currentStatus === 'diproses')     return isDelivery
        ? { status: 'dikirim',      label: '→ Kirim',        color: 'bg-purple-600 hover:bg-purple-700 text-white' }
        : { status: 'siap_diambil', label: '→ Siap Diambil', color: 'bg-orange-500 hover:bg-orange-600 text-white' };
    if (currentStatus === 'dikirim')      return { status: 'selesai',      label: '→ Selesai',      color: 'bg-green-600 hover:bg-green-700 text-white' };
    if (currentStatus === 'siap_diambil') return { status: 'selesai',      label: '→ Selesai',      color: 'bg-green-600 hover:bg-green-700 text-white' };
    return null;
}

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
        if (['dikirim', 'siap_diambil'].includes(data.status) && data.has_phone) {
            showWaConfirmModal(orderId);
        }
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
        badge.textContent = STATUS_LABEL[newStatus] || newStatus;
    }

    // Rebuild tombol aksi
    const row = document.getElementById(`order-row-${orderId}`);
    if (!row) return;
    const aksiCell = row.querySelector('td:last-child');
    if (!aksiCell) return;

    const isDelivery = row.dataset.delivery === '1';
    const next = getNextStatus(newStatus, isDelivery);
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
            .then(html => {
                content.innerHTML = html;
                if (['dikirim', 'siap_diambil'].includes(data.status) && data.has_phone) {
                    showWaConfirmModal(orderId);
                }
            });
    })
    .catch(() => {
        btn.disabled = false;
        btn.classList.remove('opacity-50');
        alert('Gagal memperbarui status. Coba lagi.');
    });
}

// Popup konfirmasi WA
let waOrderId = null;

function showWaConfirmModal(orderId) {
    waOrderId = orderId;
    document.getElementById('wa-confirm-modal').classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('wa-confirm-no').addEventListener('click', () => {
        document.getElementById('wa-confirm-modal').classList.add('hidden');
        waOrderId = null;
    });
    document.getElementById('wa-confirm-yes').addEventListener('click', () => {
        const id = waOrderId;
        document.getElementById('wa-confirm-modal').classList.add('hidden');
        waOrderId = null;
        if (id) sendWhatsapp(id);
    });
});

// Kirim konfirmasi WhatsApp via Fonnte
function sendWhatsapp(orderId) {
    const yesBtn = document.getElementById('wa-confirm-yes');
    if (yesBtn) { yesBtn.disabled = true; yesBtn.style.opacity = '0.6'; }

    fetch(`/admin/orders/${orderId}/send-whatsapp`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        alert(data.success ? '✅ Pesan WhatsApp berhasil dikirim!' : '❌ ' + data.message);
    })
    .catch(() => alert('❌ Terjadi kesalahan saat mengirim pesan.'))
    .finally(() => {
        if (yesBtn) { yesBtn.disabled = false; yesBtn.style.opacity = '1'; }
    });
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeOrderModal();
});
</script>
@endsection
