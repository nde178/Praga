<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Customer registered successfully!'], 201);
    }

    public function login(Request $request)
    {
        // Валидация данных
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Поиск клиента по email
        $customer = Customer::where('email', $request->email)->first();

        // Проверка правильности пароля
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Логируем данные клиента для отладки
        Log::info('Customer data:', [
            'name' => $customer->name,
            'email' => $customer->email
        ]);

        // Возвращаем только имя и email клиента
        return response()->json([
            'name' => $customer->name,  // Имя клиента
            'email' => $customer->email  // Email клиента
        ], 200);
    }


    // Выход
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
