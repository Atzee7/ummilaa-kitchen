<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringOrder;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CateringOrderAdminController extends Controller
{
    public function index(Request $request)
    {
        CateringOrder::cancelExpiredUnpaidOrders();

        $status = $request->get('status', 'semua');

        $baseQuery = CateringOrder::query()->with(['user', 'package']);

        $query = (clone $baseQuery)->latest();
        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        $counts = [
            'semua'               => (clone $baseQuery)->count(),
            'pengajuan'           => (clone $baseQuery)->where('status', 'pengajuan')->count(),
            'menunggu_pembayaran' => (clone $baseQuery)->where('status', 'menunggu_pembayaran')->count(),
            'diproses'            => (clone $baseQuery)->where('status', 'diproses')->count(),
            'selesai'             => (clone $baseQuery)->where('status', 'selesai')->count(),
            'dibatalkan'          => (clone $baseQuery)->where('status', 'dibatalkan')->count(),
        ];

        return view('admin.catering-orders.index', compact('orders', 'status', 'counts'));
    }

    public function show($id)
    {
        CateringOrder::cancelExpiredUnpaidOrders();

        $order = CateringOrder::with(['user', 'package', 'costItems', 'histories'])->findOrFail($id);
        return view('admin.catering-orders.show', compact('order'));
    }

    public function openPayment(Request $request, $id)
    {
        $request->validate([
            'cost_items'          => 'required|array|min:1',
            'cost_items.*.label'  => 'required|string|max:255',
            'cost_items.*.amount' => 'required|integer|min:0',
            'total'               => 'required|integer|min:1',
            'admin_notes'         => 'nullable|string|max:2000',
        ]);

        $order = CateringOrder::with(['package', 'costItems'])->findOrFail($id);

        if ($order->status !== 'pengajuan') {
            return back()->withErrors(['total' => 'Pembayaran hanya bisa dibuka untuk pesanan berstatus "Pengajuan".']);
        }

        $order->costItems()->delete();

        foreach ($request->cost_items as $i => $item) {
            $order->costItems()->create([
                'label'      => $item['label'],
                'amount'     => (int) $item['amount'],
                'sort_order' => $i,
            ]);
        }

        $order->update([
            'total'       => $request->total,
            'admin_notes' => $request->admin_notes,
            'status'      => 'menunggu_pembayaran',
            'snap_token'  => null,
        ]);

        $order->logHistory('menunggu_pembayaran', 'Total akhir ditetapkan oleh admin.');

        if (!empty($order->no_telepon)) {
            $order->load('costItems');
            try {
                (new FonnteService())->send($order->no_telepon, $this->buildPaymentMessage($order));
            } catch (\Throwable $e) {
                Log::error('Gagal kirim WA catering openPayment', ['id' => $order->id, 'error' => $e->getMessage()]);
            }
        }

        return back()->with('success', 'Total biaya berhasil ditetapkan. Notifikasi terkirim ke pelanggan.');
    }

    public function sendWhatsapp($id)
    {
        $order = CateringOrder::findOrFail($id);

        if ($order->status !== 'menunggu_pembayaran') {
            return response()->json(['success' => false, 'message' => 'Status pesanan tidak memerlukan info pembayaran.'], 422);
        }

        if (empty($order->no_telepon)) {
            return response()->json(['success' => false, 'message' => 'Nomor telepon pemesan tidak tersedia.'], 422);
        }

        $message = $this->buildPaymentMessage($order);
        $result  = (new FonnteService())->send($order->no_telepon, $message);

        return response()->json($result);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'            => 'required|in:diproses,selesai,dibatalkan',
            'alasan_pembatalan' => 'required_if:status,dibatalkan|nullable|string|max:1000',
        ]);

        $order = CateringOrder::findOrFail($id);

        if ($order->status === 'pengajuan' && $request->status !== 'dibatalkan') {
            return back()->withErrors(['status' => 'Pesanan pengajuan hanya bisa dibatalkan.']);
        }

        $updateData = ['status' => $request->status];
        if ($request->status === 'dibatalkan') {
            $updateData['alasan_pembatalan'] = $request->alasan_pembatalan;
        }

        $order->update($updateData);

        $order->logHistory($request->status, $request->alasan_pembatalan ?? null);

        return back()->with('success', 'Status pesanan catering berhasil diperbarui.');
    }

    private function buildPaymentMessage(CateringOrder $order): string
    {
        $rincian = $order->costItems->map(
            fn ($i) => "  • {$i->label}: Rp " . number_format($i->amount, 0, ',', '.')
        )->join("\n");

        $total   = 'Rp ' . number_format((int) $order->total, 0, ',', '.');
        $linkUrl = route('catering.show', $order->id);

        return "💳 *Total Catering Ditetapkan — Ummilaa Kitchen*\n\n"
            . "Halo {$order->nama_pemesan}, total biaya pesanan catering Anda sudah ditetapkan.\n\n"
            . "🆔 ID Pesanan: #{$order->id}\n"
            . "🎉 Acara: {$order->nama_acara}\n\n"
            . ($rincian ? "*Rincian Biaya:*\n{$rincian}\n\n" : '')
            . "💰 *Total Akhir: {$total}*\n\n"
            . ($order->admin_notes ? "📝 Catatan: {$order->admin_notes}\n\n" : '')
            . "Silakan lakukan pembayaran di:\n{$linkUrl}\n\n"
            . "Terima kasih 🙏";
    }
}
