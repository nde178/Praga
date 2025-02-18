<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    protected function unauthenticated($request, AuthenticationException $exception): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        // Проверяем, что это AJAX-запрос или запрос с ожиданием JSON-ответа
        if ($request->expectsJson()) {
            return response()->json(['message' => 'сообщение из Hendler'], 401);
        }

        // Для обычных запросов можно настроить редирект на страницу логина или другую страницу
        return redirect()->guest(route('index'));
    }
}
