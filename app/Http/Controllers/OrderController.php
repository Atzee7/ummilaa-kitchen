<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        Order::cancelExpiredUnpaidOrders();

        $orders = Order::with('items.product', 'testimonial')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $aktifStatuses = ['belum_bayar', 'pending', 'pembayaran', 'diproses', 'dikirim', 'siap_diambil'];
        $counts = [
            'aktif'      => $orders->whereIn('status', $aktifStatuses)->count(),
            'selesai'    => $orders->where('status', 'selesai')->count(),
            'dibatalkan' => $orders->where('status', 'dibatalkan')->count(),
        ];
        $defaultTab = $counts['aktif'] > 0 ? 'aktif' : ($counts['selesai'] > 0 ? 'selesai' : 'dibatalkan');

        $groupedOrders = $orders->groupBy(fn($order) => $order->created_at->format('Y-m-d'));

        $today     = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        return view('orders', compact('groupedOrders', 'today', 'yesterday', 'counts', 'defaultTab'));
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

    public function cancel(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'belum_bayar') {
            return redirect()->route('order.payment', $id)
                ->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        $validated = $request->validate([
            'alasan_pembatalan' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($order, $validated) {
            Order::restoreStock($order->load('items'));
            $order->update([
                'status'            => 'dibatalkan',
                'alasan_pembatalan' => $validated['alasan_pembatalan'],
                'dibatalkan_oleh'   => 'user',
            ]);
        });

        return redirect()->route('orders')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function downloadInvoice($id)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        $filename = 'Invoice-UMK-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($filename);
    }
}
