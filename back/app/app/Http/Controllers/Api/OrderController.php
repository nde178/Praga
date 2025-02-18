<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // Логирование входных данных
        \Log::info('Создание заказа:', ['request' => $request->all()]);

        // Обработка customer_id перед валидацией, если оно равно строке "null", присваиваем null
        $data = $request->all();
        if (isset($data['customer_id']) && $data['customer_id'] === 'null') {
            $data['customer_id'] = null;  // Преобразуем строку "null" в настоящий null
        }
       // dd($data);
        // Валидация данных
        $validatedData = Validator::make($data, [
            'cart_id' => 'nullable|exists:carts,id',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_method' => 'required|string|max:255',
            'delivery_price' => 'required|numeric',
            'payment_method' => 'required|string|max:255',
            'payment_price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'message' => 'nullable|string',
            'newsletterAccepted' => 'nullable|boolean',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.main_image' => 'nullable|string',
            'items.*.unique_code' => 'nullable|string',
        ])->validate();


        // Логирование после валидации
        \Log::info('Валидированные данные:', ['validatedData' => $validatedData]);

        // Создаем заказ
        try {
            $status = 'unpaid';
            if ($validatedData['payment_method'] === 'kartou') {
                $status = 'paid';
            } elseif ($validatedData['payment_method'] === 'bank' || $validatedData['payment_method'] === 'cash') {
                $status = 'pending';
            }

            $order = Order::create([
                'cart_id' => $validatedData['cart_id'],
                'customer_id' => $validatedData['customer_id'],  // Здесь значение уже может быть null
                'customer_name' => $validatedData['customer_name'],
                'customer_email' => $validatedData['customer_email'],
                'customer_phone' => $validatedData['customer_phone'],
                'delivery_method' => $validatedData['delivery_method'],
                'delivery_price' => $validatedData['delivery_price'],
                'payment_method' => $validatedData['payment_method'],
                'payment_price' => $validatedData['payment_price'],
                'total_price' => $validatedData['total_price'],
                'status' => $status,
                'message' => $validatedData['message'] ?? null,
                'newsletterAccepted' => $validatedData['newsletterAccepted'] ?? false,
            ]);

            // Создаем связанные записи в order_items
            foreach ($validatedData['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'main_image' => $item['main_image'] ?? null,
                    'unique_code' => $item['unique_code'] ?? null,
                ]);
            }

            // Создаем запись в таблице invoices
            \DB::table('invoices')->insert([
                'customer_id' => $validatedData['customer_id'],  // Здесь тоже будет null, если не указан
                'number' => Invoice::generateNumber(),
                'invoice_date' => now(),
                'total_amount' => $validatedData['total_price'],
                'status' => 'unpaid',
            ]);

            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            \Log::info('Заказ успешно создан.', ['order_id' => $order->id]);

            return response()->json(['message' => 'Заказ успешно создан.', 'order' => $order->load('items')], 201);
        } catch (\Exception $e) {
            \Log::error('Ошибка при создании заказа:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Ошибка при создании заказа'], 500);
        }
    }
    }
