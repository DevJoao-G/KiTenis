<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        [$items, $total, $count] = $this->buildCartViewData($request);

        return view('site.cart.index', [
            'items' => $items,
            'total' => $total,
            'count' => $count,
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'size' => ['required', 'string', 'max:10'],
            'color' => ['required', 'string', 'max:30'],
            'qty' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $product = Product::query()->findOrFail($data['product_id']);

        if (! $product->active || $product->stock <= 0) {
            return back()->with('error', 'Produto indisponível no momento.')->withInput();
        }

        $qty = (int) $data['qty'];

        if ($qty > (int) $product->stock) {
            return back()->withErrors(['qty' => 'Quantidade maior que o estoque disponível.'])->withInput();
        }

        $key = sha1($product->id . '|' . $data['size'] . '|' . $data['color']);

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$key])) {
            $newQty = ((int) ($cart[$key]['qty'] ?? 1)) + $qty;
            $newQty = min(10, $newQty);
            $newQty = min($newQty, (int) $product->stock);

            $cart[$key]['qty'] = $newQty;
        } else {
            $cart[$key] = [
                'product_id' => (int) $product->id,
                'size' => (string) $data['size'],
                'color' => (string) $data['color'],
                'qty' => min($qty, 10),
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produto adicionado ao carrinho.');
    }

    public function update(Request $request, string $key): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:inc,dec'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (! isset($cart[$key])) {
            return $this->respond($request, [
                'ok' => false,
                'error' => 'Item não encontrado no carrinho.',
            ], 404);
        }

        $item = $cart[$key];
        $product = Product::query()->find($item['product_id'] ?? null);

        if (! $product) {
            unset($cart[$key]);
            $request->session()->put('cart', $cart);

            return $this->respond($request, [
                'ok' => false,
                'error' => 'Produto não encontrado. Removemos o item do carrinho.',
                ...$this->summary($request),
                'removed' => true,
                'key' => $key,
            ], 200);
        }

        $qty = (int) ($item['qty'] ?? 1);
        $qty = max(1, min(10, $qty));

        if ($data['action'] === 'inc') {
            $qty++;
        } else {
            $qty--;
        }

        $qty = max(1, min(10, $qty));
        $qty = min($qty, max(1, (int) $product->stock));

        $cart[$key]['qty'] = $qty;
        $request->session()->put('cart', $cart);

        // Recalcula preços do item e do carrinho
        $unit = $product->is_promotion_active ? (float) $product->discounted_price : (float) $product->price;
        $itemSubtotal = $unit * $qty;

        return $this->respond($request, [
            'ok' => true,
            'key' => $key,
            'qty' => $qty,
            'itemSubtotal' => $itemSubtotal,
            ...$this->summary($request),
        ], 200);
    }

    public function remove(Request $request, string $key): JsonResponse|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            $request->session()->put('cart', $cart);
        }

        return $this->respond($request, [
            'ok' => true,
            'removed' => true,
            'key' => $key,
            ...$this->summary($request),
        ], 200);
    }

    /**
     * ---- Helpers ----
     */

    private function respond(Request $request, array $payload, int $status = 200): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($payload, $status);
        }

        // fallback para requests normais
        if (($payload['ok'] ?? true) === false) {
            return back()->with('error', $payload['error'] ?? 'Não foi possível atualizar o carrinho.');
        }

        return back();
    }

    private function summary(Request $request): array
    {
        [$items, $total, $count] = $this->buildCartViewData($request);

        // regra de frete da view
        $shippingFreeFrom = 299.00;
        $shippingValue = 0.00;

        $isFreeShipping = $total >= $shippingFreeFrom;
        $shipping = $isFreeShipping ? 0.0 : $shippingValue;

        $finalTotal = $total + $shipping;

        $installments = 10;
        $installmentValue = $installments > 0 ? ($finalTotal / $installments) : $finalTotal;

        return [
            'count' => $count,
            'total' => $total,
            'shipping' => $shipping,
            'isFreeShipping' => $isFreeShipping,
            'finalTotal' => $finalTotal,
            'installments' => $installments,
            'installmentValue' => $installmentValue,
            'empty' => $count <= 0,
        ];
    }

    private function buildCartViewData(Request $request): array
    {
        $cart = $request->session()->get('cart', []);

        $productIds = collect($cart)->pluck('product_id')->unique()->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0.0;

        foreach ($cart as $key => $item) {
            $product = $products->get($item['product_id'] ?? null);
            if (! $product) continue;

            $unit = $product->is_promotion_active
                ? (float) $product->discounted_price
                : (float) $product->price;

            $qty = (int) ($item['qty'] ?? 1);
            $qty = max(1, min(10, $qty));
            $qty = min($qty, max(1, (int) $product->stock));

            $subtotal = $unit * $qty;

            $items[] = [
                'key' => (string) $key,
                'product' => $product,
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
                'qty' => $qty,
                'unit' => $unit,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        $count = collect($items)->sum('qty');

        return [$items, $total, $count];
    }
}
