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
        // Apenas promoções ativas e marcadas para o carousel
        $ofertas = Product::query()
            ->active()
            ->inStock()
            ->promotionCarousel()
            ->orderByDesc('discount_percentage')
            ->orderBy('price')
            ->limit(12)
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
