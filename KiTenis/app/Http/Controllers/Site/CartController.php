<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Carrinho = lista de itens favoritados do usuário
     */
    public function index(Request $request): View
    {
        $favorites = $request->user()
            ->favoriteProducts()
            ->orderByDesc('favorites.created_at')
            ->paginate(12)
            ->withQueryString();

        return view('site.cart.index', [
            'favorites' => $favorites,
        ]);
    }

    /**
     * "Adicionar" ao carrinho = favoritar (caso você use algum botão futuro)
     */
    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $request->user()->favoriteProducts()->syncWithoutDetaching([$data['product_id']]);

        return back()->with('success', 'Produto adicionado ao carrinho (favoritos).');
    }

    /**
     * Remover do carrinho = desfavoritar
     */
    public function remove(Request $request, int $id): RedirectResponse
    {
        $request->user()->favoriteProducts()->detach($id);

        return back()->with('success', 'Produto removido do carrinho (favoritos).');
    }
}
