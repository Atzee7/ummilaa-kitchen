<?php

namespace App\Http\Controllers;

use App\Models\CateringOrder;
use App\Models\CateringPackage;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CateringController extends Controller
{
    public function index()
    {
        $packages = CateringPackage::active()->latest()->get();
        return view('catering.index', compact('packages'));
    }

    public function checkout(Request $request)
    {
        $package = null;
        if ($request->filled('package')) {
            $package = CateringPackage::active()->find($request->get('package'));
        }

        $packages = CateringPackage::active()->latest()->get();

        return view('catering.checkout', compact('package', 'packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'catering_package_id' => 'nullable|exists:catering_packages,id',
            'nama_acara'          => 'required|string|max:255',
            'tanggal_acara'       => 'required|date|after_or_equal:today',
            'jumlah_pax'          => 'required|integer|min:1',
            'lokasi_acara'        => 'required|string|max:1000',
            'catatan'             => 'nullable|string|max:1000',
            'nama_pemesan'        => 'required|string|max:255',
            'no_telepon'          => 'required|string|max:30',
        ]);

        $order = CateringOrder::create([
            'user_id'             => auth()->id(),
            'catering_package_id' => $validated['catering_package_id'] ?? null,
            'nama_acara'          => $validated['nama_acara'],
            'tanggal_acara'       => $validated['tanggal_acara'],
            'jumlah_pax'          => $validated['jumlah_pax'],
            'lokasi_acara'        => $validated['lokasi_acara'],
            'catatan'             => $validated['catatan'] ?? null,
            'nama_pemesan'        => $validated['nama_pemesan'],
            'no_telepon'          => $validated['no_telepon'],
            'status'              => 'pengajuan',
        ]);

        return redirect()
            ->route('catering.show', $order->id)
            ->with('wa_link', $this->buildWhatsappLink($order));
    }

    public function history()
    {
        $orders = CateringOrder::with('package')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('catering.history', compact('orders'));
    }

    public function show($id)
    {
        $order = CateringOrder::with('package')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('catering.show', [
            'order'   => $order,
            'waLink'  => session('wa_link') ?? $this->buildWhatsappLink($order),
        ]);
    }

    public function payment($id, MidtransService $midtrans)
    {
        $order = CateringOrder::with('package')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if (!$order->isPayable()) {
            return redirect()
                ->route('catering.show', $order->id)
                ->with('error', 'Pesanan ini belum bisa dibayar.');
        }

        // Selalu generate token (order_id) BARU setiap halaman bayar dibuka.
        // QR GoPay/QRIS hanya hidup ~15 menit, sedangkan window bayar catering panjang;
        // memakai ulang token lama membuat QR yang di-scan sudah kedaluwarsa (error 2603).
        // Webhook tetap aman: regex UMK-CTR-(\d+) resolve ke catering id yang sama,
        // transaksi pending lama akan otomatis expire di Midtrans.
        try {
            $midtrans->getSnapTokenForCatering($order);
            $order->refresh();
        } catch (\Throwable $e) {
            Log::error('Gagal generate Snap token catering', [
                'catering_id' => $order->id,
                'error'       => $e->getMessage(),
            ]);
        }

        return view('catering.payment', compact('order'));
    }

    private function buildWhatsappLink(CateringOrder $order): string
    {
        $number  = preg_replace('/\D/', '', (string) config('services.catering.wa_number'));
        $paket   = $order->package?->name ?? 'Custom (tanpa paket)';
        $tanggal = $order->tanggal_acara?->translatedFormat('l, d F Y');
        $catatan = $order->catatan ? "\nCatatan: {$order->catatan}" : '';

        $text = "Halo Admin Ummilaa Kitchen, saya ingin memesan *Catering*.\n\n"
            . "*ID Pengajuan:* #{$order->id}\n"
            . "*Nama Pemesan:* {$order->nama_pemesan}\n"
            . "*No. Telepon:* {$order->no_telepon}\n"
            . "*Paket:* {$paket}\n"
            . "*Nama Acara:* {$order->nama_acara}\n"
            . "*Tanggal Acara:* {$tanggal}\n"
            . "*Jumlah Pax:* {$order->jumlah_pax} porsi\n"
            . "*Lokasi Acara:* {$order->lokasi_acara}"
            . $catatan
            . "\n\nMohon info ketersediaan & estimasi harganya ya. Terima kasih.";

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($text);
    }
}
