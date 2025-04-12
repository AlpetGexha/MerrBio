<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsList extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $products = Product::query()
            ->with('user', 'categorie')
//            filter (name, price, category_id)
            ->where('status', 'active')
            ->when($request->get('name'), function ($query, $name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->when($request->get('price'), function ($query, $price) {
                if ($query === 'asc') {
                    return $query->orderBy('price');
                }

                if ($query === 'desc') {
                    return $query->orderByDesc('price');
                }

                return $query;
            })
            ->when($request->get('category_id'), function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->get('min'), function ($query, $min) {
                return $query->where('price', '<=', $min);
            })
            ->when($request->get('max'), function ($query, $max) {
                return $query->where('price', '>=', $max);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'products' => $products,
        ]);
    }
}
