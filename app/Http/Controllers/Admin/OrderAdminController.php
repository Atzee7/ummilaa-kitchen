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
        $date   = $request->get('date', today()->format('Y-m-d'));

        $query = Order::with('user')->latest()->whereDate('created_at', $date);

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15);

        $counts = [
            'semua'        => Order::whereDate('created_at', $date)->count(),
            'pending'      => Order::whereDate('created_at', $date)->where('status', 'pending')->count(),
            'diproses'     => Order::whereDate('created_at', $date)->where('status', 'diproses')->count(),
            'dikirim'      => Order::whereDate('created_at', $date)->where('status', 'dikirim')->count(),
            'siap_diambil' => Order::whereDate('created_at', $date)->where('status', 'siap_diambil')->count(),
            'selesai'      => Order::whereDate('created_at', $date)->where('status', 'selesai')->count(),
            'dibatalkan'   => Order::whereDate('created_at', $date)->where('status', 'dibatalkan')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'status', 'counts', 'date'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,siap_diambil,selesai,dibatalkan',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json(['status' => $order->status]);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function modalContent($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders._modal_content', compact('order'));
    }
}