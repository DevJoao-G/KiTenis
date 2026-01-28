<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $ofertas = Product::query()
            ->active()
            ->inStock()
            ->promotionCarousel()
            ->orderByDesc('discount_percentage')
            ->orderBy('price')
            ->limit(12)
            ->get();

        $marcas = Brand::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // ✅ IDs favoritados (somente para os produtos exibidos)
        $favoriteIds = [];
        if (auth()->check() && $ofertas->isNotEmpty()) {
            $favoriteIds = auth()->user()
                ->favoriteProducts()
                ->whereIn('products.id', $ofertas->pluck('id'))
                ->pluck('products.id')
                ->all();
        }

        return view('site.home', [
            'ofertas' => $ofertas,
            'marcas'  => $marcas,
            'favoriteIds' => $favoriteIds,
        ]);
    }
}
