<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        dd($request->all());
        $cart = Cart::firstOrCreate(
            ['email' => $request->input('email'), 'status' => 'pending'],
            ['customer_id' => auth()->id()]
        );

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price')
        ]);

        return response()->json(['message' => 'Item added to cart']);
    }
}
