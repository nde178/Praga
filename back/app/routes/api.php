<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CustomersCrudController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Здесь вы можете зарегистрировать маршруты API для вашего приложения. Эти
| маршруты загружаются в группе "api" middleware и должны быть защищены.
|
*/
// Для получения текущего авторизованного пользователя
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Маршруты для аутентификации
Route::post('/register', [CustomerController::class, 'register']);
Route::post('customers/login', [CustomerController::class, 'login']);
Route::post('/reset-password', [CustomerController::class, 'resetPassword']);
Route::post('customers/logout', [CustomerController::class, 'logout']);
// Открытый маршрут для создания/обновления профиля
Route::put('customers/update', [CustomersCrudController::class, 'createAndUpdate']);
Route::get('/cart', [CartController::class, 'getUserCart']);
Route::post('/cart/save', [CartController::class, 'saveCart']); // Универсальный метод для сохранения корзины
Route::post('/cart/finish', [CartController::class, 'saveFinishCart']); // Универсальный метод для сохранения корзины
Route::delete('cart/session/{session_id}/item/{product_id}', [CartController::class, 'removeItemBySessionId']);
Route::post('/orders', [OrderController::class, 'createOrder']);

Route::middleware('auth_api')->get('cart', [CartController::class, 'cart']);


// Защищенные маршруты для авторизованных покупателей
Route::middleware('auth_api')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/{id}', [CartController::class, 'show']); // Просмотр корзины авторизованного пользователя
        Route::put('/{id}', [CartController::class, 'update']); // Обновление корзины для авторизованного пользователя
        Route::delete('/{id}', [CartController::class, 'destroy']); // Удаление корзины авторизованного пользователя
        // Новый маршрут для удаления конкретного товара из корзины
        Route::delete('/{cart_id}/item/{product_id}', [CartController::class, 'removeItemFromCart']);
    });

    // Профиль (переносим на CustomersCrudController)
    Route::get('customers/profile', [CustomersCrudController::class, 'getProfile']);
    Route::put('customers/update', [CustomersCrudController::class, 'updateProfile']);
    Route::get('customers/profile/details', [CustomersCrudController::class, 'getProfileDetails']);
    Route::post('customers/reset-password', [CustomersCrudController::class, 'resetPassword']);
    Route::get('customers/billing-data', [CustomersCrudController::class, 'getBillingData']);
    Route::get('customers/delivery-data', [CustomersCrudController::class, 'getDeliveryData']);

    // Адреса
    Route::get('addresses', [AddressController::class, 'index']);
    Route::post('addresses', [AddressController::class, 'store']);
    Route::delete('addresses/{id}', [AddressController::class, 'destroy']);

    // Компания
    Route::get('company', [CompanyController::class, 'index']);
    Route::put('company', [CompanyController::class, 'update']);

    // Инвойсы
    Route::get('invoices', [InvoiceController::class, 'index']);
    Route::get('invoices/{id}', [InvoiceController::class, 'show']);
    Route::post('invoices', [InvoiceController::class, 'store']);
});


// Продукты
Route::get('products', [ProductController::class, 'index']);
Route::get('product/{slug}', [ProductController::class, 'showBySlug']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::get('categories/{slug}/products', [CategoryController::class, 'getProductsByCategory']);
Route::get('tags', [TagController::class, 'index']);
Route::get('tags/{id}', [TagController::class, 'show']);
// Маршруты для корзины
Route::post('cart', [CartController::class, 'store']);          // Добавить товары в корзину
Route::get('cart/{id}', [CartController::class, 'show']);       // Получить корзину по ID
Route::put('cart/{id}', [CartController::class, 'update']);     // Обновить корзину (завершение заказа)
Route::delete('cart/{id}', [CartController::class, 'destroy']); // Удалить корзину

Route::post('/abandoned-cart', [App\Http\Controllers\Api\AbandonedController::class, 'store']);
Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
