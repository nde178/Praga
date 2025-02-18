<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Altaddress;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Метод для создания корзины
    public function store(Request $request)
    {
       // dd($request->all());
        Log::info('Тело запроса на создание/обновление корзины:', ['request' => $request->all()]);

        // Валидация данных
        $validatedData = $request->validate([
            'customer_id' => 'required|integer',
            'customer_name' => 'required|string',
            'email' => 'required|email',
            'total_price' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            // Проверка наличия корзины для текущего пользователя
            $cart = Cart::where('customer_id', $validatedData['customer_id'])->first();

            if ($cart) {
                // Обновляем корзину, если она уже существует
                $cart->update([
                    'total_price' => $validatedData['total_price'],
                ]);

                // Удаляем текущие элементы корзины
                DB::table('cart_items')->where('cart_id', $cart->id)->delete();
            } else {
                // Создаем новую корзину, если её не существует
                $cart = Cart::create([
                    'customer_id' => $validatedData['customer_id'],
                    'customer_name' => $validatedData['customer_name'],
                    'email' => $validatedData['email'],
                    'total_price' => $validatedData['total_price'],
                ]);
            }

            // Добавление товаров в `cart_items`
            foreach ($validatedData['items'] as $item) {
                DB::table('cart_items')->insert([
                    'cart_id' => $cart->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Корзина успешно сохранена/обновлена!', 'cart' => $cart], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при сохранении корзины', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Ошибка при сохранении корзины'], 500);
        }
    }
    public function saveCart(Request $request)
    {
        // Логируем тело запроса для проверки
        //\Log::info('Сохранение корзины:', ['request' => $request->all()]);
        //dd($request->all());

        $validatedData = $request->validate([
            'customer_id' => 'nullable|integer|exists:customers,id',
            'email' => 'nullable|email|max:255',
            'session_id' => 'nullable|string|max:255',
            'total_price' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required',
            'items.*.price' => 'required',
            'items.*.total_price' => 'required',
        ]);
        dd($validatedData);

        try {
            // Проверяем, авторизован ли пользователь
            $isAuthorized = !empty($validatedData['customer_id']);

            // Если пользователь авторизован, используем customer_id
            if ($isAuthorized) {
                $cart = Cart::firstOrCreate(
                    ['customer_id' => $validatedData['customer_id']],
                    [
                        'email' => $validatedData['email'],
                        'total_price' => $validatedData['total_price'],
                    ]
                );
            } else {
                // Для неавторизованного пользователя используем session_id
                if (empty($validatedData['session_id'])) {
                    throw new \Exception('Session ID обязателен для неавторизованных пользователей');
                }

                $cart = Cart::firstOrCreate(
                    ['session_id' => $validatedData['session_id']],
                    [
                        'email' => $validatedData['email'],
                        'total_price' => $validatedData['total_price'],
                    ]
                );
                dd($cart);
            }
            // Очищаем старые записи товаров в корзине
            $cart->items()->delete();

            // Сохраняем товары в корзину
            foreach ($validatedData['items'] as $item) {
                $cart->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['total_price'],
                ]);
            }
            DB::commit();

            \Log::info('Товары корзины успешно сохранены.', ['cart_id' => $cart->id]);

            return response()->json(['cart' => $cart->load('items')], 200);
        } catch (\Exception $e) {
            \Log::error('Ошибка при сохранении корзины:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Ошибка при сохранении корзины'], 500);
        }
    }
    public function saveFinishCart(Request $request)
    {
        Log::info('Сохранение финальной корзины:', ['request' => $request->all()]);

        if ($request->input('customer_id') === "null") {
            $request->merge(['customer_id' => null]);
        }

        $validatedData = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cart_id' => 'required|exists:carts,id',
            'delivery_method' => 'required|string|max:255',
            'delivery_price' => 'required|numeric',
            'payment_method' => 'required|string|max:255',
            'payment_price' => 'required|numeric',
            'total_price' => 'required|numeric|min:1',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'address' => 'required|array',
            'address.street' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.postal_code' => 'required|string|max:10',
            'address.country' => 'required|string|max:2',
            'address.alt_address' => 'nullable|array',
            'address.alt_address.name' => 'nullable|string|max:255',
            'address.alt_address.company' => 'nullable|string|max:255',
            'address.alt_address.street' => 'nullable|string|max:255',
            'address.alt_address.city' => 'nullable|string|max:255',
            'address.alt_address.postal_code' => 'nullable|string|max:10',
            'address.alt_address.contact_phone' => 'nullable|string|max:20',
        ]);

        try {
            // Создание клиента, если его нет
            if (empty($validatedData['customer_id'])) {
                Log::info('Создание нового клиента.');

                $customer = Customer::firstOrCreate(
                    ['email' => $validatedData['email']],
                    [
                        'name' => $validatedData['customer_name'],
                        'phone' => $validatedData['phone'],
                    ]
                );

                $validatedData['customer_id'] = $customer->id;
                Log::info('Клиент создан:', ['customer_id' => $customer->id]);
            }

            // Обновление корзины
            $cart = Cart::findOrFail($validatedData['cart_id']);
            $cart->update([
                'customer_id' => $validatedData['customer_id'],
                'customer_name' => $validatedData['customer_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'status' => 'finished',
                'delivery_method' => $validatedData['delivery_method'],
                'delivery_price' => $validatedData['delivery_price'],
                'payment_method' => $validatedData['payment_method'],
                'payment_price' => $validatedData['payment_price'],
                'total_price' => $validatedData['total_price'],
            ]);

            // Обновление товаров в корзине
            $cart->items()->delete();
            foreach ($validatedData['items'] as $item) {
                $cart->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'total_price' => $item['price'] * $item['qty'],
                ]);
            }

            // Запись в таблицу addresses
            Address::updateOrCreate(
                ['customer_id' => $validatedData['customer_id']], // Условие
                [
                    'address_line_1' => $validatedData['address']['street'],
                    'city' => $validatedData['address']['city'],
                    'postal_code' => $validatedData['address']['postal_code'],
                    'country' => $validatedData['address']['country'],
                ]
            );

            // Запись в таблицу altaddresses, если альтернативный адрес указан
            if (!empty($validatedData['address']['alt_address'])) {
                AltAddress::updateOrCreate(
                    ['customer_id' => $validatedData['customer_id']], // Условие
                    [
                        'name' => $validatedData['address']['alt_address']['name'] ?? null,
                        'company' => $validatedData['address']['alt_address']['company'] ?? null,
                        'street' => $validatedData['address']['alt_address']['street'] ?? null,
                        'city' => $validatedData['address']['alt_address']['city'] ?? null,
                        'postal_code' => $validatedData['address']['alt_address']['postal_code'] ?? null,
                        'contact_phone' => $validatedData['address']['alt_address']['contact_phone'] ?? null,
                    ]
                );
            }

            // Запись в таблицу abandoned_cart_emails для неавторизованных пользователей
            if (empty($validatedData['customer_id'])) {
                $recordExists = \DB::table('abandoned_cart_emails')
                    ->where('cart_id', $cart->id)
                    ->exists();

                if (!$recordExists) {
                    \DB::table('abandoned_cart_emails')->insert([
                        'cart_id' => $cart->id,
                        'email' => $validatedData['email'],
                        'sent' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('Запись добавлена в abandoned_cart_emails.', [
                        'cart_id' => $cart->id,
                        'email' => $validatedData['email'],
                    ]);
                } else {
                    Log::info('Запись уже существует в abandoned_cart_emails.', [
                        'cart_id' => $cart->id,
                    ]);
                }
            }

            Log::info('Финальная корзина успешно сохранена.', ['cart_id' => $cart->id]);

            return response()->json(['message' => 'Финальная корзина успешно сохранена'], 200);
        } catch (\Exception $e) {
            Log::error('Ошибка при сохранении финальной корзины:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Ошибка при сохранении корзины'], 500);
        }
    }

    public function cart(Request $request)
    {
        // Получаем текущего аутентифицированного пользователя
        $customer = $request->user();

        // Проверяем, есть ли у пользователя корзина
        $cart = $customer->cart()->with('items')->first();

        if (!$cart) {
            // Если корзины нет, возвращаем сообщение
            return response()->json(['message' => 'Корзина не найдена'], 404);
        }

        // Если корзина найдена, формируем данные для ответа
        $cartData = [
            'id' => $cart->id,
            'total_price' => $cart->total_price,
            'items' => $cart->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total_price' => $item->quantity * $item->price,
                ];
            }),
        ];

        return response()->json(['cart' => $cartData], 200);
    }


    // Метод для получения корзины по ID
    public function getUserCart(Request $request)
    {
        $customer_id = $request->user()->id; // Получаем ID клиента из токена

        // Ищем корзину, связанную с клиентом
        $cart = Cart::where('customer_id', $customer_id)->first();

        if (!$cart) {
            // Если корзина не найдена, возвращаем ответ с сообщением об отсутствии корзины
            return response()->json(['message' => 'Корзина не найдена.'], 404);
        }

        // Если корзина найдена, возвращаем её данные вместе с товарами
        return response()->json([
            'cart' => $cart,
            'items' => $cart->items // Убедитесь, что связь items настроена в модели Cart
        ]);
    }
    // Метод для обновления корзины (например, финализация заказа)
    public function update(Request $request, $id)
    {
        // Логирование входящих данных
        Log::info('Обновление корзины с ID:', ['cart_id' => $id, 'request_data' => $request->all()]);

        // Проверка наличия корзины
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['message' => 'Корзина не найдена.'], 404);
        }

        // Обновление данных корзины
        $cart->total_price = $request->input('total_price');
        $cart->save();

        // Удаление и добавление товаров в корзине
        DB::table('cart_items')->where('cart_id', $id)->delete();
        foreach ($request->input('items') as $item) {
            DB::table('cart_items')->insert([
                'cart_id' => $id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_price' => $item['total_price'],
            ]);
        }

        return response()->json(['message' => 'Корзина обновлена успешно'], 200);
    }

// Метод для удаления товара из корзины
    public function removeItemFromCart($cart_id, $product_id)
    {
        Log::info("Удаление товара", ['cart_id' => $cart_id, 'product_id' => $product_id]);
        // Проверьте наличие корзины
        $cart = Cart::find($cart_id);

        if (!$cart) {
            return response()->json(['message' => 'Корзина не найдена'], 404);
        }

        // Проверьте наличие товара в корзине
        $item = DB::table('cart_items')
            ->where('cart_id', $cart_id)
            ->where('product_id', $product_id)
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Товар в корзине не найден'], 404);
        }

        // Удалите товар из корзины
        DB::table('cart_items')
            ->where('cart_id', $cart_id)
            ->where('product_id', $product_id)
            ->delete();

        return response()->json(['message' => 'Товар успешно удален'], 200);
    }

    public function removeItemBySessionId($session_id, $product_id)
    {
        Log::info("Удаление товара из корзины по session_id", ['session_id' => $session_id, 'product_id' => $product_id]);

        // Найти корзину по session_id
        $cart = Cart::where('session_id', $session_id)->first();

        if (!$cart) {
            return response()->json(['message' => 'Корзина не найдена'], 404);
        }

        // Найти товар в корзине
        $item = DB::table('cart_items')
            ->where('cart_id', $cart->id)
            ->where('product_id', $product_id)
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Товар в корзине не найден'], 404);
        }

        // Удалить товар из корзины
        DB::table('cart_items')
            ->where('cart_id', $cart->id)
            ->where('product_id', $product_id)
            ->delete();

        // Пересчитать общую стоимость корзины
        $totalPrice = DB::table('cart_items')
            ->where('cart_id', $cart->id)
            ->sum(DB::raw('price * quantity'));

        $cart->update(['total_price' => $totalPrice]);

        return response()->json(['message' => 'Товар успешно удален', 'total_price' => $totalPrice], 200);
    }


    // Метод для удаления корзины
    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return response()->json(['message' => 'Корзина удалена']);
    }
}
