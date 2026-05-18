<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Midtrans webhook diterima', [
            'order_id'           => $payload['order_id'] ?? null,
            'transaction_status' => $payload['transaction_status'] ?? null,
            'fraud_status'       => $payload['fraud_status'] ?? null,
        ]);

        if (!$this->midtrans->isSignatureValid($payload)) {
            return response()->json(['ok' => false, 'error' => 'invalid signature'], 403);
        }

        $order = $this->midtrans->handleNotification($payload);

        if (!$order) {
            return response()->json(['ok' => false, 'error' => 'order not processed'], 200);
        }

        return response()->json(['ok' => true, 'order_id' => $order->id], 200);
    }
}
