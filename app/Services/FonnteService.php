<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function send(string $phone, string $message): array
    {
        $token = config('services.fonnte.token');

        if (!$token) {
            Log::warning('FonnteService: FONNTE_TOKEN tidak dikonfigurasi.');
            return ['success' => false, 'message' => 'Token Fonnte tidak dikonfigurasi.'];
        }

        $phone = $this->formatPhone($phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target'  => $phone,
                'message' => $message,
            ]);

            $body = $response->json();

            if ($response->successful() && isset($body['status']) && $body['status'] === true) {
                return ['success' => true, 'message' => 'Pesan WhatsApp berhasil dikirim.'];
            }

            $errMsg = $body['reason'] ?? $body['message'] ?? 'Gagal mengirim pesan.';
            Log::warning('FonnteService: gagal kirim WA', ['response' => $body]);
            return ['success' => false, 'message' => $errMsg];
        } catch (\Exception $e) {
            Log::error('FonnteService: exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Terjadi kesalahan saat menghubungi Fonnte.'];
        }
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+')) {
            $phone = ltrim($phone, '+');
        }

        return $phone;
    }
}
