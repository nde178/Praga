<?php


namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthenticateApi
{
    public function handle($request, Closure $next)
    {
        // Логируем начало работы middleware
        Log::info('Запуск middleware AuthenticateApi', [
            'url' => $request->fullUrl(), // Логируем полный URL запроса
            'method' => $request->method(), // Логируем HTTP-метод (GET, POST и т.д.)
            'parameters' => $request->all(), // Логируем все параметры запроса
        ]);

        // Получаем токен из query-параметра или тела запроса
        $token = $request->query('api_token') ?? $request->input('api_token');

        // Логируем полученный токен
        Log::info('Полученный токен', ['api_token' => $token]);

        if (empty($token)) {
            Log::warning('API токен отсутствует в запросе');
            return response()->json(['message' => 'API token is required'], 401);
        }

        // Поиск токена в базе данных
        $tokenData = PersonalAccessToken::where('token', $token)->first();

        if (!$tokenData) {
            Log::warning('Токен не найден в базе данных', ['api_token' => $token]);
            return response()->json(['message' => 'Invalid API token'], 403);
        }

        // Проверяем срок действия токена
        if ($tokenData->expires_at) {
            // Преобразуем expires_at в объект Carbon
            $expiresAt = Carbon::parse($tokenData->expires_at);

            // Проверяем, истек ли токен
            if ($expiresAt->isPast()) {
                Log::warning('Токен истек', ['api_token' => $token, 'expires_at' => $tokenData->expires_at]);
                return response()->json(['message' => 'API token has expired'], 403);
            }
        }

        // Логируем успешную проверку токена
        Log::info('Токен успешно проверен', ['api_token' => $token, 'user_id' => $tokenData->tokenable_id]);

        // Аутентифицируем клиента
        $customerModel = $tokenData->tokenable_type; // Получаем модель клиента
        $customer = $customerModel::find($tokenData->tokenable_id); // Получаем клиента по ID
        Auth::login($customer);

        // Логируем завершение работы middleware
        Log::info('Аутентификация клиента выполнена', ['user_id' => $customer->id]);

        return $next($request);
    }
}
