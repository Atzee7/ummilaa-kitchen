@extends('admin.layouts.app')
@section('title', 'Laporan Catering')

@section('content')
{{-- Header --}}
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h2 class="font-playfair text-3xl font-bold text-gray-800">Laporan Catering</h2>
        <p class="text-gray-500 mt-1">Analitik pesanan catering dan performa layanan</p>
    </div>
    <a href="{{ route('admin.laporan-catering.export', array_filter(['periode' => $periode, 'tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai])) }}"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 shadow-sm transition">
        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        Export CSV
    </a>
</div>

{{-- Filter Periode --}}
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <form method="GET" action="{{ route('admin.laporan-catering.index') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">Periode</label>
            <select name="periode" onchange="toggleCustom(this.value)"
                class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-200 w-full min-w-[160px]">
                @foreach(['bulan_ini' => 'Bulan Ini', 'tahun_ini' => 'Tahun Ini', 'custom' => 'Custom'] as $val => $label)
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
    {{-- Total Pesanan --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-green-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-0.5">Total Pesanan</p>
            <p class="font-playfair text-xl font-bold text-gray-800">{{ number_format($totalPesanan) }}</p>
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
    {{-- Total Pax --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-start gap-4">
        <div class="w-11 h-11 rounded-xl bg-orange-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-0.5">Total Pax Terlayani</p>
            <p class="font-playfair text-xl font-bold text-gray-800">{{ number_format($totalPax) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">Porsi terlayani</p>
        </div>
    </div>
</div>

{{-- Status Pills --}}
@php
    $statusConfig = [
        'pengajuan'            => ['label' => 'Pengajuan',          'bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'dot' => 'bg-amber-400'],
        'menunggu_pembayaran'  => ['label' => 'Menunggu Bayar',     'bg' => 'bg-yellow-50',  'text' => 'text-yellow-700',  'dot' => 'bg-yellow-400'],
        'diproses'             => ['label' => 'Diproses',           'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'dot' => 'bg-blue-400'],
        'dikirim'              => ['label' => 'Dikirim',            'bg' => 'bg-purple-50',  'text' => 'text-purple-700',  'dot' => 'bg-purple-400'],
        'selesai'              => ['label' => 'Selesai',            'bg' => 'bg-green-50',   'text' => 'text-green-700',   'dot' => 'bg-green-500'],
        'dibatalkan'           => ['label' => 'Dibatalkan',         'bg' => 'bg-red-50',     'text' => 'text-red-700',     'dot' => 'bg-red-400'],
    ];
@endphp
<div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Ringkasan Status Pesanan Catering (Periode Ini)</p>
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

{{-- Grafik Pendapatan --}}
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="font-semibold text-gray-800">Grafik Pendapatan Catering</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $start->translatedFormat('d M Y') }} — {{ $end->translatedFormat('d M Y') }}</p>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-0.5 bg-[#8B1A1A] rounded"></span>Pendapatan</span>
            <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-0.5 bg-blue-400 rounded"></span>Pesanan</span>
        </div>
    </div>
    <canvas id="grafikPendapatan" height="90"></canvas>
</div>

{{-- Paket Terpopuler --}}
<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <h3 class="font-semibold text-gray-800 mb-5">Paket Catering Terpopuler</h3>
    @if($topPaket->isEmpty())
        <div class="flex items-center justify-center h-40 text-gray-400 text-sm">Tidak ada data</div>
    @else
        <canvas id="grafikPaket" height="80"></canvas>
    @endif
</div>

{{-- Tabel Transaksi --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-semibold text-gray-800">Daftar Pesanan Catering</h3>
        <span class="text-sm text-gray-500">{{ $totalPesanan }} pesanan ditemukan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-100 bg-gray-50">
                <tr>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">ID</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Pemesan</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Acara</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Paket</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Pax</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Total</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Tgl Acara</th>
                    <th class="text-left py-3.5 px-6 text-gray-500 font-semibold text-xs uppercase tracking-wide">Jam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ordersGrouped as $grupKey => $grupOrders)
                    @php
                        $grupTotal   = $grupOrders->sum('total');
                        $grupJumlah  = $grupOrders->count();
                        $grupPax     = $grupOrders->sum('jumlah_pax');

                        if ($periode === 'tahun_ini') {
                            $grupLabel    = \Carbon\Carbon::createFromFormat('Y-m', $grupKey)->translatedFormat('F Y');
                            $grupSubLabel = null;
                        } else {
                            $tgl          = \Carbon\Carbon::parse($grupKey);
                            $grupLabel    = $tgl->translatedFormat('l');
                            $grupSubLabel = $tgl->translatedFormat('d F Y');
                        }
                        $ikonSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                    @endphp
                    {{-- Header grup --}}
                    <tr class="bg-[#fdf8f8] border-t-2 border-b border-[#e8d5d5]">
                        <td colspan="8" class="py-3 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#8B1A1A] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $ikonSvg !!}
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm leading-tight">{{ $grupLabel }}</p>
                                    @if($grupSubLabel)
                                        <p class="text-xs text-gray-400 leading-tight">{{ $grupSubLabel }}</p>
                                    @endif
                                </div>
                                <div class="ml-auto flex items-center gap-4">
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">Pesanan</p>
                                        <p class="text-sm font-bold text-gray-700">{{ $grupJumlah }}</p>
                                    </div>
                                    <div class="w-px h-8 bg-gray-200"></div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">Pax</p>
                                        <p class="text-sm font-bold text-gray-700">{{ $grupPax }}</p>
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
                    @foreach($grupOrders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-3.5 px-6 font-semibold text-gray-700">#{{ $order->id }}</td>
                        <td class="py-3.5 px-6">
                            <p class="font-semibold text-gray-800">{{ $order->nama_pemesan ?? $order->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $order->no_telepon ?? '-' }}</p>
                        </td>
                        <td class="py-3.5 px-6 text-gray-600">{{ $order->nama_acara ?? '-' }}</td>
                        <td class="py-3.5 px-6">
                            <span class="text-xs bg-gray-100 text-gray-600 font-semibold px-2 py-1 rounded-lg">{{ $order->package->name ?? '-' }}</span>
                        </td>
                        <td class="py-3.5 px-6 font-semibold text-gray-700">{{ $order->jumlah_pax }}</td>
                        <td class="py-3.5 px-6 font-semibold text-gray-800">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-6 text-gray-600 text-xs">{{ $order->tanggal_acara?->format('d/m/Y') ?? '-' }}</td>
                        <td class="py-3.5 px-6 text-gray-500 text-xs">{{ $order->created_at->format('H:i') }}</td>
                    </tr>
                    @endforeach
                @empty
                <tr>
                    <td colspan="8" class="py-12 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Tidak ada pesanan catering pada periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const grafikLabelsRaw  = @json($grafikData->pluck('tanggal'));
    const grafikPendapatan = @json($grafikData->pluck('pendapatan'));
    const grafikJumlah     = @json($grafikData->pluck('jumlah'));
    const periodeAktif     = @json($periode);

    const bulanSingkat = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const grafikLabels = grafikLabelsRaw.map(lbl => {
        if (periodeAktif === 'tahun_ini') {
            const [, m] = lbl.split('-');
            return bulanSingkat[parseInt(m, 10) - 1] ?? lbl;
        }
        return lbl;
    });

    const paketLabels = @json($topPaket->map(fn($p) => $p->package->name ?? 'Paket #' . $p->catering_package_id));
    const paketData   = @json($topPaket->pluck('total_pesanan'));

    const maroon    = '#8B1A1A';
    const barColors = ['#8B1A1A','#A52020','#C23B3B','#D97070','#EAA8A8'];

    // Grafik Pendapatan (Line, dual axis)
    (function () {
        const ctx = document.getElementById('grafikPendapatan').getContext('2d');
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
                        label: 'Pesanan',
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
                        yAxisID: 'yPesanan',
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
                                : ctx.raw + ' pesanan'
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
                    yPesanan: {
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

    // Grafik Paket Terpopuler (Horizontal Bar)
    if (document.getElementById('grafikPaket')) {
        new Chart(document.getElementById('grafikPaket').getContext('2d'), {
            type: 'bar',
            data: {
                labels: paketLabels,
                datasets: [{
                    label: 'Pesanan',
                    data: paketData,
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
                        callbacks: { label: ctx => ctx.raw + ' pesanan' }
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

    function toggleCustom(val) {
        const el = document.getElementById('custom-date');
        el.classList.toggle('hidden', val !== 'custom');
        el.classList.toggle('flex', val === 'custom');
    }
</script>
@endsection
