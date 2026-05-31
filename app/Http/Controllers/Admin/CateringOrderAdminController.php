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
        if ($status === 'semua') {
            $query->whereNotIn('status', ['selesai', 'dibatalkan']);
        } elseif ($status === 'dibatalkan') {
            $query->where('status', 'dibatalkan')->where('cancelled_by', 'admin');
        } else {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        $counts = [
            'semua'               => (clone $baseQuery)->whereNotIn('status', ['selesai', 'dibatalkan'])->count(),
            'pengajuan'           => (clone $baseQuery)->where('status', 'pengajuan')->count(),
            'menunggu_pembayaran' => (clone $baseQuery)->where('status', 'menunggu_pembayaran')->count(),
            'terkonfirmasi'       => (clone $baseQuery)->where('status', 'terkonfirmasi')->count(),
            'diproses'            => (clone $baseQuery)->where('status', 'diproses')->count(),
            'dikirim'             => (clone $baseQuery)->where('status', 'dikirim')->count(),
            'selesai'             => (clone $baseQuery)->where('status', 'selesai')->count(),
            'dibatalkan'          => (clone $baseQuery)->where('status', 'dibatalkan')->where('cancelled_by', 'admin')->count(),
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
            'status'            => 'required|in:terkonfirmasi,diproses,dikirim,selesai,dibatalkan',
            'alasan_pembatalan' => 'required_if:status,dibatalkan|nullable|string|max:1000',
        ]);

        $order = CateringOrder::findOrFail($id);

        if ($order->status === 'pengajuan' && $request->status !== 'dibatalkan') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Pesanan pengajuan hanya bisa dibatalkan.'], 422);
            }
            return back()->withErrors(['status' => 'Pesanan pengajuan hanya bisa dibatalkan.']);
        }

        if ($order->status === 'menunggu_pembayaran' && !in_array($request->status, ['terkonfirmasi', 'dibatalkan'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Menunggu pembayaran dari customer. Status tidak bisa diubah secara manual.'], 422);
            }
            return back()->withErrors(['status' => 'Menunggu pembayaran dari customer. Status tidak bisa diubah secara manual.']);
        }

        if ($order->status === 'terkonfirmasi' && !in_array($request->status, ['diproses', 'dibatalkan'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Dari status terkonfirmasi hanya bisa mulai proses atau dibatalkan.'], 422);
            }
            return back()->withErrors(['status' => 'Dari status terkonfirmasi hanya bisa mulai proses atau dibatalkan.']);
        }

        $updateData = ['status' => $request->status];
        if ($request->status === 'dibatalkan') {
            $updateData['alasan_pembatalan'] = $request->alasan_pembatalan;
            $updateData['cancelled_by']      = 'admin';
        }

        $order->update($updateData);
        $order->logHistory($request->status, $request->alasan_pembatalan ?? null);

        $sentWa = false;
        if ($request->status === 'dikirim' && !empty($order->no_telepon)) {
            try {
                (new FonnteService())->send($order->no_telepon, $this->buildDeliveryMessage($order));
                $sentWa = true;
            } catch (\Throwable $e) {
                Log::error('Gagal kirim WA dikirim catering', ['id' => $order->id, 'error' => $e->getMessage()]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => $request->status,
                'sent_wa' => $sentWa,
            ]);
        }

        return back()->with('success', 'Status pesanan catering berhasil diperbarui.');
    }

    private function buildDeliveryMessage(CateringOrder $order): string
    {
        $tanggal        = $order->tanggal_acara->translatedFormat('l, d F Y');
        $jamPengantaran = $order->jam_pengantaran
            ? \Carbon\Carbon::parse($order->jam_pengantaran)->format('H:i') . ' WIB'
            : '-';
        $mapsLink     = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($order->lokasi_acara);
        $detailLokasi = $order->detail_lokasi_acara ? "\nDetail: {$order->detail_lokasi_acara}" : '';

        return "🚚 *Pesanan Catering Sedang Diantarkan!*\n\n"
            . "Halo *{$order->nama_pemesan}*, pesanan catering Anda sedang dalam perjalanan ke lokasi acara.\n\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "*DETAIL PESANAN*\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "🆔 ID Pesanan: #{$order->id}\n"
            . "🎉 Acara: {$order->nama_acara}\n"
            . "📅 Tanggal: {$tanggal}\n"
            . "⏰ Jam Pengantaran: {$jamPengantaran}\n\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "*LOKASI TUJUAN*\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "{$order->lokasi_acara}{$detailLokasi}\n"
            . "🗺️ Lihat di Maps: {$mapsLink}\n\n"
            . "Mohon siapkan penerimaan ya! Terima kasih 🙏";
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
