<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringOrder;
use App\Models\CateringPackage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanCateringController extends Controller
{
    private function getDateRange(string $periode, ?string $tanggalMulai, ?string $tanggalSelesai): array
    {
        switch ($periode) {
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
        $periode        = $request->get('periode', 'bulan_ini');
        $tanggalMulai   = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        [$start, $end] = $this->getDateRange($periode, $tanggalMulai, $tanggalSelesai);

        $orders = CateringOrder::with(['user', 'package'])
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $ordersGrouped = $orders->groupBy(function ($order) use ($periode) {
            if ($periode === 'tahun_ini') return $order->created_at->format('Y-m');
            return $order->created_at->format('Y-m-d');
        });

        $totalPendapatan = $orders->sum('total');
        $totalPesanan    = $orders->count();
        $rataRata        = $totalPesanan > 0 ? $totalPendapatan / $totalPesanan : 0;
        $totalPax        = $orders->sum('jumlah_pax');

        $statusCount = CateringOrder::whereBetween('created_at', [$start, $end])
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $grafikBase = CateringOrder::where('status', 'selesai')->whereBetween('created_at', [$start, $end]);
        if ($periode === 'tahun_ini') {
            $grafikData = (clone $grafikBase)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as tanggal, SUM(total) as pendapatan, COUNT(*) as jumlah")
                ->groupBy('tanggal')->orderBy('tanggal')->get();
        } else {
            $grafikData = (clone $grafikBase)
                ->selectRaw("DATE(created_at) as tanggal, SUM(total) as pendapatan, COUNT(*) as jumlah")
                ->groupBy('tanggal')->orderBy('tanggal')->get();
        }

        $topPaket = CateringOrder::selectRaw('catering_package_id, COUNT(*) as total_pesanan')
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->with('package:id,name')
            ->groupBy('catering_package_id')
            ->orderByDesc('total_pesanan')
            ->limit(5)
            ->get();

        return view('admin.laporan-catering.index', compact(
            'orders', 'ordersGrouped', 'periode', 'tanggalMulai', 'tanggalSelesai',
            'totalPendapatan', 'totalPesanan', 'rataRata', 'totalPax',
            'grafikData', 'statusCount', 'topPaket', 'start', 'end'
        ));
    }

    public function export(Request $request)
    {
        $periode        = $request->get('periode', 'bulan_ini');
        $tanggalMulai   = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        [$start, $end] = $this->getDateRange($periode, $tanggalMulai, $tanggalSelesai);

        $orders = CateringOrder::with(['user', 'package'])
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $filename = 'laporan-catering-' . $start->format('Y-m-d') . '-sd-' . $end->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['ID', 'Pemesan', 'No Telepon', 'Nama Acara', 'Paket', 'Jumlah Pax', 'Total', 'Tanggal Acara', 'Tanggal Pesanan']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    '#' . $order->id,
                    $order->nama_pemesan ?? $order->user->name ?? '-',
                    $order->no_telepon ?? '-',
                    $order->nama_acara ?? '-',
                    $order->package->name ?? '-',
                    $order->jumlah_pax,
                    $order->total,
                    $order->tanggal_acara?->format('d/m/Y') ?? '-',
                    $order->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
