<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class LogTokenComparison
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     */

    public function handle($request, Closure $next)

    {
        Log::info('Прошли через middleware LogTokenComparison');
        Log::info('Все заголовки запроса:', $request->headers->all());

        // Получаем токен из заголовка
        $token = $request->bearerToken();
        Log::info('Получен токен из запроса:', ['token' => $token]);
        $customer = $request->user('api');
        // Логируем процесс сравнения токена
        if ($customer) {
            Log::info('Пользователь авторизован:', ['customer' => $customer]);
        } else {
            Log::warning('Авторизация пользователя не удалась');
        }

        if ($token) {
            $this->compareTokens($token);
        }

        return $next($request);
    }
    /**
     * Метод для логирования и сравнения токенов.
     *
     * @param string $token
     * @return bool
     */
    public function compareTokens(string $token): bool
    {
        // Разделяем токен на ID и сам токен
        $parts = explode('|', $token);

        if (count($parts) !== 2) {
            Log::warning('Неправильный формат токена', ['token' => $token]);
            return false;
        }

        $tokenId = $parts[0]; // ID токена
        $plainTextToken = $parts[1]; // Сам токен без хеша

        Log::info('Токен разбит на части', ['tokenId' => $tokenId, 'plainTextToken' => $plainTextToken]);

        // Находим запись токена в базе данных
        $tokenRecord = PersonalAccessToken::find($tokenId);

        if ($tokenRecord) {
            $hashedToken = $tokenRecord->token;
            Log::info('Токен найден в базе данных', ['hashedToken' => $hashedToken]);

            $isMatch = Hash::check($plainTextToken, $hashedToken);
            Log::info('Результат сравнения токена', ['isMatch' => $isMatch]);

            return $isMatch;
        } else {
            Log::warning('Токен не найден в базе данных', ['tokenId' => $tokenId]);
            return false;
        }
    }


}
