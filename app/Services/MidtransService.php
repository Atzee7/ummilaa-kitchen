<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

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
     * Tangani notifikasi webhook dari Midtrans (server-to-server).
     */
    public function handleNotification(array $payload): ?Order
    {
        if (!$this->isSignatureValid($payload)) {
            Log::warning('Midtrans webhook: signature invalid', [
                'order_id' => $payload['order_id'] ?? null,
            ]);
            return null;
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
     * Polling: ambil status terbaru dari Midtrans untuk order ini, lalu update.
     * Dipakai sebagai fallback kalau webhook tidak sampai.
     */
    public function syncStatusFromMidtrans(Order $order): ?Order
    {
        if (!$order->midtrans_transaction_id) {
            return null;
        }

        try {
            $statusObj = Transaction::status($order->midtrans_transaction_id);
            $payload   = json_decode(json_encode($statusObj), true);

            $updated = $this->updateOrderFromPayload($order, $payload);

            Log::info('Midtrans status disinkronisasi via polling', [
                'order_id'   => $order->id,
                'new_status' => $updated?->status,
                'tx_status'  => $payload['transaction_status'] ?? null,
            ]);

            return $updated;
        } catch (\Throwable $e) {
            Log::warning('Sync Midtrans status gagal', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
            return null;
        }
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
