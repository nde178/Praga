<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    // Возвращает список всех категорий
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    // Возвращает конкретную категорию по ID
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return response()->json($category);
    }

    // Возвращает продукты для конкретной категории по slug
    public function getProductsByCategory($slug)
    {
        // Находим категорию по slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Получаем продукты, связанные с категорией
        $products = $category->products;

        return response()->json([
            'category' => $category->name,
            'products' => $products
        ]);
    }
}

