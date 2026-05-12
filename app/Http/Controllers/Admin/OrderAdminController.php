<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'semua');

        $query = Order::with('user')->latest();

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15);

        $counts = [
            'semua'      => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'diproses'   => Order::where('status', 'diproses')->count(),
            'dikirim'    => Order::where('status', 'dikirim')->count(),
            'selesai'    => Order::where('status', 'selesai')->count(),
            'dibatalkan' => Order::where('status', 'dibatalkan')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'status', 'counts'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai,dibatalkan',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}