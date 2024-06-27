<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('login', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            try {
                $token = $user->createToken('appToken')->plainTextToken;
                return response()->json([
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'group' => $user->group_id,
                        'permission' => $user->permission,
                        'firstName' => $user->name,
                        'middleName' => $user->patronymic,
                        'lastName' => $user->sname
                    ]
                ], 200);
            } catch (\Exception $e) {
                \Log::error('Token creation failed: ' . $e->getMessage());
                return response()->json(['error' => 'Token creation failed'], 500);
            }
        }

        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    public function register(Request $request)
    {
        try {
            $user = User::create([
                'login' => $request->login,
                'password' => bcrypt($request->password),
                'name' => $request->name,
                'patronymic' => $request->patronymic,
                'sname' => $request->sname,
                'b_date' => $request->b_date,
                'permission' => $request->permission,
                'group_id' => $request->group_id
            ]);

            $token = $user->createToken('appToken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'group' => $user->group_id,
                    'permission' => $user->permission,
                    'firstName' => $user->name,
                    'middleName' => $user->patronymic,
                    'lastName' => $user->sname
                ]
            ], 200);
        } catch (\Exception $e) {
            \Log::error('User registration failed: ' . $e->getMessage());
            return response()->json(['error' => 'User registration failed'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Logout user failed: ' . $e->getMessage());
            return response()->json(['error' => 'Logout user failed'], 500);
        }
    }

    public function getUser(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'group' => $user->group_id,
                    'permission' => $user->permission,
                    'firstName' => $user->name,
                    'middleName' => $user->patronymic,
                    'lastName' => $user->sname
                ]
            ], 200);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function getUserFromId($UserId)
    {
        $user = User::findOrFail($UserId);

        if ($user) {
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'group_id' => $user->group_id,
                    'permission' => $user->permission,
                    'name' => $user->name,
                    'patronymic' => $user->patronymic,
                    'sname' => $user->sname
                ]
            ], 200);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'sname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'user_password' => 'required|string|max:255',
            'permission' => 'required|string',
            'group_id' => 'nullable|integer|exists:groups,id',
        ]);

        try {
            $user = new User();
            $user->sname = $request->input('sname');
            $user->name = $request->input('name');
            $user->patronymic = $request->input('patronymic');
            $user->user_password = $request->input('user_password');
            $user->permission = $request->input('permission');
            $user->group_id = $request->input('group_id');
            $user->save();

            return response()->json($user, 201);
        } catch (\Exception $e) {
            \Log::error('store user failed: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка при создании пользователя'], 500);
        }
    }

    public function checkUserPassword(Request $request)
    {
        $request->validate([
            'user_password' => 'required|string'
        ]);

        $user = User::where('user_password', $request->user_password)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid user password'], 404);
        }

        return response()->json(['message' => 'User password is valid'], 200);
    }

    // Метод для обновления пользователя с новыми данными
    public function updateUser(Request $request)
    {
        $request->validate([
            'user_password' => 'required|string',
            'login' => 'required|string|unique:users,login',
            'password' => 'required|string|min:6'
        ]);

        try {
            $user = User::where('user_password', $request->user_password)->first();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $user->login = $request->login;
            $user->password = Hash::make($request->password);
            $user->user_password = null; // Удаление одноразового кода
            $user->save();

            $token = $user->createToken('appToken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'group' => $user->group_id,
                    'permission' => $user->permission,
                    'firstName' => $user->name,
                    'middleName' => $user->patronymic,
                    'lastName' => $user->sname
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            return response()->json(['error' => 'User update failed'], 500);
        }
    }

    public function getTeachers()
    {
        try {
            $teachers = User::where('permission', 'Преподаватель')
                        ->orWhere('permission', 'Модератор')
                        ->get();
            return response()->json($teachers, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch teachers'], 500);
        }
    }

}
