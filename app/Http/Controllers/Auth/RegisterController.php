<?php

// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use App\Mail\RegistrationSuccessful;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail;
// use App\Models\User;

// class RegisterController extends Controller
// {
//     public function register(Request $request)
//     {
//         // Validáció
//         $validatedData = $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:6|confirmed',
//         ]);

//         // Felhasználó létrehozása
//         $user = User::create([
//             'name' => $validatedData['name'],
//             'email' => $validatedData['email'],
//             'password' => bcrypt($validatedData['password']),
//         ]);

//         // E-mail küldése queue-val
//         Mail::to($user->email)->queue(new RegistrationSuccessful($user));

//         // Válasz visszaküldése
//         return response()->json(['message' => 'Sikeres regisztráció!'], 201);
//     }
// }