<?php
// filepath: app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product; // Termék modell importálása
use Illuminate\Support\Facades\Auth; // Auth használata a felhasználó azonosításához
use Illuminate\Support\Facades\Validator; // Validator használata a bemenet ellenőrzéséhez
use Illuminate\Validation\Rule; // Rule használata az egyedi szabályokhoz
use Illuminate\Support\Facades\Storage; // Storage használata a fájlok kezeléséhez
use Illuminate\Support\Facades\Mail; // Mail használata az e-mailek küldéséhez
use App\Mail\BookingConfirmationMail; // E-mail sablon importálása
use App\Models\Booking; // Booking modell importálása
use App\Models\Delivery; // Delivery modell importálása
use App\Models\User; // User modell importálása
use App\Models\Users; // Users modell importálása


class CartController extends Controller
{
    public function index(Request $request)
{
    $cartItems = Cart::where('user_id', $request->user()->id)
        ->with('product') // Termék részletek betöltése
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
        [
            'quantity' => \DB::raw('quantity + ' . $validated['quantity']),
        ]
    );

    $cartItem->load('product'); // Termék részletek betöltése

    return response()->json(['success' => true, 'cartItem' => $cartItem], 201);
}
public function update(Request $request, $id)
{
    $cartItem = Cart::find($id);

    if (!$cartItem) {
        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }

    $validated = $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    $cartItem->update($validated);

    $cartItem->load('product'); // Termék részletek betöltése

    return response()->json(['success' => true, 'cartItem' => $cartItem]);
}
   
    
public function destroy($id)
{
    $cartItem = Cart::find($id);

    if (!$cartItem) {
        return response()->json(['success' => false, 'message' => 'Item not found'], 404);
    }

    $cartItem->delete();

    return response()->json(['success' => true, 'message' => 'Item removed from cart']);
}

}