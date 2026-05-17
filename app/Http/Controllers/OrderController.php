<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        Order::cancelExpiredUnpaidOrders();

        $orders = Order::with('items.product', 'testimonial')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $counts = [
            'semua'        => $orders->count(),
            'belum_bayar'  => $orders->where('status', 'belum_bayar')->count(),
            'pending'      => $orders->where('status', 'pending')->count(),
            'diproses'     => $orders->where('status', 'diproses')->count(),
            'dikirim'      => $orders->where('status', 'dikirim')->count(),
            'siap_diambil' => $orders->where('status', 'siap_diambil')->count(),
            'selesai'      => $orders->where('status', 'selesai')->count(),
            'dibatalkan'   => $orders->where('status', 'dibatalkan')->count(),
        ];

        $groupedOrders = $orders->groupBy(fn($order) => $order->created_at->format('Y-m-d'));

        $today     = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        return view('orders', compact('groupedOrders', 'today', 'yesterday', 'counts'));
    }

    public function show($id)
    {
        Order::cancelExpiredUnpaidOrders();

        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('order-detail', compact('order'));
    }

    public function markPaid($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'belum_bayar') {
            return redirect()->route('orders')
                ->with('error', 'Pesanan tidak dalam status menunggu pembayaran.');
        }

        if ($order->isPaymentExpired()) {
            Order::cancelExpiredUnpaidOrders();
            return redirect()->route('orders')
                ->with('error', 'Batas waktu pembayaran sudah lewat. Pesanan dibatalkan.');
        }

        $order->update(['status' => 'pending']);

        return redirect()->route('orders')
            ->with('success', 'Terima kasih! Pembayaran Anda akan segera kami konfirmasi.');
    }
}
