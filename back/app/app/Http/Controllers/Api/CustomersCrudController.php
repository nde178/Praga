<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Address;
use App\Models\Altaddress;
use App\Models\Company;
use App\Models\Customer;
use App\Models\PersonalAccessToken;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomersCrudController extends Controller
{
    // Получение списка всех клиентов
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }
    public function getProfile(Request $request)
    {
        // Получаем аутентифицированного клиента
        $customer = Auth::user();

        // Проверяем, есть ли клиент
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Возвращаем данные профиля клиента
        return response()->json([
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'addresses' => $customer->addresses, // если у вас есть связь с адресами
            'company' => $customer->company, // если есть связь с компанией
            'altAddresses' => $customer->altAddresses, // альтернативные адреса доставки, если есть
        ], 200);
    }
    // Создание нового клиента
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create($data);
        return response()->json($customer, 201);
    }

    // Получение данных клиента по его ID
    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        return response()->json($customer);
    }
    public function updateProfile(Request $request)
    {
        try {
            // Логируем входные данные запроса
            Log::info('Получен запрос на обновление профиля', ['request_data' => $request->all()]);

            // Получаем аутентифицированного пользователя
            $customer = Auth::user();
            if (!$customer) {
                Log::warning('Пользователь не аутентифицирован.');
                return response()->json(['message' => 'Пользователь не аутентифицирован'], 401);
            }

            // Логируем, что пользователь найден и аутентифицирован
            Log::info('Пользователь найден и аутентифицирован', ['customer_id' => $customer->id]);

            // Валидация данных клиента
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('customers', 'email')->ignore($customer->id),
                ],
                'phone' => 'nullable|string|max:40',
                'company_name' => 'nullable|string|max:255',
                'ico_number' => 'nullable|string|max:20',
                'dic_number' => 'nullable|string|max:20',
                'street' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'delivery_name' => 'nullable|string|max:255',
                'delivery_street' => 'nullable|string|max:255',
                'delivery_city' => 'nullable|string|max:255',
                'delivery_postal_code' => 'nullable|string|max:10',
                'delivery_contact_phone' => 'nullable|string|max:20',
            ]);
            Log::info('Данные клиента успешно валидированы', ['validated_data' => $data]);

            // Обновление данных клиента
            // Обновление данных клиента (включая основной телефон)
            $customer->update([
                'phone' => $data['phone'],  // Обновляем основной телефон
            ]);
            Log::info('Профиль пользователя обновлен', ['customer' => $customer]);

            // Обновление компании, если данные заполнены
            if ($request->filled('company_name') || $request->filled('ico_number') || $request->filled('dic_number')) {
                // Формируем полный адрес компании
                $companyAddress = implode(', ', array_filter([$data['street'], $data['postal_code'], $data['city']]));
                Log::info('Формирование адреса компании', ['company_address' => $companyAddress]);

                // Обновление или создание компании
                $company = Company::updateOrCreate(
                    ['customer_id' => $customer->id], // Условие обновления или создания
                    [
                        'company_name' => $data['company_name'],
                        'ico_number' => $data['ico_number'],
                        'dic_number' => $data['dic_number'],
                        'phone' => $data['phone'],
                        'company_address' => $companyAddress,
                        'email' => $data['email'],
                    ]
                );
                Log::info('Данные компании обновлены', ['company' => $company]);
            }

            // Обновление основного адреса
            $address = Address::updateOrCreate(
                ['customer_id' => $customer->id], // Условие обновления или создания
                [
                    'address_line_1' => $data['street'],
                    'city' => $data['city'],
                    'postal_code' => $data['postal_code'],
                    'country' => 'CZ',
                ]
            );
            Log::info('Основной адрес обновлен', ['address' => $address]);

            // Обновление альтернативного адреса (если заполнены все обязательные поля)
            if ($request->filled('delivery_name') || $request->filled('delivery_street') ||
                $request->filled('delivery_city') || $request->filled('delivery_postal_code') || $request->filled('delivery_contact_phone')) {
                $alternateAddress = AltAddress::updateOrCreate(
                    ['customer_id' => $customer->id], // Условие обновления или создания
                    [
                        'name' => $data['delivery_name'],
                        'company' => $data['company_name'],
                        'street' => $data['delivery_street'],
                        'city' => $data['delivery_city'],
                        'postal_code' => $data['delivery_postal_code'],
                        'contact_phone' => $data['delivery_contact_phone'],
                    ]
                );
                Log::info('Альтернативный адрес обновлен', ['alternate_address' => $alternateAddress]);
            }

            // Возвращаем успешный ответ
            return response()->json(['message' => 'Данные успешно обновлены', 'customer' => $customer], 200);

        } catch (Exception $e) {
            Log::error('Ошибка при обновлении профиля', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Произошла ошибка при обновлении профиля'], 500);
        }
    }



// Метод для отправки письма с учетными данными
    protected function sendWelcomeEmail($customer, $password)
    {
        Log::info('Отправка письма с данными пользователя', ['email' => $customer->email]);

        $emailData = [
            'name' => $customer->name,
            'email' => $customer->email,
            'password' => $password,
        ];

        // Отправка письма
        Mail::send('emails.welcome', $emailData, function($message) use ($customer) {
            $message->to($customer->email);
            $message->subject('Добро пожаловать в наш интернет-магазин!');
        });

        Log::info('Письмо отправлено на email', ['email' => $customer->email]);
    }


    // Удаление клиента по его ID
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();
        return response()->json(['message' => 'Customer deleted']);
    }
    // Метод для сброса пароля
    public function resetPassword(Request $request)
    {
        // Получаем токен из заголовка authToken
        $token = $request->header('authToken');

        if (!$token) {
            return response()->json(['message' => 'API не получен'], 401);
        }

        // Аутентифицируем пользователя по токену
        $customer = Auth::user();

        // Валидация данных
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Неверные данные', 'details' => $validator->errors()], 422);
        }

        // Обновление пароля
        $customer->password = Hash::make($request->password);
        $customer->save();

        return response()->json(['message' => 'Пароль успешно сброшен.'], 200);
    }
}
