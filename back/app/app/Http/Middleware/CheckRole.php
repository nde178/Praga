<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $roleSlug)
    {
        // Проверяем, аутентифицирован ли пользователь
        if (!auth()->check()) {
            return redirect('login'); // Если пользователь не аутентифицирован, перенаправляем на страницу входа
        }

        $user = auth()->user();

        // Если у пользователя есть полный доступ, разрешаем доступ к маршруту
        if ($user->hasAllPermissions()) {
            return $next($request);
        }

        // Если у пользователя нет необходимой роли, запрещаем доступ
        if (!$user->hasRole($roleSlug)) {
            abort(403, 'У вас нет доступа к этой странице.');
        }

        return $next($request);
    }


}
