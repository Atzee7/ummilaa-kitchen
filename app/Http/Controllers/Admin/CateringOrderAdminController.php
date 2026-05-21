<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateringOrder;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class CateringOrderAdminController extends Controller
{
    public function index(Request $request)
    {
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
        $order = CateringOrder::with(['user', 'package'])->findOrFail($id);
        return view('admin.catering-orders.show', compact('order'));
    }

    public function openPayment(Request $request, $id)
    {
        $request->validate([
            'total' => 'required|integer|min:1',
        ]);

        $order = CateringOrder::findOrFail($id);

        if ($order->status !== 'pengajuan') {
            return back()->withErrors(['total' => 'Pembayaran hanya bisa dibuka untuk pesanan berstatus "Pengajuan".']);
        }

        $order->update([
            'total'      => $request->total,
            'status'     => 'menunggu_pembayaran',
            'snap_token' => null, // reset agar token di-generate ulang dengan total final
        ]);

        if (!empty($order->no_telepon)) {
            session(['catering_wa_prompt' => $order->id]);
        }

        return back()->with('success', 'Pembayaran berhasil dibuka. Total tagihan telah ditetapkan.');
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

        $updateData = ['status' => $request->status];
        if ($request->status === 'dibatalkan') {
            $updateData['alasan_pembatalan'] = $request->alasan_pembatalan;
        }

        $order->update($updateData);

        return back()->with('success', 'Status pesanan catering berhasil diperbarui.');
    }

    private function buildPaymentMessage(CateringOrder $order): string
    {
        $total   = 'Rp ' . number_format((int) $order->total, 0, ',', '.');
        $linkUrl = route('catering.show', $order->id);

        return "💳 *Pembayaran Catering Dibuka — Ummilaa Kitchen*\n\n"
            . "Halo {$order->nama_pemesan}, pesanan catering Anda sudah dikonfirmasi.\n\n"
            . "🆔 ID Pesanan: #{$order->id}\n"
            . "🎉 Acara: {$order->nama_acara}\n"
            . "💰 Total Tagihan: {$total}\n\n"
            . "Silakan lakukan pembayaran melalui halaman *Riwayat Catering* di website kami:\n{$linkUrl}\n\n"
            . "Terima kasih 🙏";
    }
}
