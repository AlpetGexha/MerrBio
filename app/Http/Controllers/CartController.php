<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        if (!auth()->check()) {
//            return response()->json([
//                'message' => 'Unauthorized',
//            ], 401);
//        }

        $cart = auth()->user()->carts()
            ->with('product')
            ->get();

        return response()->json([
            'cart' => $cart,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!auth()->check()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {

            $cartItem->quantity += $request->quantity;
            $cartItem->unit_price = $product->price;
            $cartItem->save();
        } else {

            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $product->price,
            ]);
        }

        return response()->json(['message' => 'Produkti u shtua në shportë']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;

        $cartItem->unit_price = $cartItem->product->price ?? $cartItem->unit_price;

        $cartItem->save();

        return response()->json(['message' => 'Cart updated successfully', 'cartItem' => $cartItem]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json(['message' => 'Cart item removed successfully']);
    }
}
