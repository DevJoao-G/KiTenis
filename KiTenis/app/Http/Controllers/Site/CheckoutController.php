<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\MercadoPagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly MercadoPagoService $mp,
    ) {}

    public function mercadoPago(Request $request): RedirectResponse
    {
        // ✅ Validação dos names reais do seu formulário (Blade)
        $data = $request->validate([
            'buyer_name'  => ['required', 'string', 'max:150'],
            'buyer_cpf'   => ['required', 'string', 'max:20'],
            'buyer_phone' => ['required', 'string', 'max:30'],
            'buyer_birth' => ['required', 'date'],

            'ship_cep'        => ['required', 'string', 'max:15'],
            'ship_street'     => ['required', 'string', 'max:200'],
            'ship_number'     => ['required', 'string', 'max:30'],
            'ship_district'   => ['required', 'string', 'max:120'],
            'ship_city'       => ['required', 'string', 'max:120'],
            'ship_uf'         => ['required', 'string', 'size:2'],
            'ship_complement' => ['nullable', 'string', 'max:120'],

            'payment_method' => ['required', 'in:card,boleto,pix'],
        ]);

        // Dados do carrinho
        [$items, $total, $count] = $this->cart->buildCartViewData($request);

        if (($count ?? 0) <= 0) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        // Itens no formato do Mercado Pago
        $mpItems = [];
        foreach ($items as $it) {
            $p = $it['product'];

            $title = (string) $p->name;

            $variant = trim(
                ($it['size'] ? 'Tam ' . $it['size'] : '') .
                ($it['color'] ? ' ' . $it['color'] : '')
            );

            if ($variant !== '') {
                $title .= ' (' . $variant . ')';
            }

            $qty  = (int) ($it['qty'] ?? 0);
            $unit = (float) ($it['unit'] ?? 0);

            if ($qty <= 0 || $unit <= 0) {
                continue;
            }

            $mpItems[] = [
                'title'       => $title,
                'quantity'    => $qty,
                'unit_price'  => round($unit, 2),
                'currency_id' => config('mercadopago.currency', 'BRL'),
            ];
        }

        if (empty($mpItems)) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho não possui itens válidos para pagamento.');
        }

        // Draft na sessão (sem dados sensíveis)
        $request->session()->put('checkout_draft', [
            'buyer' => [
                'name'  => $data['buyer_name'],
                'cpf'   => $data['buyer_cpf'],
                'phone' => $data['buyer_phone'],
                'birth' => $data['buyer_birth'],
            ],
            'shipping' => [
                'cep'        => $data['ship_cep'],
                'street'     => $data['ship_street'],
                'number'     => $data['ship_number'],
                'district'   => $data['ship_district'],
                'city'       => $data['ship_city'],
                'uf'         => strtoupper($data['ship_uf']),
                'complement' => $data['ship_complement'] ?? null,
            ],
            'payment_method' => $data['payment_method'],
            'total' => (float) $total,
        ]);

        // External reference (não quebra se não tiver user)
        $userId = optional($request->user())->id;
        $externalReference = 'user:' . ($userId ?? 'guest') . '|ts:' . now()->timestamp;

        // Payload base
        $payload = [
            'items' => $mpItems,
            'payer' => [
                'name' => $data['buyer_name'],
                'identification' => [
                    'type'   => 'CPF',
                    'number' => preg_replace('/\D+/', '', $data['buyer_cpf']),
                ],
                'phone' => [
                    'number' => preg_replace('/\D+/', '', $data['buyer_phone']),
                ],
            ],
            'external_reference' => $externalReference,
        ];

        /**
         * ✅ Checkout Pro decide o que aparece.
         * Aqui a gente só filtra de acordo com o método escolhido no modal.
         *
         * IMPORTANTÍSSIMO:
         * - NÃO usar default_payment_method_id (dá 400 quando o MP considera inválido/excluído)
         */
        $method = $data['payment_method'];

        if ($method === 'pix') {
            // deixa só transferências (PIX quando disponível entra aqui)
            $payload['payment_methods'] = [
                'excluded_payment_types' => [
                    ['id' => 'credit_card'],
                    ['id' => 'debit_card'],
                    ['id' => 'ticket'],
                    ['id' => 'atm'],
                ],
            ];
        } elseif ($method === 'boleto') {
            // deixa só boleto (ticket)
            $payload['payment_methods'] = [
                'excluded_payment_types' => [
                    ['id' => 'credit_card'],
                    ['id' => 'debit_card'],
                    ['id' => 'bank_transfer'],
                    ['id' => 'atm'],
                ],
            ];
        } else {
            // cartão
            $payload['payment_methods'] = [
                'excluded_payment_types' => [
                    ['id' => 'ticket'],
                    ['id' => 'bank_transfer'],
                    ['id' => 'atm'],
                ],
                'installments' => 10,
            ];
        }

        /**
         * ✅ back_urls/auto_return:
         * MP costuma exigir HTTPS para back_urls. Em LAN/local HTTP pode invalidar.
         * Então só envia quando APP_URL for https:// (domínio / ngrok https).
         */
        $appUrl = (string) config('app.url');
        $isHttps = str_starts_with($appUrl, 'https://');

        if ($isHttps) {
            $payload['back_urls'] = [
                'success' => url('/checkout/success'),
                'failure' => url('/checkout/failure'),
                'pending' => url('/checkout/pending'),
            ];
            $payload['auto_return'] = 'approved';
        }

        // Cria preferência
        $pref = $this->mp->createPreference($payload);

        /**
         * ✅ DEBUG OPCIONAL (pra destravar "uma das partes é de teste")
         * Para usar:
         * - adicione temporariamente no form: <input type="hidden" name="debug_mp" value="1">
         * - ou teste com Postman
         */
        if ($request->boolean('debug_mp') || $request->has('debug_mp')) {
            dd([
                'sandbox' => config('mercadopago.sandbox'),
                'preference_id' => $pref['id'] ?? null,
                'collector_id'  => $pref['collector_id'] ?? null,
                'sandbox_init_point' => $pref['sandbox_init_point'] ?? null,
                'init_point' => $pref['init_point'] ?? null,
            ]);
        }

        // URL para redirecionar
        $redirect = $this->mp->getRedirectUrl($pref);

        if (! $redirect) {
            return redirect()->route('cart.index')->with('error', 'Não foi possível obter URL de pagamento do Mercado Pago.');
        }

        return redirect()->away($redirect);
    }

    public function success(Request $request)
    {
        return view('site.checkout.success', [
            'query' => $request->query(),
        ]);
    }

    public function failure(Request $request)
    {
        return view('site.checkout.failure', [
            'query' => $request->query(),
        ]);
    }

    public function pending(Request $request)
    {
        return view('site.checkout.pending', [
            'query' => $request->query(),
        ]);
    }
}
