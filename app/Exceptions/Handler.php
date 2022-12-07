<?php

namespace App\Exceptions;

use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // object not found exception
        $this->renderable(function(NotFoundHttpException $e, $request) {
            if ($request->is('api/*') || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => '',
                    'errors' => [
                        'not_found' => [
                            __('api.object_not_found')
                        ]
                    ]
                ], HttpResponse::HTTP_NOT_FOUND);
            }
        });

        // forbidden
        $this->renderable(function (HttpException $e, $request) {
            if ($request->is('api/*') || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => '',
                    'errors' => [
                        'exception' => [
                            __('api.403_forbidden')
                        ]
                    ]
                ], HttpResponse::HTTP_FORBIDDEN);
            }
        });

        // overwritting sanctum unauthenticated response
        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                    'errors' => [
                        'unauthenticated' => [
                            __('api.unauthorised')
                        ]
                    ]
                ], HttpResponse::HTTP_UNAUTHORIZED);
            }
        });
    }
}
