@extends('admin.layouts.app')
@section('title', 'Laporan Penjualan')

@section('content')
<div class="mb-8">
    <h2 class="font-playfair text-3xl font-bold text-gray-800">Laporan Penjualan</h2>
    <p class="text-gray-500 mt-1">Rekap transaksi pesanan selesai</p>
</div>

{{-- Filter Periode --}}
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Periode</label>
            <select name="periode" onchange="toggleCustom(this.value)"
                class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-200 w-full min-w-[160px]">
                @foreach(['hari_ini' => 'Hari Ini', 'minggu_ini' => 'Minggu Ini', 'bulan_ini' => 'Bulan Ini', 'tahun_ini' => 'Tahun Ini', 'custom' => 'Custom'] as $val => $label)
                    <option value="{{ $val }}" {{ $periode === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div id="custom-date" class="{{ $periode === 'custom' ? 'flex' : 'hidden' }} gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Dari</label>
                <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}"
                    class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-200">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Sampai</label>
                <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}"
                    class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-200">
            </div>
        </div>

        <button type="submit"
            class="px-6 py-2 bg-[#8B1A1A] text-white text-sm font-semibold rounded-xl hover:bg-[#6f1515] transition">
            Tampilkan
        </button>
    </form>
</div>

{{-- Statistik --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <p class="text-sm text-gray-500 mb-1">Total Pendapatan</p>
        <p class="font-playfair text-2xl font-bold text-gray-800">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $start->format('d M Y') }} — {{ $end->format('d M Y') }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <p class="text-sm text-gray-500 mb-1">Total Transaksi</p>
        <p class="font-playfair text-2xl font-bold text-gray-800">{{ $totalTransaksi }}</p>
        <p class="text-xs text-gray-400 mt-1">Pesanan selesai</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <p class="text-sm text-gray-500 mb-1">Rata-rata Pesanan</p>
        <p class="font-playfair text-2xl font-bold text-gray-800">Rp{{ number_format($rataRata, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Per transaksi</p>
    </div>
</div>

{{-- Grafik --}}
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <h3 class="font-semibold text-gray-800 mb-4">Grafik Pendapatan</h3>
    <canvas id="grafikPenjualan" height="100"></canvas>
</div>

{{-- Tabel Transaksi --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">Daftar Transaksi</h3>
        <span class="text-sm text-gray-500">{{ $totalTransaksi }} transaksi ditemukan</span>
    </div>
    <table class="w-full text-sm">
        <thead class="border-b border-gray-100">
            <tr>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">ID</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Pelanggan</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Subtotal</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Ongkir</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Total</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Metode</th>
                <th class="text-left py-4 px-6 text-gray-500 font-semibold">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="border-b border-gray-50 hover:bg-gray-50">
                <td class="py-4 px-6 font-semibold text-gray-700">#{{ $order->id }}</td>
                <td class="py-4 px-6">
                    <p class="font-semibold text-gray-800">{{ $order->user->name ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
                </td>
                <td class="py-4 px-6 text-gray-600">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                <td class="py-4 px-6 text-gray-600">Rp{{ number_format($order->ongkir, 0, ',', '.') }}</td>
                <td class="py-4 px-6 font-semibold text-gray-800">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                <td class="py-4 px-6 text-xs uppercase font-semibold text-gray-500">{{ $order->metode_pembayaran }}</td>
                <td class="py-4 px-6 text-gray-500 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="py-10 text-center text-gray-400">Tidak ada transaksi pada periode ini</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Script Grafik --}}
{{-- Script Grafik --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($grafikData->pluck('tanggal'));
    const data   = @json($grafikData->pluck('pendapatan'));

    const ctx = document.getElementById('grafikPenjualan').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(139, 26, 26, 0.3)');
    gradient.addColorStop(1, 'rgba(139, 26, 26, 0.01)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: data,
                fill: true,
                backgroundColor: gradient,
                borderColor: '#8B1A1A',
                borderWidth: 2.5,
                pointBackgroundColor: '#8B1A1A',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleColor: '#f9fafb',
                    bodyColor: '#d1d5db',
                    padding: 12,
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#9ca3af', font: { size: 12 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        color: '#9ca3af',
                        font: { size: 12 },
                        callback: val => 'Rp ' + val.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    function toggleCustom(val) {
        const el = document.getElementById('custom-date');
        el.classList.toggle('hidden', val !== 'custom');
        el.classList.toggle('flex', val === 'custom');
    }
</script>
@endsection