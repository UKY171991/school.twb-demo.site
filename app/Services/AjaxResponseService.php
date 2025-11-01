<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Exception;

class AjaxResponseService
{
    /**
     * Create a success response
     */
    public static function success(string $message = 'Operation completed successfully', $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => 'SUCCESS',
            'timestamp' => now()->toISOString()
        ], $code);
    }

    /**
     * Create an error response
     */
    public static function error(string $message = 'An error occurred', $errors = null, int $code = 400, string $errorCode = 'ERROR'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $errorCode,
            'data' => null,
            'timestamp' => now()->toISOString()
        ], $code);
    }

    /**
     * Create a validation error response
     */
    public static function validationError(ValidationException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $exception->errors(),
            'code' => 'VALIDATION_ERROR',
            'data' => null,
            'timestamp' => now()->toISOString()
        ], 422);
    }

    /**
     * Create a not found response
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => null,
            'code' => 'NOT_FOUND',
            'data' => null,
            'timestamp' => now()->toISOString()
        ], 404);
    }

    /**
     * Create an unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized access'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => null,
            'code' => 'UNAUTHORIZED',
            'data' => null,
            'timestamp' => now()->toISOString()
        ], 403);
    }

    /**
     * Create a server error response
     */
    public static function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => null,
            'code' => 'SERVER_ERROR',
            'data' => null,
            'timestamp' => now()->toISOString()
        ], 500);
    }

    /**
     * Create a DataTables response
     */
    public static function datatable(int $draw, int $totalRecords, int $filteredRecords, $data): JsonResponse
    {
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Create a Select2 response
     */
    public static function select2($results, bool $hasMore = false): JsonResponse
    {
        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $hasMore
            ]
        ]);
    }

    /**
     * Handle exceptions and return appropriate response
     */
    public static function handleException(Exception $exception, bool $debug = false): JsonResponse
    {
        // Log the exception
        logger()->error('AJAX Exception: ' . $exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString()
        ]);

        if ($exception instanceof ValidationException) {
            return self::validationError($exception);
        }

        if ($debug) {
            return self::serverError($exception->getMessage());
        }

        return self::serverError('An unexpected error occurred');
    }

    /**
     * Create a response with toast notification data
     */
    public static function withToast(string $type, string $title, string $message, $data = null): JsonResponse
    {
        return response()->json([
            'success' => $type === 'success',
            'message' => $message,
            'data' => $data,
            'toast' => [
                'type' => $type, // success, error, warning, info
                'title' => $title,
                'message' => $message,
                'duration' => self::getToastDuration($type)
            ],
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get toast duration based on type
     */
    private static function getToastDuration(string $type): int
    {
        return match($type) {
            'success' => 3000,
            'info' => 4000,
            'warning' => 5000,
            'error' => 6000,
            default => 4000
        };
    }

    /**
     * Create a redirect response for AJAX
     */
    public static function redirect(string $url, string $message = 'Redirecting...'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'redirect' => $url,
            'data' => null,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Create a modal response
     */
    public static function modal(string $html, string $title = '', array $options = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'modal' => [
                'html' => $html,
                'title' => $title,
                'options' => $options
            ],
            'timestamp' => now()->toISOString()
        ]);
    }
}