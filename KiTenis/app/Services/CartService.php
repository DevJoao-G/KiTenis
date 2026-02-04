<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class CartService
{
    /**
     * Returns [items, total, count]
     *
     * items: array of ['key','product','size','color','qty','unit','subtotal']
     */
    public function buildCartViewData(Request $request): array
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

        $count = (int) collect($items)->sum('qty');

        return [$items, $total, $count];
    }

    public function isEmpty(Request $request): bool
    {
        [, , $count] = $this->buildCartViewData($request);

        return $count <= 0;
    }

    public function clear(Request $request): void
    {
        $request->session()->forget('cart');
    }
}
