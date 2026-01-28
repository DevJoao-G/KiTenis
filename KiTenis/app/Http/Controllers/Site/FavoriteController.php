<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();

        $exists = $user->favoriteProducts()
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            $user->favoriteProducts()->detach($product->id);
            $favorited = false;
        } else {
            $user->favoriteProducts()->attach($product->id);
            $favorited = true;
        }

        return response()->json([
            'ok' => true,
            'favorited' => $favorited,
            'product_id' => $product->id,
        ]);
    }
}
