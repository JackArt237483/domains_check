<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DomainCheckerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DomainCheckController extends Controller
{
    public function __construct(protected DomainCheckerService $domainChecker)
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Проверяет доступность доменов из запроса.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        try {
            $request->validate([
                'domains' => 'required|string',
            ]);

            // Разбиваем входные данные (запятая или новая строка)
            $domains = array_filter(
                array_map('trim', explode(',', str_replace("\n", ',', $request->input('domains'))))
            );

            // Проверка доменов
            $results = $this->domainChecker->checkDomains($domains);

            // Формируем JSON-ответ
            return response()->json(
                array_map(fn($result) => $result->toArray(), $results)
            );
        } catch (\Exception $e) {
            Log::error('Ошибка при проверке доменов: ' . $e->getMessage());
            return response()->json(['error' => 'Произошла ошибка при проверке доменов'], 500);
        }
    }
}
