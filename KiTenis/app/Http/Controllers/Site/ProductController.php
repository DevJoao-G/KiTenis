<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Lista de produtos
     */
    public function index(Request $request): View
    {
        $query = Product::query()
            ->active()
            ->inStock();

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoria = $request->string('categoria')->toString()) {
            $query->where('category', $categoria);
        }

        // ofertas = promoções ativas
        if ($request->boolean('ofertas')) {
            $query->onPromotion();
        }

        $allowedOrderBy = ['created_at', 'price', 'name'];
        $orderBy = $request->input('order_by', 'created_at');

        if (! in_array($orderBy, $allowedOrderBy, true)) {
            $orderBy = 'created_at';
        }

        $orderDirection = $request->input('order_direction', 'desc') === 'asc' ? 'asc' : 'desc';

        $products = $query
            ->orderBy($orderBy, $orderDirection)
            ->paginate(12)
            ->onEachSide(1)
            ->withQueryString();

        return view('site.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Detalhe do produto
     */
    public function show(Product $product): View
    {
        if (! $product->active) {
            abort(404);
        }

        $relatedProducts = Product::query()
            ->active()
            ->inStock()
            ->where('category', $product->category)
            ->whereKeyNot($product->id)
            ->limit(4)
            ->get();

        return view('site.products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
