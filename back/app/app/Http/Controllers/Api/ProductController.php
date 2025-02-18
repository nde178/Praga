<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Возвращает список всех продуктов
    public function index()
    {
        $products = Product::with('category', 'tags')->get();

        // Преобразуем carousel_images из строки JSON в массив
        $products->each(function ($product) {
            $product->carousel_images = json_decode($product->carousel_images, true);
            $product->new_price = $product->new_price ?? $product->price;
            $product->old_price = $product->old_price ?? null;
            $product->main_image = $product->main_image ?? 'default_image.jpg';
        });

        return response()->json($products);
    }
    // Возвращает конкретный продукт по slug
    public function showBySlug($slug)
    {
        // Поиск продукта по slug
        $product = Product::with('category', 'tags')->where('slug', $slug)->firstOrFail();

        // Преобразование carousel_images из строки JSON в массив
        $product->carousel_images = json_decode($product->carousel_images, true);
        $product->new_price = $product->new_price ?? $product->price;
        $product->old_price = $product->old_price ?? null;
        $product->main_image = $product->main_image ?? 'default_image.jpg';

        return response()->json($product);
    }
}
