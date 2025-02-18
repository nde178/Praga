<?php


use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');
// Отключаем регистрацию
Auth::routes(['register' => false]);

// Полный доступ только для администратора
Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('users', UserController::class);  // Доступ к пользователям
    Route::resource('roles', RoleController::class);  // Доступ к ролям
    Route::resource('invoices', InvoiceController::class); // Доступ к инвойсам
});

// Доступ контент-менеджера к остальной части системы
Route::group(['middleware' => ['role:content_manager']], function () {
    Route::resource('categories', CategoryController::class);  // Категории
    Route::resource('products', ProductController::class);     // Продукты
    // другие маршруты для контент-менеджеров
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);
Route::resource('products', ProductController::class);
Route::get('products/category/{id}', [ProductController::class, 'productsByCategory'])->name('products.byCategory');
