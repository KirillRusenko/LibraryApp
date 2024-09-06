<?php

namespace App\Http\Middleware;

use App\Services\RabbitMQService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLogger
{
    protected $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = new $rabbitMQService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $content = str_contains('auth', $request->path()) ? $response->getContent(): null;

        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'uri' => $request->path(),
            'method' => $request->getMethod(),
            'content' => $content,
        ];

        $this->rabbitMQService->publishMessage('api_logs', json_encode($logData));
        $this->rabbitMQService->close();

        return $response;
    }
}
