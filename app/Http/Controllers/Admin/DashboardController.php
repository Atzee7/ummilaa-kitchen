<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    private array $activeStatuses = ['pending', 'diproses', 'dikirim'];

    public function index()
    {
        $totalProduk    = Product::count();
        $totalPesanan   = Order::count();
        $totalPengguna  = User::where('role', 'user')->count();
        $totalPenjualan = Order::where('status', '!=', 'dibatalkan')->sum('total');

        $pesananAktif = Order::with('user')
            ->whereIn('status', $this->activeStatuses)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalPesanan',
            'totalPengguna',
            'totalPenjualan',
            'pesananAktif'
        ));
    }

    public function pesananAktifApi()
    {
        $orders = Order::with('user')
            ->whereIn('status', $this->activeStatuses)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'badge_count' => Order::whereIn('status', $this->activeStatuses)->count(),
            'orders' => $orders->map(fn($o) => [
                'id'      => $o->id,
                'nama'    => $o->user->name ?? $o->nama_penerima ?? '-',
                'total'   => $o->total,
                'metode'  => $o->metode_pembayaran,
                'status'  => $o->status,
                'tanggal' => $o->created_at->format('d M Y'),
            ]),
        ]);
    }
}
