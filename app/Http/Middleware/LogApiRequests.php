<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    /**
     * Fields to exclude from logging (sensitive data)
     *
     * @var array
     */
    protected $excludedFields = [
        'password',
        'password_confirmation',
        // 'token',
        // 'api_token',
        'secret',
        'authorization',
        'credit_card',
        'cvv',
        'ssn',
    ];

    /**
     * Headers to exclude from logging
     *
     * @var array
     */
    protected $excludedHeaders = [
        // 'authorization',
        'cookie',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        // Log request
        $this->logRequest($request);

        // Process request
        $response = $next($request);

        // Calculate execution time
        $executionTime = round((microtime(true) - $startTime) * 1000, 2); // in milliseconds

        // Log response
        $this->logResponse($request, $response, $executionTime);

        return $response;
    }

    /**
     * Log the incoming request
     *
     * @param Request $request
     * @return void
     */
    protected function logRequest(Request $request)
    {
        $data = [
            'type' => 'api_request',
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $this->filterHeaders($request->headers->all()),
            'query_params' => $request->query->all(),
            'request_body' => $this->filterSensitiveData($request->all()),
            'user_id' => $this->getUserId($request),
            'timestamp' => now()->toIso8601String(),
        ];

        $logChannel = config('logging.api_channel', 'api');
        Log::channel($logChannel)->info('API Request', $data);
    }

    /**
     * Log the response
     *
     * @param Request $request
     * @param Response $response
     * @param float $executionTime
     * @return void
     */
    protected function logResponse(Request $request, Response $response, float $executionTime)
    {
        $data = [
            'type' => 'api_response',
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'status_code' => $response->getStatusCode(),
            'execution_time_ms' => $executionTime,
            'user_id' => $this->getUserId($request),
            'timestamp' => now()->toIso8601String(),
        ];

        // Only log response body for errors or if explicitly enabled
        if ($response->getStatusCode() >= 400) {
            $content = $response->getContent();
            // Try to decode JSON, otherwise use raw content (limited length)
            $data['response_body'] = $this->safeJsonDecode($content);
        }

        $logLevel = $response->getStatusCode() >= 400 ? 'warning' : 'info';
        $logChannel = config('logging.api_channel', 'api');
        Log::channel($logChannel)->{$logLevel}('API Response', $data);
    }

    /**
     * Get user ID from request if authenticated
     *
     * @param Request $request
     * @return int|string|null
     */
    protected function getUserId(Request $request)
    {
        if ($request->user('api')) {
            return $request->user('api')->id;
        }
        if ($request->user('api-driver')) {
            return $request->user('api-driver')->id;
        }
        if ($request->user('api-company')) {
            return $request->user('api-company')->id;
        }
        return null;
    }

    /**
     * Filter sensitive data from request
     *
     * @param array $data
     * @return array
     */
    protected function filterSensitiveData(array $data)
    {
        foreach ($data as $key => $value) {
            $lowerKey = strtolower($key);
            
            // Check if key contains any excluded field
            foreach ($this->excludedFields as $excluded) {
                if (str_contains($lowerKey, $excluded)) {
                    $data[$key] = '[REDACTED]';
                    continue 2;
                }
            }

            // Recursively filter nested arrays
            if (is_array($value)) {
                $data[$key] = $this->filterSensitiveData($value);
            }
        }

        return $data;
    }

    /**
     * Filter sensitive headers
     *
     * @param array $headers
     * @return array
     */
    protected function filterHeaders(array $headers)
    {
        $filtered = [];
        
        foreach ($headers as $key => $value) {
            $lowerKey = strtolower($key);
            
            if (in_array($lowerKey, $this->excludedHeaders)) {
                $filtered[$key] = '[REDACTED]';
            } else {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * Safely decode JSON, return original if not valid JSON
     *
     * @param string $content
     * @return mixed
     */
    protected function safeJsonDecode($content)
    {
        if (empty($content)) {
            return null;
        }

        $decoded = json_decode($content, true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // If not JSON, return truncated string
        return mb_substr($content, 0, 500);
    }
}

