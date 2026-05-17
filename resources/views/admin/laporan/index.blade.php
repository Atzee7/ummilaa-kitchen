@extends('admin.layouts.app')
@section('title', 'Laporan Penjualan')

@section('content')
{{-- Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h2 class="font-playfair text-3xl font-bold text-gray-800">Laporan Penjualan</h2>
        <p class="text-gray-500 mt-1">Analitik transaksi dan performa toko</p>
    </div>
    <a href="{{ route('admin.laporan.export', array_filter(['periode' => $periode, 'tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai])) }}"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 shadow-sm transition">
        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        Export CSV
    </a>
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
        <div id="custom-date" class="{{ $periode === 'custom' ? 'flex' : 'hidden' }} gap-3 flex-wrap">
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
            class="px-6 py-2.5 bg-[#8B1A1A] text-white text-sm font-semibold rounded-xl hover:bg-[#6f1515] transition">
            Tampilkan
        </button>
    </form>
</div>

{{-- 4 KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Total Pendapatan --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-[#8B1A1A] flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs text-gray-500 mb-0.5">Total Pendapatan</p>
            <p class="font-playfair text-xl font-bold text-gray-800 truncate">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Pesanan selesai</p>
        </div>
    </div>
    {{-- Total Transaksi --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-0.5">Total Transaksi</p>
            <p class="font-playfair text-xl font-bold text-gray-800">{{ number_format($totalTransaksi) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Pesanan selesai</p>
        </div>
    </div>
    {{-- Rata-rata Pesanan --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-blue-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-xs text-gray-500 mb-0.5">Rata-rata Pesanan</p>
            <p class="font-playfair text-xl font-bold text-gray-800 truncate">Rp{{ number_format($rataRata, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Per transaksi</p>
        </div>
    </div>
    {{-- Total Item Terjual --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-orange-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-0.5">Item Terjual</p>
            <p class="font-playfair text-xl font-bold text-gray-800">{{ number_format($totalItemTerjual) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Total unit</p>
        </div>
    </div>
</div>

{{-- Status Pills --}}
@php
    $statusConfig = [
        'belum_bayar'  => ['label' => 'Belum Bayar',    'bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'dot' => 'bg-amber-400'],
        'pending'      => ['label' => 'Menunggu',       'bg' => 'bg-yellow-50',  'text' => 'text-yellow-700',  'dot' => 'bg-yellow-400'],
        'diproses'     => ['label' => 'Sedang Dimasak', 'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'dot' => 'bg-blue-400'],
        'dikirim'      => ['label' => 'Dikirim',        'bg' => 'bg-purple-50',  'text' => 'text-purple-700',  'dot' => 'bg-purple-400'],
        'siap_diambil' => ['label' => 'Siap Diambil',   'bg' => 'bg-orange-50',  'text' => 'text-orange-700',  'dot' => 'bg-orange-400'],
        'selesai'      => ['label' => 'Selesai',        'bg' => 'bg-green-50',   'text' => 'text-green-700',   'dot' => 'bg-green-500'],
        'dibatalkan'   => ['label' => 'Dibatalkan',     'bg' => 'bg-red-50',     'text' => 'text-red-700',     'dot' => 'bg-red-400'],
    ];
@endphp
<div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Ringkasan Status Pesanan (Periode Ini)</p>
    <div class="flex flex-wrap gap-3">
        @foreach($statusConfig as $key => $cfg)
        <div class="{{ $cfg['bg'] }} rounded-xl px-4 py-2.5 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }}"></span>
            <span class="text-xs font-semibold {{ $cfg['text'] }}">{{ $cfg['label'] }}</span>
            <span class="text-sm font-bold {{ $cfg['text'] }}">{{ $statusCount[$key] ?? 0 }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Grafik Pendapatan (full width) --}}
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="font-semibold text-gray-800">Grafik Pendapatan</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $start->translatedFormat('d M Y') }} — {{ $end->translatedFormat('d M Y') }}</p>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-0.5 bg-[#8B1A1A] rounded"></span>Pendapatan</span>
            <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-0.5 bg-blue-400 rounded"></span>Transaksi</span>
        </div>
    </div>
    <canvas id="grafikPenjualan" height="90"></canvas>
</div>

{{-- Produk Terlaris --}}
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <h3 class="font-semibold text-gray-800 mb-5">Produk Terlaris</h3>
    @if($topProduk->isEmpty())
        <div class="flex items-center justify-center h-40 text-gray-400 text-sm">Tidak ada data</div>
    @else
        <canvas id="grafikProduk" height="80"></canvas>
    @endif
</div>

{{-- Tabel Transaksi --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">Daftar Transaksi</h3>
        <span class="text-sm text-gray-500">{{ $totalTransaksi }} transaksi ditemukan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-100 bg-gray-50">
                <tr>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">ID</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Pelanggan</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Subtotal</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Ongkir</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Total</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Bayar</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Kirim</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Jam</th>
                    <th class="py-3.5 px-6"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($ordersGrouped as $grupKey => $grupOrders)
                    @php
                        $jamAkhir    = str_pad((int)$grupKey + 1, 2, '0', STR_PAD_LEFT);
                        $grupTotal   = $grupOrders->sum('total');
                        $grupJumlah  = $grupOrders->count();

                        if ($periode === 'hari_ini') {
                            $grupLabel    = 'Pukul ' . $grupKey . ':00 – ' . $jamAkhir . ':00';
                            $grupSubLabel = null;
                            $ikonSvg      = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                        } elseif ($periode === 'tahun_ini') {
                            $grupLabel    = \Carbon\Carbon::createFromFormat('Y-m', $grupKey)->translatedFormat('F Y');
                            $grupSubLabel = null;
                            $ikonSvg      = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                        } else {
                            $tgl          = \Carbon\Carbon::parse($grupKey);
                            $grupLabel    = $tgl->translatedFormat('l');
                            $grupSubLabel = $tgl->translatedFormat('d F Y');
                            $ikonSvg      = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                        }
                    @endphp
                    {{-- Header grup --}}
                    <tr class="bg-[#fdf8f8] border-t-2 border-b border-[#e8d5d5]">
                        <td colspan="9" class="py-3 px-6">
                            <div class="flex items-center gap-3">
                                {{-- Ikon --}}
                                <div class="w-8 h-8 rounded-lg bg-[#8B1A1A] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $ikonSvg !!}
                                    </svg>
                                </div>
                                {{-- Label --}}
                                <div>
                                    <p class="font-bold text-gray-800 text-sm leading-tight">{{ $grupLabel }}</p>
                                    @if($grupSubLabel)
                                        <p class="text-xs text-gray-400 leading-tight">{{ $grupSubLabel }}</p>
                                    @endif
                                </div>
                                {{-- Stats kanan --}}
                                <div class="ml-auto flex items-center gap-4">
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">Transaksi</p>
                                        <p class="text-sm font-bold text-gray-700">{{ $grupJumlah }}</p>
                                    </div>
                                    <div class="w-px h-8 bg-gray-200"></div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">Total</p>
                                        <p class="text-sm font-bold text-[#8B1A1A]">Rp{{ number_format($grupTotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    {{-- Baris order dalam grup --}}
                    @foreach($grupOrders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-3.5 px-6 font-semibold text-gray-700">#{{ $order->id }}</td>
                        <td class="py-3.5 px-6">
                            <p class="font-semibold text-gray-800">{{ $order->user->name ?? $order->nama_penerima ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user->email ?? 'Pembelian Langsung' }}</p>
                        </td>
                        <td class="py-3.5 px-6 text-gray-600">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-6 text-gray-600">Rp{{ number_format($order->ongkir, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-6 font-semibold text-gray-800">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-6">
                            <span class="text-xs bg-gray-100 text-gray-600 font-semibold px-2 py-1 rounded-lg uppercase">{{ $order->metode_pembayaran }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="text-xs font-semibold {{ $order->metode_pengiriman === 'delivery' ? 'text-purple-600 bg-purple-50' : 'text-blue-600 bg-blue-50' }} px-2 py-1 rounded-lg capitalize">
                                {{ $order->metode_pengiriman }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 text-xs">{{ $order->created_at->format('H:i') }}</td>
                        <td class="py-3.5 px-4">
                            <button onclick="openOrderModal({{ $order->id }})"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-[#8B1A1A] hover:text-white hover:bg-[#8B1A1A] border border-[#8B1A1A] rounded-lg px-3 py-1.5 transition">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                @empty
                <tr>
                    <td colspan="9" class="py-12 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Tidak ada transaksi pada periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Detail Pesanan --}}
<div id="order-modal-backdrop"
     class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden items-center justify-center p-4"
     onclick="handleBackdropClick(event)">
    <div id="order-modal"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
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

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ─── Data dari server ──────────────────────────────
    const grafikLabelsRaw  = @json($grafikData->pluck('tanggal'));
    const grafikPendapatan = @json($grafikData->pluck('pendapatan'));
    const grafikJumlah     = @json($grafikData->pluck('jumlah'));
    const periodeAktif     = @json($periode);

    const bulanSingkat = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const grafikLabels = grafikLabelsRaw.map(lbl => {
        if (periodeAktif === 'hari_ini') return lbl + ':00';
        if (periodeAktif === 'tahun_ini') {
            const [, m] = lbl.split('-');
            return bulanSingkat[parseInt(m, 10) - 1] ?? lbl;
        }
        return lbl;
    });

    const produkLabels   = @json($topProduk->map(fn($p) => $p->product->name ?? 'Produk #' . $p->product_id));
    const produkData     = @json($topProduk->pluck('total_terjual'));

    // ─── Warna ────────────────────────────────────────
    const maroon    = '#8B1A1A';
    const barColors = ['#8B1A1A','#A52020','#C23B3B','#D97070','#EAA8A8'];

    // ─── Grafik Pendapatan (Line, dual axis) ──────────
    (function () {
        const ctx = document.getElementById('grafikPenjualan').getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 280);
        grad.addColorStop(0, 'rgba(139,26,26,0.25)');
        grad.addColorStop(1, 'rgba(139,26,26,0.01)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: grafikLabels,
                datasets: [
                    {
                        label: 'Pendapatan',
                        data: grafikPendapatan,
                        fill: true,
                        backgroundColor: grad,
                        borderColor: maroon,
                        borderWidth: 2.5,
                        pointBackgroundColor: maroon,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        tension: 0.4,
                        yAxisID: 'yPendapatan',
                    },
                    {
                        label: 'Transaksi',
                        data: grafikJumlah,
                        fill: false,
                        borderColor: '#60a5fa',
                        borderWidth: 2,
                        borderDash: [5, 4],
                        pointBackgroundColor: '#60a5fa',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        tension: 0.4,
                        yAxisID: 'yTransaksi',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#d1d5db',
                        padding: 12,
                        callbacks: {
                            label: ctx => ctx.dataset.label === 'Pendapatan'
                                ? 'Rp ' + ctx.raw.toLocaleString('id-ID')
                                : ctx.raw + ' transaksi'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af', font: { size: 11 } }
                    },
                    yPendapatan: {
                        position: 'left',
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 11 },
                            callback: val => 'Rp ' + (val >= 1000000
                                ? (val/1000000).toFixed(1) + 'jt'
                                : val.toLocaleString('id-ID'))
                        }
                    },
                    yTransaksi: {
                        position: 'right',
                        beginAtZero: true,
                        grid: { drawOnChartArea: false },
                        ticks: {
                            color: '#60a5fa',
                            font: { size: 11 },
                            stepSize: 1,
                            callback: val => val + 'x'
                        }
                    }
                }
            }
        });
    })();

    // ─── Grafik Produk Terlaris (Horizontal Bar) ──────
    if (document.getElementById('grafikProduk')) {
        new Chart(document.getElementById('grafikProduk').getContext('2d'), {
            type: 'bar',
            data: {
                labels: produkLabels,
                datasets: [{
                    label: 'Terjual',
                    data: produkData,
                    backgroundColor: barColors,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        callbacks: { label: ctx => ctx.raw + ' item terjual' }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: { color: '#9ca3af', font: { size: 11 }, stepSize: 1 }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#374151', font: { size: 12 } }
                    }
                }
            }
        });
    }


    // ─── Modal Detail Pesanan ──────────────────────────
    const CSRF_TOKEN = '{{ csrf_token() }}';

    function openOrderModal(orderId) {
        const backdrop = document.getElementById('order-modal-backdrop');
        const content  = document.getElementById('order-modal-content');
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
        .then(() => {
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

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeOrderModal();
    });

    // ─── Toggle custom date ────────────────────────────
    function toggleCustom(val) {
        const el = document.getElementById('custom-date');
        el.classList.toggle('hidden', val !== 'custom');
        el.classList.toggle('flex', val === 'custom');
    }
</script>
@endsection
