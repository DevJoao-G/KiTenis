<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Lista de produtos (página geral)
     */
    public function index(Request $request): View
    {
        return $this->listing($request, title: 'Produtos');
    }

    /**
     * Página Masculino
     */
    public function masculino(Request $request): View
    {
        return $this->listing($request, category: 'masculino', title: 'Masculino');
    }

    /**
     * Página Feminino
     */
    public function feminino(Request $request): View
    {
        return $this->listing($request, category: 'feminino', title: 'Feminino');
    }

    /**
     * Página Infantil
     */
    public function infantil(Request $request): View
    {
        return $this->listing($request, category: 'infantil', title: 'Infantil');
    }

    /**
     * Página Ofertas (promoções ativas)
     */
    public function ofertas(Request $request): View
    {
        return $this->listing($request, onlyPromotions: true, title: 'Ofertas');
    }

    /**
     * Método reutilizável para as listagens
     */
    private function listing(
        Request $request,
        ?string $category = null,
        bool $onlyPromotions = false,
        string $title = 'Produtos'
    ): View {
        $query = Product::query()
            ->active()
            ->inStock();

        // Busca por nome ou descrição
        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Categoria fixa (para as páginas Masculino/Feminino)
        if ($category) {
            $query->where('category', $category);
        } else {
            // página geral permite query string ?categoria=
            if ($categoria = $request->string('categoria')->toString()) {
                $query->where('category', $categoria);
            }
        }

        // Ofertas = promoções ativas (do novo sistema)
        if ($onlyPromotions || $request->boolean('ofertas')) {
            $query->onPromotion();
        }

        // Ordenação segura
        $allowedOrderBy = ['created_at', 'price', 'name'];
        $orderBy = $request->input('order_by', 'created_at');

        if (! in_array($orderBy, $allowedOrderBy, true)) {
            $orderBy = 'created_at';
        }

        $orderDirection = $request->input('order_direction', 'desc') === 'asc'
            ? 'asc'
            : 'desc';

        $products = $query
            ->orderBy($orderBy, $orderDirection)
            ->paginate(12)
            ->onEachSide(1)
            ->withQueryString();

        return view('site.products.index', [
            'products' => $products,
            'pageTitle' => $title,
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

        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = auth()->user()
                ->favoriteProducts()
                ->whereKey($product->id)
                ->exists();
        }

        return view('site.products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'isFavorited' => $isFavorited,
        ]);
    }
}
