<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'author' => 'required',
            'content' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Создание нового отзыва
        Review::create([
            'product_id' => $productId,
            'author' => $request->author,
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        return redirect()->route('products.show', $productId)->with('success', 'Отзыв добавлен!');
    }

}
