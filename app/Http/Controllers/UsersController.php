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


        if($lang == 'en') {
            return response()->json(['message' => 'User registered successfully.'], 201);
        }else if ($lang == 'hu') {
            return response()->json(['message' => 'A felhaszmáló sikeresen regisztrálva.'], 201);
        }else if ($lang == 'zh') {
            return response()->json(['message' => '用户注册成功'],201);
        }
        
    }

    public function login(Request $request, $lang)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('authToken')->plainTextToken;
            return response()->json(['token' => $token]);
        }

        
        if($lang == 'en') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }else if ($lang == 'hu') {
            return response()->json(['error' => 'Nem engedélyezett'], 401);
        }else if ($lang == 'zh') {
            return response()->json(['error' => '登录失败'], 401);
        }
    }

    public function logout($lang)
    {
        Auth::logout();
        

        if($lang == 'en') {
            return response()->json(['message' => 'Logged out successfully.']);
        }else if ($lang == 'hu') {
            return response()->json(['message' => 'Sikeresen kkijelentkezett.']);
        }else if ($lang == 'zh') {
            return response()->json(['message' => '登出成功.']);
        }
    }

    /* public function delete($id, $lang)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully.']);
        } else {
            return response()->json(['error' => 'User not found.'], 404);
        }
    } */

    public function getUserInfo(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'userInfo' => $user,
            'userRole' => $user->role,
        ]);
    }

    public function upgradeToAdmin(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $targetUser = User::find($id);
        if (!$targetUser || $targetUser->isAdmin() || $targetUser->isSuperAdmin()) {
            return response()->json(['error' => 'User cannot be upgraded to admin.'], 400);
        }

        $targetUser->role = 1;
        $targetUser->save();

        return response()->json(['message' => 'User upgraded to admin successfully.']);
    }

    public function downgradeFromAdmin(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $targetUser = User::find($id);
        if (!$targetUser || !$targetUser->isAdmin()) {
            return response()->json(['error' => 'User cannot be downgraded from admin.'], 400);
        }

        $targetUser->role = 0;
        $targetUser->save();

        return response()->json(['message' => 'User downgraded from admin successfully.']);
    }


    public function updateUserName(Request $request, $lang)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
        ]);

        $user->name = $validatedData['name'];
        $user->save();

        if($lang == 'en') {
            return response()->json(['message' => 'User name updated successfully.']);
        } else if ($lang == 'hu') {
            return response()->json(['message' => 'A felhasználó neve sikeresen frissítve.']);
        } else if ($lang == 'zh') {
            return response()->json(['message' => '用户名更新成功'], 200);
        }
    }


    public function checkOldPassword(Request $request) {
        $user = Auth::user();
        $validatedData = $request->validate([
            'old_password' => 'required|string|min:6',
        ]);
        
        if (password_verify($validatedData['old_password'], $user->password)) {
            return response()->json(['message' => 'Old password is correct.']);
        } else {
            return response()->json(['error' => 'Old password is incorrect.'], 400);
        }
    }
    
    public function updatePassword(Request $request, $lang) {
        $user = Auth::user();
        $validatedData = $request->validate([
            'new_password' => 'required|string|min:6|confirmed|max:10',
            
        ]);
    
        
        $user->password = bcrypt($validatedData['new_password']);
        $user->save();
        
        if ($lang == 'en') {
            return response()->json(['message' => 'Password updated successfully.']);
        } else if ($lang == 'hu') {
            return response()->json(['message' => 'A jelszó sikeresen frissítve.']);
        } else if ($lang == 'zh') {
            return response()->json(['message' => '密码更新成功'], 200);
        }
    }
    



}

