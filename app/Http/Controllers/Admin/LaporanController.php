<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'bulan_ini');
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        // Tentukan rentang tanggal berdasarkan periode
        switch ($periode) {
            case 'hari_ini':
                $start = Carbon::today();
                $end   = Carbon::today()->endOfDay();
                break;
            case 'minggu_ini':
                $start = Carbon::now()->startOfWeek();
                $end   = Carbon::now()->endOfWeek();
                break;
            case 'tahun_ini':
                $start = Carbon::now()->startOfYear();
                $end   = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $start = $tanggalMulai ? Carbon::parse($tanggalMulai)->startOfDay() : Carbon::now()->startOfMonth();
                $end   = $tanggalSelesai ? Carbon::parse($tanggalSelesai)->endOfDay() : Carbon::now()->endOfDay();
                break;
            default: // bulan_ini
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
                break;
        }

        // Query pesanan selesai dalam periode
        $orders = Order::with('user')
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        // Statistik ringkasan
        $totalPendapatan = $orders->sum('total');
        $totalTransaksi  = $orders->count();
        $rataRata        = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        // Data grafik — pendapatan per hari dalam periode
        $grafikData = Order::where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as tanggal, SUM(total) as pendapatan, COUNT(*) as jumlah')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('admin.laporan.index', compact(
            'orders', 'periode', 'tanggalMulai', 'tanggalSelesai',
            'totalPendapatan', 'totalTransaksi', 'rataRata', 'grafikData', 'start', 'end'
        ));
    }
}