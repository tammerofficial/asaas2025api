<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
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
        'password_confirmation',
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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        // Handle API requests - always return JSON for API routes
        if ($request->expectsJson() || $request->is('api/*') || $request->is('*/api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions
     */
    protected function handleApiException($request, Throwable $e)
    {
        // Validation errors
        if ($e instanceof ValidationException) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // Authentication errors
        if ($e instanceof AuthenticationException) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        // Model not found
        if ($e instanceof ModelNotFoundException) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Route not found
        if ($e instanceof NotFoundHttpException) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Endpoint not found',
            ], 404);
        }

        // Method not allowed
        if ($e instanceof MethodNotAllowedHttpException) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Method not allowed',
            ], 405);
        }

        // Generic error (only show details in debug mode)
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        
        // Use JsonResponse directly to avoid dependency on view service
        return new JsonResponse([
            'success' => false,
            'message' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            'exception' => config('app.debug') ? get_class($e) : null,
            'file' => config('app.debug') ? $e->getFile() : null,
            'line' => config('app.debug') ? $e->getLine() : null,
        ], $statusCode);
    }
}
