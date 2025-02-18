<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();  // Получаем все роли
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');  // Форма для создания роли
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'slug' => 'required|unique:roles',
        ]);

        // Создание новой роли
        Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('roles.index')->with('success', 'Роль создана!');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));  // Форма для редактирования роли
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'slug' => 'required|unique:roles,slug,' . $role->id,
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')->with('success', 'Роль обновлена!');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Роль удалена!');
    }
}
