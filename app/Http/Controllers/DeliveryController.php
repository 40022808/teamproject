<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeliveryConfirmationMail;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    public function storeDelivery(Request $request)
    {
        // Validálás
        $validated = $request->validate([
            'fullName' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'houseNumber' => 'required|string',
            'city' => 'required|string',
            'postalCode' => 'required|string',
            'phone' => 'required|string',
        ]);

        // Adatok mentése az adatbázisba
        Delivery::create([
            'full_name' => $validated['fullName'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'house_number' => $validated['houseNumber'],
            'city' => $validated['city'],
            'postal_code' => $validated['postalCode'],
            'phone' => $validated['phone'],
        ]);
        // E-mail küldése
        Mail::to($validated['email'])->queue(new DeliveryConfirmationMail($validated));

        return response()->json(['success' => true, 'message' => 'Delivery details saved and email sent.']);
    }
}