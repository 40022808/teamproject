<?php
// filepath: app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated user's cart items
        $cartItems = Cart::where('user_id', $request->user()->id)
            ->with('product') // Load product details
            ->get();

        return response()->json(['success' => true, 'cartItems' => $cartItems]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $validated['product_id'],
            ],
            ['quantity' => $validated['quantity']]
        );

        return response()->json(['success' => true, 'cartItem' => $cartItem], 201);
    }

    public function destroy($id, Request $request)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json(['success' => true, 'message' => 'Item removed from cart'], 200);
    }
}
