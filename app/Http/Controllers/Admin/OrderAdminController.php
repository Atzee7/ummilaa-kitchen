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