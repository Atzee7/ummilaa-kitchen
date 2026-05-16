<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $groupedOrders = Order::with('items.product', 'testimonial')
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->groupBy(fn($order) => $order->created_at->format('Y-m-d'));

        $today     = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        return view('orders', compact('groupedOrders', 'today', 'yesterday'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('order-detail', compact('order'));
    }
}