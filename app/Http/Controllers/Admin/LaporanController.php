<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    private function getDateRange(string $periode, ?string $tanggalMulai, ?string $tanggalSelesai): array
    {
        switch ($periode) {
            case 'hari_ini':
                return [Carbon::today(), Carbon::today()->endOfDay()];
            case 'minggu_ini':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
            case 'tahun_ini':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
            case 'custom':
                $start = $tanggalMulai ? Carbon::parse($tanggalMulai)->startOfDay() : Carbon::now()->startOfMonth();
                $end   = $tanggalSelesai ? Carbon::parse($tanggalSelesai)->endOfDay() : Carbon::now()->endOfDay();
                return [$start, $end];
            default:
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        }
    }

    public function index(Request $request)
    {
        $periode        = $request->get('periode', 'hari_ini');
        $tanggalMulai   = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        [$start, $end] = $this->getDateRange($periode, $tanggalMulai, $tanggalSelesai);

        // Query pesanan selesai dalam periode
        $orders = Order::with('user')
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        // Kelompokkan untuk tampilan tabel
        $ordersGrouped = $orders->groupBy(function ($order) use ($periode) {
            if ($periode === 'hari_ini')  return $order->created_at->format('H');
            if ($periode === 'tahun_ini') return $order->created_at->format('Y-m');
            return $order->created_at->format('Y-m-d');
        });

        // Statistik ringkasan
        $totalPendapatan = $orders->sum('total');
        $totalTransaksi  = $orders->count();
        $rataRata        = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        // Total item terjual
        $totalItemTerjual = OrderItem::whereHas('order', fn($q) =>
            $q->where('status', 'selesai')->whereBetween('created_at', [$start, $end])
        )->sum('quantity');

        // Jumlah pesanan per status dalam periode
        $statusCount = Order::whereBetween('created_at', [$start, $end])
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        // Data grafik — granularitas menyesuaikan periode
        // created_at tersimpan dalam WIB, tidak perlu CONVERT_TZ
        $grafikBase = Order::where('status', 'selesai')->whereBetween('created_at', [$start, $end]);
        if ($periode === 'hari_ini') {
            $rawJam = (clone $grafikBase)
                ->selectRaw("DATE_FORMAT(created_at, '%H') as tanggal, SUM(total) as pendapatan, COUNT(*) as jumlah")
                ->groupBy('tanggal')->orderBy('tanggal')->get()
                ->keyBy('tanggal');

            // Tampilkan semua jam dalam sehari (00:00–23:00)
            $grafikData = collect();
            for ($h = 0; $h <= 23; $h++) {
                $key = str_pad($h, 2, '0', STR_PAD_LEFT);
                $grafikData->push((object)[
                    'tanggal'    => $key,
                    'pendapatan' => $rawJam->get($key)->pendapatan ?? 0,
                    'jumlah'     => $rawJam->get($key)->jumlah     ?? 0,
                ]);
            }
        } elseif ($periode === 'tahun_ini') {
            $grafikData = (clone $grafikBase)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as tanggal, SUM(total) as pendapatan, COUNT(*) as jumlah")
                ->groupBy('tanggal')->orderBy('tanggal')->get();
        } else {
            $grafikData = (clone $grafikBase)
                ->selectRaw("DATE(created_at) as tanggal, SUM(total) as pendapatan, COUNT(*) as jumlah")
                ->groupBy('tanggal')->orderBy('tanggal')->get();
        }

        // Top 5 produk terlaris (berdasarkan quantity)
        $topProduk = OrderItem::selectRaw('product_id, SUM(quantity) as total_terjual')
            ->whereHas('order', fn($q) =>
                $q->where('status', 'selesai')->whereBetween('created_at', [$start, $end])
            )
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Distribusi metode pembayaran
        $metodePembayaran = Order::where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('metode_pembayaran, COUNT(*) as jumlah')
            ->groupBy('metode_pembayaran')
            ->get();

        // Distribusi metode pengiriman
        $metodePengiriman = Order::where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('metode_pengiriman, COUNT(*) as jumlah')
            ->groupBy('metode_pengiriman')
            ->get();

        return view('admin.laporan.index', compact(
            'orders', 'ordersGrouped', 'periode', 'tanggalMulai', 'tanggalSelesai',
            'totalPendapatan', 'totalTransaksi', 'rataRata', 'grafikData',
            'totalItemTerjual', 'statusCount', 'topProduk',
            'metodePembayaran', 'metodePengiriman', 'start', 'end'
        ));
    }

    public function export(Request $request)
    {
        $periode        = $request->get('periode', 'bulan_ini');
        $tanggalMulai   = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        [$start, $end] = $this->getDateRange($periode, $tanggalMulai, $tanggalSelesai);

        $orders = Order::with(['user', 'items.product'])
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $filename = 'laporan-penjualan-' . $start->format('Y-m-d') . '-sd-' . $end->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM untuk Excel

            fputcsv($file, ['ID', 'Pelanggan', 'Email', 'Subtotal', 'Ongkir', 'Total', 'Metode Bayar', 'Metode Kirim', 'Tanggal']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    '#' . $order->id,
                    $order->user->name ?? $order->nama_penerima ?? '-',
                    $order->user->email ?? 'Pembelian Langsung',
                    $order->subtotal,
                    $order->ongkir,
                    $order->total,
                    $order->metode_pembayaran,
                    $order->metode_pengiriman,
                    $order->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}