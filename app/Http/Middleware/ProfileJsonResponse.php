<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class ProfileJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var JsonResponse $response */
        $response = $next($request);

        if ($this->shouldAddDebugInfo($request, $response)) {
            $this->addDebugInfo($response);
        }

        return $response;
    }

    /**
     * @param $request
     * @param $response
     * @return bool
     */
    private function shouldAddDebugInfo($request, $response): bool
    {
        return $response instanceof JsonResponse
            && $request->has('_debug')
            && app('debugbar')->isEnabled();
    }

    /**
     * @param JsonResponse $response
     */
    private function addDebugInfo(JsonResponse $response): void
    {
        $statements = app('debugbar')->getData()['queries']['statements'];

        $queries = collect($statements)->map->sql;

        $response->setData($response->getData(true) + [
            '_debug' => [
                'queries' => $queries,
                'queries_count' => $queries->count(),
            ],
        ]);
    }
}
