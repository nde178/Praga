<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();  // Получаем всех пользователей
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');  // Форма для создания пользователя
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'permissions' => 'nullable|array', // Валидация прав
        ]);

        // Обработка прав пользователя (по умолчанию - нет полного доступа)
        $permissions = $request->input('permissions', ['all' => false]);

        // Создание нового пользователя
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'permissions' => json_encode($permissions), // Сохраняем права как JSON
            'remember_token' => Str::random(10), // Генерация remember_token
        ]);

        return redirect()->route('users.index')->with('success', 'Пользователь создан!');
    }



    public function edit(User $user)
    {
        return view('users.edit', compact('user'));  // Форма для редактирования пользователя
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'permissions' => 'nullable|array',
        ]);

        // Обработка прав пользователя
        $permissions = $request->input('permissions', ['all' => false]); // Устанавливаем права по умолчанию, если не переданы

        // Если пароль введён, хешируем и обновляем его
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Обновляем все данные пользователя, включая permissions и remember_token
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'permissions' => json_encode($permissions), // Преобразуем права в JSON
            'remember_token' => $user->remember_token ?? Str::random(10), // Если токен пустой, генерируем новый
        ]);

        return redirect()->route('users.index')->with('success', 'Пользователь обновлен!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Пользователь удален!');
    }
}
