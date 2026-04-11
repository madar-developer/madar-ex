<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        TokenExpiredException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        if (($request->expectsJson() || $request->segment(1) === 'api') && $e instanceof TokenExpiredException) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => ['token_expired'],
                'message' => 'Token has expired',
                'code' => 401,
            ], 401);
        }

        if (($request->expectsJson() || $request->segment(1) === 'api') && $e instanceof TokenInvalidException) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => ['token_invalid'],
                'message' => 'Token is invalid',
                'code' => 401,
            ], 401);
        }

        if (($request->expectsJson() || $request->segment(1) === 'api') && $e instanceof JWTException) {
            return response()->json([
                'data' => new \stdClass,
                'errors' => ['token_error'],
                'message' => 'Token error',
                'code' => 401,
            ], 401);
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->segment(1) == 'api') {
            return response()->json([
                        'data'   => new \stdClass,
                        'errors'       => [' '],
                        'message'       => trans('words.authFailed'),
                        'code'          => getMsgCode('authFailed'),
            ]);
        }

        return redirect()->guest(route('login'));
    }
}
