<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function register(Request $request, $lang)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed|max:10',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => 0
        ]);

        if(User::count() == 1) {
            $user->role = 2;
            $user->save();
        }

        return response()->json(['message' => 'User registered successfully.'], 201);
    }

    public function login(Request $request, $lang)
    {
        $credentials = $request->only('name','email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('authToken')->plainTextToken;
            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout($lang)
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function delete($id, $lang)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully.']);
        } else {
            return response()->json(['error' => 'User not found.'], 404);
        }
    }

    public function getUserInfo(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'userInfo' => $user,
            'userRole' => $user->role,
        ]);
    }
}

