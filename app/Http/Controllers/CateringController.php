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

    public function showPackage($id)
    {
        $package = CateringPackage::active()->findOrFail($id);
        return view('catering.package', compact('package'));
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
            'catering_package_id'  => 'required|exists:catering_packages,id',
            'nama_acara'           => 'required|string|max:255',
            'tanggal_acara'        => 'required|date|after_or_equal:today',
            'jam_acara'            => 'required|date_format:H:i',
            'jam_pengantaran'      => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:16:00',
            'jumlah_pax'           => 'required|integer|min:1',
            'lokasi_acara'         => 'required|string|max:1000',
            'detail_lokasi_acara'  => 'nullable|string|max:500',
            'catatan'              => 'nullable|string|max:1000',
            'nama_pemesan'         => 'required|string|max:255',
            'no_telepon'           => 'required|string|max:30',
        ]);

        // Validasi minimum pax sesuai paket yang dipilih
        $pkg = \App\Models\CateringPackage::find($validated['catering_package_id']);
        if ($pkg && $pkg->min_pax && (int) $validated['jumlah_pax'] < $pkg->min_pax) {
            return back()
                ->withErrors(['jumlah_pax' => "Minimum pemesanan untuk paket \"{$pkg->name}\" adalah {$pkg->min_pax} pax."])
                ->withInput();
        }

        $order = CateringOrder::create([
            'user_id'             => auth()->id(),
            'catering_package_id' => $validated['catering_package_id'],
            'nama_acara'          => $validated['nama_acara'],
            'tanggal_acara'       => $validated['tanggal_acara'],
            'jam_acara'           => $validated['jam_acara'],
            'jam_pengantaran'     => $validated['jam_pengantaran'],
            'jumlah_pax'          => $validated['jumlah_pax'],
            'lokasi_acara'        => $validated['lokasi_acara'],
            'detail_lokasi_acara' => $validated['detail_lokasi_acara'] ?? null,
            'catatan'             => $validated['catatan'] ?? null,
            'nama_pemesan'        => $validated['nama_pemesan'],
            'no_telepon'          => $validated['no_telepon'],
            'status'              => 'pengajuan',
        ]);

        $order->logHistory('pengajuan', 'Pesanan catering berhasil diajukan.');

        return redirect()
            ->route('catering.show', $order->id)
            ->with('wa_link', $this->buildWhatsappLink($order));
    }

    public function history()
    {
        CateringOrder::cancelExpiredUnpaidOrders();

        $orders = CateringOrder::with('package')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('catering.history', compact('orders'));
    }

    public function show($id)
    {
        CateringOrder::cancelExpiredUnpaidOrders();

        $order = CateringOrder::with(['package', 'costItems', 'histories'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('catering.show', [
            'order'   => $order,
            'waLink'  => $this->buildWhatsappLink($order),
        ]);
    }

    public function payment($id, MidtransService $midtrans)
    {
        CateringOrder::cancelExpiredUnpaidOrders();

        $order = CateringOrder::with('package')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if (!$order->isPayable()) {
            return redirect()
                ->route('catering.show', $order->id)
                ->with('error', 'Pesanan ini belum bisa dibayar.');
        }

        // Anchor deadline pada kunjungan pertama ke halaman bayar (seperti order catalogue
        // yang memakai created_at). Reload/kunjungan ulang tidak me-reset deadline.
        if (!$order->payment_expires_at) {
            $order->update([
                'payment_expires_at' => now()->addMinutes(CateringOrder::PAYMENT_TIMEOUT_MINUTES),
            ]);
        }

        // Generate token HANYA bila belum ada (self-heal). Token dipakai ulang agar
        // metode yang dipilih (mis. QRIS) tetap sama sampai waktu pembayaran berakhir.
        if (empty($order->snap_token) && !$order->isPaymentExpired()) {
            try {
                $midtrans->getSnapTokenForCatering($order);
                $order->refresh();
            } catch (\Throwable $e) {
                Log::error('Gagal generate Snap token catering', [
                    'catering_id' => $order->id,
                    'error'       => $e->getMessage(),
                ]);
            }
        }

        return view('catering.payment', compact('order'));
    }

    public function cancel(Request $request, $id)
    {
        CateringOrder::cancelExpiredUnpaidOrders();

        $order = CateringOrder::where('user_id', auth()->id())->findOrFail($id);

        if ($order->status !== 'pengajuan') {
            return redirect()->route('catering.show', $id)
                ->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        $validated = $request->validate([
            'alasan_pembatalan' => 'required|string|max:500',
        ]);

        $order->update([
            'status'            => 'dibatalkan',
            'alasan_pembatalan' => $validated['alasan_pembatalan'],
        ]);

        $order->logHistory('dibatalkan', 'Dibatalkan oleh pemesan: ' . $validated['alasan_pembatalan']);

        return redirect()->route('catering.history')
            ->with('success', 'Pesanan catering berhasil dibatalkan.');
    }

    private function buildWhatsappLink(CateringOrder $order): string
    {
        $number         = preg_replace('/\D/', '', (string) config('services.catering.wa_number'));
        $paket          = $order->package?->name ?? 'Custom (tanpa paket)';
        $tanggal        = $order->tanggal_acara?->translatedFormat('l, d F Y');
        $jamAcara       = $order->jam_acara ? \Carbon\Carbon::parse($order->jam_acara)->format('H:i') : '-';
        $jamPengantaran = $order->jam_pengantaran ? \Carbon\Carbon::parse($order->jam_pengantaran)->format('H:i') : '-';
        $detailLokasi   = $order->detail_lokasi_acara ? "\n  Detail: {$order->detail_lokasi_acara}" : '';
        $catatan        = $order->catatan ? "\n*Catatan:* {$order->catatan}" : '';

        $text = "Halo Admin Ummilaa Kitchen, saya ingin memesan *Catering*.\n\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "*DATA PEMESAN*\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "*ID Pengajuan:* #{$order->id}\n"
            . "*Nama:* {$order->nama_pemesan}\n"
            . "*No. Telepon:* {$order->no_telepon}\n\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "*DETAIL ACARA*\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "*Paket:* {$paket}\n"
            . "*Nama Acara:* {$order->nama_acara}\n"
            . "*Tanggal Acara:* {$tanggal}\n"
            . "*Jam Acara:* {$jamAcara} WIB\n"
            . "*Jam Pengantaran:* {$jamPengantaran} WIB\n"
            . "*Jumlah Pax:* {$order->jumlah_pax} porsi\n\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "*LOKASI ACARA*\n"
            . "━━━━━━━━━━━━━━━━━━━━━\n"
            . "{$order->lokasi_acara}{$detailLokasi}"
            . $catatan
            . "\n\n━━━━━━━━━━━━━━━━━━━━━\n"
            . "Mohon info ketersediaan & estimasi harganya ya. Terima kasih. 🙏";

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($text);
    }
}
