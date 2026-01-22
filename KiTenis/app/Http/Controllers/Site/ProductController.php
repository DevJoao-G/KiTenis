<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Exibe lista de produtos com busca e filtros
     */
    public function index(Request $request): View
    {
        $query = Product::active()->inStock();

        // Busca por nome ou descrição
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por categoria
        if ($categoria = $request->input('categoria')) {
            $query->where('category', $categoria);
        }

        // Filtro de ofertas
        if ($request->boolean('ofertas')) {
            $query->where('price', '<', 400);
        }

        // Ordenação
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        // Paginação
        $products = $query->paginate(12)->withQueryString();

        return view('site.products.index', compact('products'));
    }

    /**
     * Exibe detalhes de um produto
     */
    public function show(Product $product): View
    {
        abort_if(!$product->active, 404, 'Produto não encontrado');

        $relatedProducts = Product::active()
            ->inStock()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('site.products.show', compact('product', 'relatedProducts'));
    }
}
