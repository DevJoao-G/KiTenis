<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MercadoPagoService
{
    /**
     * Cria uma preferência no Mercado Pago (Checkout Pro).
     *
     * @throws RuntimeException
     */
    public function createPreference(array $payload): array
    {
        $token = config('mercadopago.access_token');

        if (! $token) {
            throw new RuntimeException('MP_ACCESS_TOKEN não configurado.');
        }

        $req = Http::withToken($token)
            ->acceptJson()
            ->asJson()
            ->timeout(30);

        // ⚠️ Somente em DEV/LOCAL (se sua máquina tiver problemas de CA/certificados)
        if (app()->environment('local')) {
            $req = $req->withoutVerifying();
        }

        $resp = $req->post('https://api.mercadopago.com/checkout/preferences', $payload);

        if ($resp->failed()) {
            $status  = $resp->status();
            $bodyRaw = $resp->body();
            $json    = $resp->json();

            Log::error('Mercado Pago preference error', [
                'status' => $status,
                'json'   => $json,
                'raw'    => $bodyRaw,
            ]);

            // Em local, mostre a mensagem real do MP para facilitar debug
            if (app()->environment('local')) {
                $msg =
                    $json['message']
                    ?? $json['error_description']
                    ?? $json['error']
                    ?? $bodyRaw
                    ?? 'Erro desconhecido do Mercado Pago.';

                throw new RuntimeException("Mercado Pago ({$status}): {$msg}");
            }

            throw new RuntimeException('Falha ao criar preferência no Mercado Pago.');
        }

        return (array) $resp->json();
    }

    /**
     * Retorna a URL correta para redirecionar o usuário.
     */
    public function getRedirectUrl(array $preference): ?string
    {
        // Se estiver em sandbox e a API devolver sandbox_init_point, prioriza ele
        if (config('mercadopago.sandbox') && ! empty($preference['sandbox_init_point'])) {
            return $preference['sandbox_init_point'];
        }

        // Caso contrário, usa init_point
        if (! empty($preference['init_point'])) {
            return $preference['init_point'];
        }

        return null;
    }
}
