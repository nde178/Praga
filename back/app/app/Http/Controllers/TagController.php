<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('order')->get();
        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        $nextOrder = Tag::max('order') + 1;
        return view('tags.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'order' => 'required|integer',
        ]);

        Tag::create([
            'name' => $request->name,
            'order' => $request->order,
        ]);

        return redirect()->route('tags.index')->with('success', 'Тег создан!');
    }

    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required',
            'order' => 'required|integer',
        ]);

        $tag->update([
            'name' => $request->name,
            'order' => $request->order,
        ]);

        return redirect()->route('tags.index')->with('success', 'Тег обновлён!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')->with('success', 'Тег удалён!');
    }
}
