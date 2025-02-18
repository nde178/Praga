<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SendAbandonedCartReminder;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\AbandonedCartEmail;
use App\Mail\AbandonedCartMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AbandonedController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Получен запрос на создание брошенной корзины', [
            'email' => $request->email,
            'products' => $request->products
        ]);

        try {
            // Валидация данных
            $request->validate([
                'customer_name' => 'required|string',
                'email' => 'required|email',
                'products' => 'required|array',
                'products.*.product_id' => 'required|integer',
                'products.*.quantity' => 'required|integer',
                'products.*.price' => 'required|numeric',
            ]);

            // Создание новой записи в таблице Cart
            $cart = Cart::create([
                'customer_id' => auth()->id() ?? null,
                'customer_name' => $request->customer_name,
                'email' => $request->email,
                'status' => 'pending'
            ]);
            Log::info('Корзина успешно создана', ['cart_id' => $cart->id]);

            // Сохранение товаров в корзине
            $totalPrice = 0;
            $cartItemsData = [];
            foreach ($request->products as $product) {
                $itemTotalPrice = $product['price'] * $product['quantity'];
                $totalPrice += $itemTotalPrice;

                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total_price' => $itemTotalPrice,
                ]);

                // Получаем данные продукта (название и изображение)
                $productDetails = Product::find($product['product_id']);
                $cartItemsData[] = [
                    'name' => $productDetails->name,
                    'main_image' => url('/storage/' . $productDetails->main_image),
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'total_price' => $itemTotalPrice,
                ];
            }
            Log::info('Товары корзины успешно сохранены', ['cart_id' => $cart->id]);

            // Сохранение записи в таблице abandoned_cart_emails
            AbandonedCartEmail::create([
                'cart_id' => $cart->id,
                'email' => $request->email,
                'sent' => false,
            ]);
            Log::info('Запись для брошенной корзины успешно создана', ['cart_id' => $cart->id]);

            // Отправка первого отложенного письма через 24 часа
            SendAbandonedCartReminder::dispatch(
                $request->customer_name,
                $request->email,
                $cartItemsData,
                $totalPrice
            )->delay(now()->addHours(24));

            // Отправка второго отложенного письма через 48 часов
            SendAbandonedCartReminder::dispatch(
                $request->customer_name,
                $request->email,
                $cartItemsData,
                $totalPrice
            )->delay(now()->addHours(48));

            // Немедленная отправка первого письма (необязательно)
            $this->sendAbandonedCartEmail($request->customer_name, $request->email, $cartItemsData, $totalPrice);

            return response()->json(['message' => 'Корзина сохранена, письмо отправлено'], 200);
        } catch (\Exception $e) {
            Log::error('Ошибка при сохранении корзины', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'products' => $request->products
            ]);

            return response()->json(['message' => 'Произошла ошибка при сохранении корзины'], 500);
        }
    }
    /**
     * Отправка письма с данными брошенной корзины
     */
    private function sendAbandonedCartEmail($customerName, $email, $cartItemsData, $totalPrice)
    {
        try {
            Mail::to($email)->queue(new AbandonedCartMail($customerName, $cartItemsData, $totalPrice));
            Log::info('Письмо отправлено пользователю', ['email' => $email]);
        } catch (\Exception $e) {
            Log::error('Ошибка при отправке письма с брошенной корзиной', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
        }
    }
    public function quickOrder(Request $request)
    {
        Log::info('Получен запрос на быстрый заказ', [
            'email' => $request->email,
            'product_id' => $request->product_id
        ]);

        try {
            // Валидация данных
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'product_id' => 'required|integer',
                'quantity' => 'required|integer|min:1',
            ]);

            // Получение данных продукта
            $product = Product::findOrFail($request->product_id);
            $totalPrice = $product->price * $request->quantity;

            // Создание нового заказа
            $order = Order::create([
                'customer_name' => $request->name,
                'email' => $request->email,
                'status' => 'pending'
            ]);

            // Сохранение товара в заказе
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'total_price' => $totalPrice,
            ]);
            Log::info('Быстрый заказ успешно создан', ['order_id' => $order->id]);

            // Подготовка данных для шаблона письма
            $emailData = [
                'customerName' => $request->name,
                'product' => $product,
                'quantity' => $request->quantity,
                'totalPrice' => $totalPrice,
                'email' => $request->email,
            ];

            // Отправка письма подтверждения быстрого заказа
            Mail::send('emails.quick_order', $emailData, function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Ваш быстрый заказ принят');
            });
            Log::info('Письмо подтверждения быстрого заказа отправлено', ['email' => $request->email]);

            return response()->json(['message' => 'Быстрый заказ успешно оформлен'], 200);
        } catch (\Exception $e) {
            Log::error('Ошибка при создании быстрого заказа', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'product_id' => $request->product_id
            ]);

            return response()->json(['message' => 'Произошла ошибка при оформлении быстрого заказа'], 500);
        }
    }
}
