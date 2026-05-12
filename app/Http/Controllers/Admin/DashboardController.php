<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk    = Product::count();
        $totalPesanan   = Order::count();
        $totalPengguna  = User::where('role', 'user')->count();
        $totalPenjualan = Order::where('status', '!=', 'dibatalkan')->sum('total');

        $pesananTerbaru = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalPesanan',
            'totalPengguna',
            'totalPenjualan',
            'pesananTerbaru'
        ));
    }
}