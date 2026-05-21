<?php

namespace App\Services;

use App\Models\Order;
use App\Models\CateringOrder;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized  = (bool) config('services.midtrans.is_sanitized', true);
        Config::$is3ds        = (bool) config('services.midtrans.is_3ds', true);
    }

    /**
     * Bangun & simpan Snap token untuk order.
     */
    public function getSnapToken(Order $order): string
    {
        $order->loadMissing('items.product', 'user');

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'id'       => (string) $item->product_id,
                'price'    => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name'     => mb_substr($item->product->name ?? 'Produk', 0, 50),
            ];
        }

        if ((int) $order->ongkir > 0) {
            $items[] = [
                'id'       => 'ONGKIR',
                'price'    => (int) $order->ongkir,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        // Pakai start_time absolute (bukan duration relatif) agar deadline Midtrans
        // sinkron persis dengan deadline internal Laravel — keduanya berakhir di
        // created_at + PAYMENT_TIMEOUT_MINUTES, tidak bergantung kapan token di-generate.
        $startTime = $order->created_at->copy()->setTimezone('+0700')->format('Y-m-d H:i:s O');

        $payload = [
            'transaction_details' => [
                'order_id'     => $this->buildMidtransOrderId($order),
                'gross_amount' => (int) $order->total,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->nama_penerima,
                'phone'      => $order->no_telepon,
                'shipping_address' => [
                    'address' => $order->alamat,
                ],
            ],
            'expiry' => [
                'start_time' => $startTime,
                'unit'       => 'minute',
                'duration'   => Order::PAYMENT_TIMEOUT_MINUTES,
            ],
        ];

        $snapToken = Snap::getSnapToken($payload);

        $order->update([
            'snap_token'              => $snapToken,
            'midtrans_transaction_id' => $payload['transaction_details']['order_id'],
        ]);

        return $snapToken;
    }

    /**
     * Order ID unik untuk Midtrans (boleh berbeda dari Order::id).
     * Format: UMK-{order_id}-{timestamp} agar bisa retry tanpa konflik.
     */
    public function buildMidtransOrderId(Order $order): string
    {
        return 'UMK-' . $order->id . '-' . now()->format('YmdHis');
    }

    /**
     * Bangun & simpan Snap token untuk pesanan catering.
     * Total ditentukan admin (hasil nego), dikirim sebagai 1 item.
     * Order ID prefix UMK-CTR- agar webhook bisa membedakan dari order biasa.
     */
    public function getSnapTokenForCatering(CateringOrder $order): string
    {
        $order->loadMissing('user');

        $payload = [
            'transaction_details' => [
                'order_id'     => 'UMK-CTR-' . $order->id . '-' . now()->format('YmdHis'),
                'gross_amount' => (int) $order->total,
            ],
            'item_details' => [[
                'id'       => 'CATERING-' . $order->id,
                'price'    => (int) $order->total,
                'quantity' => 1,
                'name'     => mb_substr('Catering: ' . $order->nama_acara, 0, 50),
            ]],
            'customer_details' => [
                'first_name' => $order->nama_pemesan,
                'phone'      => $order->no_telepon,
            ],
            // start_time absolute disinkronkan ke deadline internal agar masa hidup
            // QR/QRIS di Midtrans berakhir persis di payment_expires_at — token dipakai
            // ulang sehingga metode yang dipilih tetap valid sampai waktu habis.
            'expiry' => [
                'start_time' => $order->payment_expires_at
                    ->copy()
                    ->subMinutes(CateringOrder::PAYMENT_TIMEOUT_MINUTES)
                    ->setTimezone('+0700')
                    ->format('Y-m-d H:i:s O'),
                'unit'       => 'minute',
                'duration'   => CateringOrder::PAYMENT_TIMEOUT_MINUTES,
            ],
        ];

        $snapToken = Snap::getSnapToken($payload);

        $order->update([
            'snap_token'              => $snapToken,
            'midtrans_transaction_id' => $payload['transaction_details']['order_id'],
        ]);

        return $snapToken;
    }

    /**
     * Tangani notifikasi webhook dari Midtrans (server-to-server).
     * Mengembalikan Order atau CateringOrder tergantung prefix order_id.
     */
    public function handleNotification(array $payload): ?object
    {
        if (!$this->isSignatureValid($payload)) {
            Log::warning('Midtrans webhook: signature invalid', [
                'order_id' => $payload['order_id'] ?? null,
            ]);
            return null;
        }

        // Catering (prefix UMK-CTR-) HARUS dicek lebih dulu sebelum order biasa.
        $cateringId = $this->extractCateringOrderId($payload['order_id'] ?? '');
        if ($cateringId) {
            return $this->handleCateringNotification($cateringId, $payload);
        }

        $order = $this->resolveOrderFromPayload($payload);
        if (!$order) return null;

        $updated = $this->updateOrderFromPayload($order, $payload);

        Log::info('Midtrans webhook diproses', [
            'order_id'  => $order->id,
            'new_status'=> $updated?->status,
            'tx_status' => $payload['transaction_status'] ?? null,
        ]);

        return $updated;
    }

    /**
     * Tangani notifikasi pembayaran catering.
     */
    private function handleCateringNotification(int $cateringId, array $payload): ?CateringOrder
    {
        $order = CateringOrder::find($cateringId);
        if (!$order) {
            Log::warning('Midtrans: catering order tidak ditemukan', ['catering_id' => $cateringId]);
            return null;
        }

        $newStatus = $this->mapCateringStatus(
            $payload['transaction_status'] ?? '',
            $payload['fraud_status'] ?? null
        );

        $updates = [
            'midtrans_transaction_id' => $payload['order_id'] ?? $order->midtrans_transaction_id,
            'payment_type'            => $payload['payment_type'] ?? $order->payment_type,
        ];

        // Pembayaran SUKSES → diproses.
        if ($newStatus === 'diproses' && $order->status !== 'diproses') {
            $updates['status'] = 'diproses';
            if (!$order->paid_at) {
                $updates['paid_at'] = now();
            }
        }

        // Transaksi expire/cancel/deny/failure membatalkan pesanan, tapi HANYA jika
        // masih menunggu pembayaran (jangan override pesanan yang sudah diproses).
        if ($newStatus === 'dibatalkan' && $order->status === 'menunggu_pembayaran') {
            $updates['status'] = 'dibatalkan';
            if (!$order->alasan_pembatalan) {
                $updates['alasan_pembatalan'] = 'Pembayaran ' . ($payload['transaction_status'] ?? 'gagal') . ' di Midtrans';
            }
        }

        $order->update($updates);

        Log::info('Midtrans webhook catering diproses', [
            'catering_id' => $order->id,
            'new_status'  => $order->status,
            'tx_status'   => $payload['transaction_status'] ?? null,
        ]);

        return $order->fresh();
    }

    /**
     * Resolve Order Eloquent dari payload Midtrans (cek order_id).
     */
    private function resolveOrderFromPayload(array $payload): ?Order
    {
        $orderId = $this->extractInternalOrderId($payload['order_id'] ?? '');
        if (!$orderId) {
            Log::warning('Midtrans: order_id tidak dikenali', ['raw' => $payload['order_id'] ?? null]);
            return null;
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::warning('Midtrans: order tidak ditemukan', ['order_id' => $orderId]);
            return null;
        }

        return $order;
    }

    /**
     * Update kolom-kolom Order berdasarkan payload (dari webhook ATAU dari polling status).
     */
    private function updateOrderFromPayload(Order $order, array $payload): ?Order
    {
        $newStatus = $this->mapStatus(
            $payload['transaction_status'] ?? '',
            $payload['fraud_status'] ?? null
        );

        $updates = [
            'midtrans_transaction_id' => $payload['order_id'] ?? $order->midtrans_transaction_id,
            'payment_type'            => $payload['payment_type'] ?? $order->payment_type,
        ];

        if ($newStatus !== null) {
            // Jangan downgrade order yang sudah pending/diproses ke belum_bayar.
            if (!in_array($order->status, ['belum_bayar', 'dibatalkan']) && $newStatus === 'belum_bayar') {
                $newStatus = $order->status;
            }

            $updates['status'] = $newStatus;

            if ($newStatus === 'pending' && !$order->paid_at) {
                $updates['paid_at'] = now();
            }

            if ($newStatus === 'dibatalkan' && !$order->alasan_pembatalan) {
                $updates['alasan_pembatalan'] = 'Pembayaran ' . ($payload['transaction_status'] ?? 'gagal') . ' di Midtrans';
            }
        }

        $order->update($updates);

        return $order->fresh();
    }

    /**
     * Verifikasi signature SHA512 dari payload Midtrans.
     */
    public function isSignatureValid(array $payload): bool
    {
        $orderId      = $payload['order_id']      ?? '';
        $statusCode   = $payload['status_code']   ?? '';
        $grossAmount  = $payload['gross_amount']  ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signatureKey === '') {
            return false;
        }

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . config('services.midtrans.server_key'));
        return hash_equals($expected, $signatureKey);
    }

    /**
     * Ekstrak Order::id internal dari midtrans order_id (format UMK-{id}-{timestamp}).
     */
    public function extractInternalOrderId(string $midtransOrderId): ?int
    {
        if (preg_match('/^UMK-(\d+)-/', $midtransOrderId, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    /**
     * Ekstrak CateringOrder::id dari midtrans order_id (format UMK-CTR-{id}-{timestamp}).
     */
    public function extractCateringOrderId(string $midtransOrderId): ?int
    {
        if (preg_match('/^UMK-CTR-(\d+)-/', $midtransOrderId, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    /**
     * Map status Midtrans → status sistem catering.
     */
    public function mapCateringStatus(string $transactionStatus, ?string $fraudStatus): ?string
    {
        return match ($transactionStatus) {
            'capture'    => ($fraudStatus === 'challenge') ? null : 'diproses',
            'settlement' => 'diproses',
            'expire', 'cancel', 'deny', 'failure' => 'dibatalkan',
            // pending → null: status catering tidak diubah (masih menunggu pembayaran).
            default      => null,
        };
    }

    /**
     * Map status Midtrans → status sistem Ummilaa Kitchen.
     */
    public function mapStatus(string $transactionStatus, ?string $fraudStatus): ?string
    {
        return match ($transactionStatus) {
            'capture'    => ($fraudStatus === 'challenge') ? 'belum_bayar' : 'pending',
            'settlement' => 'pending',
            'pending'    => 'belum_bayar',
            'deny', 'cancel', 'expire', 'failure' => 'dibatalkan',
            default      => null,
        };
    }
}
