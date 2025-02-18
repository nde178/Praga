<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // Возвращает список всех тегов
    public function index()
    {
        $tags = Tag::with('products')->get();
        return response()->json($tags);
    }

    // Возвращает конкретный тег по ID
    public function show($id)
    {
        $tag = Tag::with('products')->findOrFail($id);
        return response()->json($tag);
    }
}
