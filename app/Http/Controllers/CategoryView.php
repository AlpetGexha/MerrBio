<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategoryView extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $category_id)
    {
        $category = Categorie::query()
            ->with('products')
            ->where('id', $category_id)
            ->firstOrFail();

        return response()->json([
            'category' => $category,
        ]);
    }
}
