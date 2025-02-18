<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\PersonalAccessToken;


class CustomerController extends Controller
{
    /**
     * Регистрация нового клиента
     */
    public function register(Request $request)
    {
        Log::info('Данные запроса регистрации:', ['request' => $request->only(['name', 'email', 'password'])]);

        // Правила валидации для базовой регистрации
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // Проверяем, есть ли ошибка по правилу уникальности для email
            if ($validator->errors()->has('email')) {
                return response()->json([
                    'email' => ['Uživatel s tímto emailem již existuje']
                ], 400);
            }
            // Возвращаем другие ошибки валидации, если они есть
            return response()->json($validator->errors(), 400);
        }

        try {
            // Создание нового пользователя
            $customer = Customer::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            Log::info('Пользователь успешно создан', ['customer_id' => $customer->id]);

            // Генерация токена
            $token = Str::random(60);
            PersonalAccessToken::create([
                'tokenable_type' => get_class($customer),
                'tokenable_id' => $customer->id,
                'name' => 'API Token',
                'token' => $token,
                'abilities' => json_encode(['*']),
                'last_used_at' => Carbon::now(),
                'expires_at' => now()->addDays(7),
            ]);

            Log::info('Токен успешно создан', ['token' => $token]);

            // Возвращаем ответ с данными пользователя и токеном
            return response()->json([
                'token' => $token,
                'name' => $customer->name,
                'email' => $customer->email,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Ошибка при создании пользователя', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Произошла ошибка при регистрации'], 500);
        }
    }


    /**
     * Авторизация клиента
     * Метод для логина клиента и генерации токена
     */
    /**
     * Авторизация клиента
     * Метод для логина клиента и генерации токена
     */
    public function login(Request $request)
    {
        // Валидация входных данных
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('customers')->attempt($credentials)) {
            $customer = Auth::guard('customers')->user();

            // Удаляем старые токены и генерируем новый
            $customer->tokens()->delete();
            $token = Str::random(60);

            PersonalAccessToken::create([
                'tokenable_type' => get_class($customer),
                'tokenable_id' => $customer->id,
                'name' => 'API Token',
                'token' => $token,
                'abilities' => json_encode(['*']),
                'last_used_at' => Carbon::now(),
                'expires_at' => now()->addDays(7),
            ]);

            // Извлечение данных компании
            $company = $customer->company;
            $company_name = $company->company_name ?? null;
            $ico_number = $company->ico_number ?? null;
            $dic_number = $company->dic_number ?? null;

            // Извлечение адреса
            $address = $customer->addresses()->first();
            $street = $address->address_line_1 ?? null;
            $city = $address->city ?? null;
            $postal_code = $address->postal_code ?? null;

            $altAddress = $customer->altAddresses()->first();
            $delivery_name = $altAddress->name ?? null;
            $delivery_street = $altAddress->street ?? null;
            $delivery_city = $altAddress->city ?? null;
            $delivery_postal_code = $altAddress->postal_code ?? null;
            $delivery_contact_phone = $altAddress->contact_phone ?? null;

            // Загрузка корзины
            $cart = $customer->cart;

            $cartData = null;
            if ($cart) {
                $cartItems = $cart->items->map(function ($item) {
                    $product = \App\Models\Product::find($item->product_id);

                    if ($product) {
                        return [
                            'id' => $product->id,
                            'main_image' => $product->main_image,
                            'name' => $product->name,
                            'price' => $product->new_price,
                            'qty' => $item->quantity,
                            'unique_code' => $product->unique_code,
                            'total_price' => $product->new_price * $item->quantity,
                        ];
                    }
                    return null; // Удаляем товар, если он больше недоступен
                })->filter(); // Удаляем null значения

                $totalPrice = $cartItems->sum('total_price'); // Пересчет общей суммы

                // Сохранение обновленной суммы в базе данных
                $cart->update(['total_price' => $totalPrice]);

                $cartData = [
                    'id' => $cart->id,
                    'total_price' => $totalPrice,
                    'items' => $cartItems,
                ];
            }

            return response()->json([
                'token' => $token,
                'customer_id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'company_name' => $company_name,
                'ico_number' => $ico_number,
                'dic_number' => $dic_number,
                'street' => $street,
                'city' => $city,
                'postal_code' => $postal_code,
                'delivery_name' => $delivery_name,
                'delivery_street' => $delivery_street,
                'delivery_city' => $delivery_city,
                'delivery_postal_code' => $delivery_postal_code,
                'delivery_contact_phone' => $delivery_contact_phone,
                'cart' => $cartData, // Возвращаем данные корзины
            ], 200);
        }

        // Ошибка авторизации
        return response()->json(['message' => 'Unauthorized'], 401);
    }


    /**
     * Выход (ревокация токена)
     */
    public function logout(Request $request)
    {
        // Получаем токен из параметров запроса
        $apiToken = $request->input('api_token');

        if ($apiToken) {
            // Ищем токен в базе данных
            $token = PersonalAccessToken::where('token', $apiToken)->first();

            if (!$token) {
                return response()->json(['message' => 'Invalid token'], 401);
            }

            // Удаляем токен
            $token->forceDelete();

            return response()->json(['message' => 'Logged out successfully'], 200);
        }

        // Если токен не предоставлен, возвращаем успешный ответ для неавторизованного пользователя
        return response()->json(['message' => 'Logged out successfully (no token provided)'], 200);
    }

// Метод для отправки ссылки на сброс пароля
    public function sendResetLinkEmail(Request $request)
    {
        Log::error('Не удалось отправить ссылку для сброса пароля', ['email' => $request->email]);

        // Валидация email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:customers,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Неверный email или пользователь не найден'], 422);
        }

        // Отправка ссылки для сброса пароля
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Ссылка для восстановления пароля отправлена.'], 200)
            : response()->json(['error' => 'Не удалось отправить ссылку. Попробуйте позже.'], 500);
    }
}
