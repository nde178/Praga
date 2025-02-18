<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all(); // Получаем все продукты
        return view('products.index', compact('products'));
    }
    public function productsByCategory($categoryId = null)
    {
        if ($categoryId) {
            $category = Category::findOrFail($categoryId);
            $products = $category->products;
        } else {
            $products = Product::all();
            $category = null;
        }

        return view('products.index', compact('products', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::orderBy('order')->get();
        $categories = Category::all();
        return view('products.create', compact('tags', 'categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'new_price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'main_image' => 'required|image',
            'carousel_images.*' => 'image',   // Для карусели несколько изображений
            'stock_quantity' => 'required|integer',
            'unique_code' => 'required|unique:products,unique_code',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'required|array',
        ]);

        // Сохранение главного изображения
        $mainImagePath = $request->file('main_image')->store('products', 'public');

        // Сохранение изображений для карусели
        $carouselImages = [];
        if ($request->hasFile('carousel_images')) {
            foreach ($request->file('carousel_images') as $image) {
                $carouselImages[] = $image->store('products/carousel', 'public');
            }
        }

        // Создание продукта
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,  // Убедитесь, что поле description передаётся
            'new_price' => $request->new_price,
            'old_price' => $request->old_price,
            'main_image' => $mainImagePath,
            'carousel_images' => !empty($carouselImages) ? json_encode($carouselImages) : null, // Если нет карусельных изображений, оставляем null
            'stock_quantity' => $request->stock_quantity,
            'unique_code' => $request->unique_code,
            'slug' => Str::slug($request->name), // Генерация slug на основе названия
            'category_id' => $request->category_id,
        ]);

        // Привязка тегов
        $product->tags()->sync($request->tags);

        return redirect()->route('products.index')->with('success', 'Продукт создан!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {

        $tags = Tag::orderBy('order')->get();  // Получаем все теги
        $categories = Category::all();  // Получаем все категории
        return view('products.edit', compact('product', 'tags', 'categories'));  // Передаем данные в представление
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
//        dd($request->all());
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'new_price' => 'required|numeric',
            'old_price' => 'nullable|numeric',
            'main_image' => 'image',
            'carousel_images.*' => 'image',
            'stock_quantity' => 'required|integer',
            'unique_code' => 'required|unique:products,unique_code,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'tags' => 'required|array',
        ]);

        // Сохранение главного изображения
        $mainImagePath = $product->main_image;
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('products', 'public');
        }

        // Сохранение изображений для карусели
        $carouselImages = $product->carousel_images ? json_decode($product->carousel_images, true) : [];
        if ($request->hasFile('carousel_images')) {
            foreach ($request->file('carousel_images') as $image) {
                $carouselImages[] = $image->store('products/carousel', 'public');
            }
        }

        // Обновление продукта с новым slug
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'new_price' => $request->new_price,
            'old_price' => $request->old_price,
            'main_image' => $mainImagePath,
            'carousel_images' => json_encode($carouselImages),
            'stock_quantity' => $request->stock_quantity,
            'unique_code' => $request->unique_code,
            'slug' => Str::slug($request->name), // Генерация нового slug на основе названия
            'category_id' => $request->category_id,
        ]);

        // Привязка тегов
        $product->tags()->sync($request->tags);

        return redirect()->route('products.index')->with('success', 'Продукт обновлен!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Мягкое удаление продукта
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Продукт был успешно удален!');
    }
    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.index')->with('success', 'Продукт был восстановлен!');
    }

}
