<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Página inicial
     */
    public function index(): View
    {
        // Produtos em oferta (ex: até R$ 400)
        $ofertas = Product::query()
            ->active()
            ->inStock()
            ->where('price', '<', 400)
            ->orderBy('price')
            ->limit(6)
            ->get();

        // Marcas (somente ativas)
        $marcas = Brand::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('site.home', [
            'ofertas' => $ofertas,
            'marcas'  => $marcas,
        ]);
    }
}
