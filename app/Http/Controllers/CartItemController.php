<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartItemController extends BaseController
{
    public function add_item(Request $req)
{
    $user = $req->user();

    $validated = $req->validate([
        'product_id' => 'required|exists:products,id',
        'amount' => 'required|integer|min:1',
    ]);

    $product = Product::find($validated['product_id']);
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    $cart = Cart::firstOrCreate(
        ['user_id' => $user->id, 'status' => 'open'],
        ['status' => 'open']
    );

    $item = CartItem::firstOrNew([
        'cart_id' => $cart->id,
        'product_id' => $validated['product_id'],
    ]);

    $newAmount = ($item->exists ? $item->amount : 0) + $validated['amount'];


    if ($newAmount > $product->stock) {
        return response()->json([
            'error' => 'Not enough stock available.',
            'available_stock' => $product->stock,
            'requested_total' => $newAmount,
        ], 422);
    }

    $item->amount = $newAmount;
    $item->save();

    $success['cart_item'] = $item;

    return $this->sendResponse($success, "Product added to cart");
}


     public function delete_item(Request $req, $id)
    {
        $item = CartItem::findOrFail($id);

        if ($item->cart->user_id !== $req->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $item->delete();

        return $this->sendResponse([],"Item deleted");
    }

    public function edit_item(Request $req, $id){
        $validated = $req->validate([
        'amount' => 'required|integer|min:1',
    ]);

    $item = CartItem::with('product', 'cart')->findOrFail($id);

    if ($item->cart->user_id !== $req->user()->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $product = $item->product;
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    if ($validated['amount'] > $product->stock) {
        return response()->json([
            'error' => 'Requested quantity exceeds available stock',
            'available_stock' => $product->stock
        ], 422);
    }

    $item->update(['amount' => $validated['amount']]);

    return response()->json([
        'message' => 'Cart item updated',
        'cart_item' => $item
    ]);
    }

     public function get_cart_items(Request $req, $id)
{
    $cart = Cart::where('id', $id)
        ->where('status', 'open')
        ->with('cartItems.product')
        ->first();

     if ($cart->user_id !== $req->user()->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    if (!$cart) {
        return response()->json(['error' => 'Cart not found or is closed.'], 404);
    }

    return response()->json([
        'cart_id' => $cart->id,
        'status' => $cart->status,
        'items' => $cart->cartItems
    ]);


}

    public function purchase(Request $req, $id)
    {
        $user = $req->user();

        $cart = Cart::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'open')
            ->with('cartItems.product')
            ->first();


        if (!$cart) {
            return response()->json(['error' => 'Cart not found or already purchased.'], 404);
        }

        foreach ($cart->cartItems as $item) {
            $product = $item->product;

            if (!$product) {
                return response()->json(['error' => "Product with ID {$item->product_id} not found."], 404);
            }

            if ($product->stock < $item->amount) {
                return response()->json([
                    'error' => "Not enough stock for '{$product->name}'.",
                    'available' => $product->stock,
                    'requested' => $item->amount
                ], 422);
            }
        }

        foreach ($cart->cartItems as $item) {
            $product = $item->product;
            $product->stock -= $item->amount;
            $product->save();
        }

        $cart->status = 'closed';
        $cart->save();

        return response()->json([
            'message' => 'Purchase completed successfully.',
            'cart_id' => $cart->id
        ]);
    }

}
