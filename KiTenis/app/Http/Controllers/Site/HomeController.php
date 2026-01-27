<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
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

        // Marcas mockadas (UI)
        $marcas = [
            ['nome' => 'Adidas', 'logo' => 'adidas-logo.svg'],
            ['nome' => 'Nike', 'logo' => 'nike-logo.svg'],
            ['nome' => 'Mizuno', 'logo' => 'mizuno-logo.svg'],
            ['nome' => 'Vans', 'logo' => 'vans-logo.svg'],
            ['nome' => 'Asics', 'logo' => 'asics-logo.svg'],
            ['nome' => 'Puma', 'logo' => 'puma-logo.svg'],
        ];

        return view('site.home', [
            'ofertas' => $ofertas,
            'marcas'  => $marcas,
        ]);
    }
}
