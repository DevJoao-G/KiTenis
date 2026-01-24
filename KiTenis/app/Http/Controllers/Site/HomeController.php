<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial com ofertas e marcas
     */
    public function index(): View
    {
        // Busca produtos em oferta (preço < 400)
        $ofertas = Product::active()
            ->inStock()
            ->where('price', '<', 400)
            ->orderBy('price', 'asc')
            ->limit(6)
            ->get();

        // Marcas disponíveis (baseado nas imagens)
        $marcas = [
            ['nome' => 'Adidas', 'logo' => 'adidas-logo.svg'],
            ['nome' => 'Nike', 'logo' => 'nike-logo.svg'],
            ['nome' => 'Mizuno', 'logo' => 'mizuno-logo.svg'],
            ['nome' => 'Vans', 'logo' => 'vans-logo.svg'],
            ['nome' => 'Asics', 'logo' => 'asics-logo.svg'],
            ['nome' => 'Puma', 'logo' => 'puma-logo.svg'],
        ];

        return view('site.home', compact('ofertas', 'marcas'));
    }
}