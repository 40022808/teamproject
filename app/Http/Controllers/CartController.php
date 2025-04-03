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
        $userId = $request->user()->id; // Bejelentkezett felhasználó ID-ja

        $cartItems = Cart::where('user_id', $userId)
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

        $cartItem = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            // Ha a termék már létezik a kosárban, növeljük a mennyiséget
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            // Ha a termék nem létezik, hozzuk létre
            $cartItem = Cart::create([
                'user_id' => $request->user()->id,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

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
        $userId = Auth::id(); // Bejelentkezett felhasználó ID-ja
        $cartItem = Cart::where('product_id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $cartItem->delete();

        return response()->json(['success' => true, 'message' => 'Item removed from cart']);
    }

    public function decreaseQuantity(Request $request, $productId)
    {
        $userId = $request->user()->id; // Bejelentkezett felhasználó ID-ja

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
        }

        if ($cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $cartItem->save();
        } else {
            $cartItem->delete(); // Ha a darabszám 1, töröljük a terméket
        }

        return response()->json(['success' => true, 'message' => 'Quantity decreased successfully']);
    }
}
