<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController
{
    public function index()
    {
        $categories = Category::orderBy('order')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|image',
        ]);

        // Сохранение изображения
        $imagePath = $request->file('image')->store('categories', 'public');
        $nextOrder = Category::max('order') + 1;

        // Создание категории с автоматическим slug
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Генерация slug на основе названия
            'image' => $imagePath,
            'order' => $nextOrder,
        ]);

        return redirect()->route('categories.index')->with('success', 'Категория создана!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->update(['image' => $imagePath]);
        }

        // Обновление имени и автоматической генерации slug
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Генерация slug на основе названия
        ]);

        return redirect()->route('categories.index')->with('success', 'Категория обновлена!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Категория удалена!');
    }
}
