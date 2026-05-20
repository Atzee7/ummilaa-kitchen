<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        Order::cancelExpiredUnpaidOrders();

        $status = $request->get('status', 'semua');
        $date   = $request->get('date', today()->format('Y-m-d'));

        // Admin tidak melihat pesanan belum_bayar — itu state user-action
        // yang otomatis di-cancel kalau lewat 10 menit, tidak butuh aksi admin.
        // excludeAutoCancelled() menyembunyikan pesanan yang dibatalkan otomatis
        // sistem (Midtrans expire/fail) — hanya pembatalan oleh admin yang tampil.
        $baseQuery = Order::query()
            ->whereDate('created_at', $date)
            ->where('status', '!=', 'belum_bayar')
            ->excludeAutoCancelled()
            ->where(function ($q) use ($date) {
                $q->whereNull('tanggal_pengiriman')
                  ->orWhereDate('tanggal_pengiriman', $date);
            });

        $query = (clone $baseQuery)->with('user')->latest();

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(5);

        $counts = [
            'semua'        => (clone $baseQuery)->count(),
            'pending'      => (clone $baseQuery)->where('status', 'pending')->count(),
            'diproses'     => (clone $baseQuery)->where('status', 'diproses')->count(),
            'dikirim'      => (clone $baseQuery)->where('status', 'dikirim')->count(),
            'siap_diambil' => (clone $baseQuery)->where('status', 'siap_diambil')->count(),
            'selesai'      => (clone $baseQuery)->where('status', 'selesai')->count(),
            'dibatalkan'   => (clone $baseQuery)->where('status', 'dibatalkan')->count(),
        ];

        $scheduledOrders = Order::query()
            ->with('user')
            ->whereDate('tanggal_pengiriman', '>', today())
            ->where('status', '!=', 'belum_bayar')
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->excludeAutoCancelled()
            ->orderBy('tanggal_pengiriman')
            ->orderBy('waktu_pengiriman')
            ->paginate(8, ['*'], 'spage');

        return view('admin.orders.index', compact('orders', 'status', 'counts', 'date', 'scheduledOrders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'            => 'required|in:belum_bayar,pending,diproses,dikirim,siap_diambil,selesai,dibatalkan',
            'alasan_pembatalan' => 'required_if:status,dibatalkan|nullable|string|max:1000',
        ]);

        $order = Order::findOrFail($id);

        if ($request->status === 'dibatalkan' && $order->status !== 'pending') {
            $message = 'Pembatalan hanya dapat dilakukan saat status pesanan masih "Menunggu".';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }
            return back()->withErrors(['status' => $message]);
        }

        if ($request->status !== 'dibatalkan' && $order->tanggal_pengiriman) {
            if ($order->tanggal_pengiriman->isFuture()) {
                $msg = 'Pesanan ini dijadwalkan untuk ' . $order->tanggal_pengiriman->translatedFormat('l, d F Y') . '. Belum bisa diproses sebelum harinya tiba.';
                return $request->expectsJson()
                    ? response()->json(['message' => $msg], 422)
                    : back()->withErrors(['status' => $msg]);
            }
            if ($order->tanggal_pengiriman->isToday() && $order->waktu_pengiriman) {
                $startTime = substr($order->waktu_pengiriman, 0, 5);
                if (now()->format('H:i') < $startTime) {
                    $msg = "Pesanan ini baru bisa diproses mulai jam {$startTime}.";
                    return $request->expectsJson()
                        ? response()->json(['message' => $msg], 422)
                        : back()->withErrors(['status' => $msg]);
                }
            }
        }

        $updateData = ['status' => $request->status];
        if ($request->status === 'dibatalkan') {
            $updateData['alasan_pembatalan'] = $request->alasan_pembatalan;
        }

        $order->update($updateData);
        $order->load('user');

        $hasPhone = !empty(optional($order->user)->no_telepon);

        if ($request->expectsJson()) {
            return response()->json([
                'status'    => $order->status,
                'has_phone' => $hasPhone,
            ]);
        }

        if (in_array($request->status, ['dikirim', 'siap_diambil']) && $hasPhone) {
            session(['wa_prompt' => $order->id]);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function modalContent($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders._modal_content', compact('order'));
    }

    public function sendWhatsapp($id)
    {
        $order = Order::with('user')->findOrFail($id);

        if (!in_array($order->status, ['dikirim', 'siap_diambil'])) {
            return response()->json(['success' => false, 'message' => 'Status pesanan tidak memerlukan konfirmasi WA.'], 422);
        }

        $phone = optional($order->user)->no_telepon;
        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Nomor telepon pembeli tidak tersedia di profil.'], 422);
        }

        $message = $this->buildWhatsappMessage($order);
        $fonnte  = new FonnteService();
        $result  = $fonnte->send($phone, $message);

        return response()->json($result);
    }

    private function buildWhatsappMessage(Order $order): string
    {
        $total = 'Rp ' . number_format($order->total, 0, ',', '.');

        if ($order->status === 'dikirim') {
            $detail = $order->detail_alamat ? "\n🏠 Detail: {$order->detail_alamat}" : '';

            $user = $order->user;
            if ($user && $user->lat && $user->lng) {
                $mapsLink = "https://www.google.com/maps?q={$user->lat},{$user->lng}";
            } else {
                $mapsLink = "https://www.google.com/maps/search/?api=1&query=" . urlencode($order->alamat);
            }

            return "🛵 *Pesanan #{$order->id} sedang diantar!*\n\nHalo {$order->nama_penerima}, makananmu dari *Ummila Kitchen* sudah dalam perjalanan ya!\n\n📍 Alamat: {$order->alamat}{$detail}\n🗺️ Link Google Maps: {$mapsLink}\n⏱️ Estimasi: 15–30 menit\n💰 Total: {$total}\n\nKami akan info kembali jika kurir sudah tiba di lokasimu. 🔔\n\nTerima kasih sudah berbelanja! Selamat menikmati 🍽️";
        }

        return "✅ *Pesanan #{$order->id} siap diambil!*\n\nHalo {$order->nama_penerima}, makananmu dari *Ummila Kitchen* sudah siap ya!\n\n🏪 Alamat Toko: Jalan Kapi Anala 1 Blok 15N No. 18, Sawojajar 2, Kota Malang, Jawa Timur\n🕐 Jam Operasional: 08.00 – 20.00\n💰 Total: {$total}\n\nSegera ambil pesananmu sebelum 30 menit ya, agar tetap hangat! 🔥\n\nTerima kasih sudah berbelanja! Selamat menikmati 🍽️";
    }
}