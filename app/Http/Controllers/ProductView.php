<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductView extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int  $product_id)
    {
        $product = \App\Models\Product::query()
            ->with('user', 'categorie')
            ->where('id', $product_id)
            ->firstOrFail();

        return response()->json([
            'product' => $product,
        ]);
    }
}
