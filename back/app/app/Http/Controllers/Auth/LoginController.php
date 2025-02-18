<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; // Исправлен импорт
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if (auth()->user()->isAdmin()) { // Проверьте, что метод isAdmin() существует в модели User
            return '/admin/dashboard';
        }

        return '/dashboard';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
