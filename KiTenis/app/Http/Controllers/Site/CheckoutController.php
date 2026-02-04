<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\DB;
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

        // Guarda metadados pra confirmar no retorno (mesmo que seja simulação)
        $request->session()->put('checkout_mp', [
            'external_reference' => $externalReference,
            'preference_id' => $pref['id'] ?? null,
        ]);

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
        $user = $request->user();

        // Se o usuário caiu aqui sem sessão/auth, não dá pra finalizar o pedido.
        if (! $user) {
            return view('site.checkout.success', [
                'query' => $request->query(),
                'order' => null,
                'warning' => 'Pagamento retornou com sucesso, mas sua sessão expirou. Faça login e tente novamente.',
            ]);
        }

        // Evita duplicar pedido (refresh/back)
        $mp = (array) $request->session()->get('checkout_mp', []);
        $draft = (array) $request->session()->get('checkout_draft', []);

        $externalReference = $mp['external_reference'] ?? null;
        $existing = null;

        if ($externalReference) {
            $existing = Order::query()
                ->where('user_id', $user->id)
                ->where('external_reference', $externalReference)
                ->with(['items.product'])
                ->first();
        }

        if ($existing) {
            // Garante carrinho limpo
            $this->cart->clear($request);
            $request->session()->forget(['checkout_draft', 'checkout_mp']);

            return view('site.checkout.success', [
                'query' => $request->query(),
                'order' => $existing,
                'warning' => null,
            ]);
        }

        // Monta dados do carrinho (o que será vendido)
        [$items, $total, $count] = $this->cart->buildCartViewData($request);

        if (($count ?? 0) <= 0) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        $created = DB::transaction(function () use ($request, $user, $items, $total, $draft, $externalReference, $mp) {
            // Trava os produtos pra não vender acima do estoque
            $productIds = collect($items)->pluck('product.id')->filter()->unique()->values()->all();

            $products = \App\Models\Product::query()
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($items as $it) {
                $p = $products->get($it['product']->id);
                $qty = (int) ($it['qty'] ?? 0);
                if (! $p || $qty <= 0) {
                    continue;
                }

                if ((int) $p->stock < $qty) {
                    throw new \RuntimeException("Estoque insuficiente para {$p->name}.");
                }

                $p->stock = (int) $p->stock - $qty;
                $p->save();
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total' => (float) $total,
                // "approved" (success) = já considerar como venda efetivada
                'status' => 'processing',
                'external_reference' => $externalReference,
                'preference_id' => $mp['preference_id'] ?? null,
                'payment_method' => $draft['payment_method'] ?? null,
                'paid_at' => now(),
            ]);

            foreach ($items as $it) {
                $p = $it['product'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'quantity' => (int) ($it['qty'] ?? 0),
                    'price' => (float) ($it['unit'] ?? 0),
                    'size' => $it['size'] ?? null,
                    'color' => $it['color'] ?? null,
                ]);
            }

            return $order->load(['items.product']);
        });

        // Carrinho e rascunho resolvidos
        $this->cart->clear($request);
        $request->session()->forget(['checkout_draft', 'checkout_mp']);

        return view('site.checkout.success', [
            'query' => $request->query(),
            'order' => $created,
            'warning' => null,
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
