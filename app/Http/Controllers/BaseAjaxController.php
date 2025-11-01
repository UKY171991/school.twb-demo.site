<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

abstract class BaseAjaxController extends BaseController
{
    /**
     * Return success response
     */
    protected function successResponse(string $message = 'Operation completed successfully', $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => 'SUCCESS'
        ], $code);
    }

    /**
     * Return error response
     */
    protected function errorResponse(string $message = 'An error occurred', $errors = null, int $code = 400, string $errorCode = 'ERROR'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $errorCode,
            'data' => null
        ], $code);
    }

    /**
     * Return validation error response
     */
    protected function validationErrorResponse(ValidationException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $exception->errors(),
            'code' => 'VALIDATION_ERROR',
            'data' => null
        ], 422);
    }

    /**
     * Return not found response
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => null,
            'code' => 'NOT_FOUND',
            'data' => null
        ], 404);
    }

    /**
     * Return unauthorized response
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized access'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => null,
            'code' => 'UNAUTHORIZED',
            'data' => null
        ], 403);
    }

    /**
     * Return server error response
     */
    protected function serverErrorResponse(string $message = 'Internal server error'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => null,
            'code' => 'SERVER_ERROR',
            'data' => null
        ], 500);
    }

    /**
     * Handle AJAX request with automatic error handling
     */
    protected function handleAjaxRequest(callable $callback): JsonResponse
    {
        try {
            $result = $callback();
            
            if ($result instanceof JsonResponse) {
                return $result;
            }
            
            return $this->successResponse('Operation completed successfully', $result);
            
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('The requested resource was not found');
        } catch (Exception $e) {
            // Log the error for debugging
            logger()->error('AJAX Request Error: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $this->user->id ?? null,
                'school_id' => $this->school->id ?? null,
                'request_url' => request()->url(),
                'request_data' => request()->all()
            ]);

            if (config('app.debug')) {
                return $this->serverErrorResponse($e->getMessage());
            }

            return $this->serverErrorResponse('An unexpected error occurred');
        }
    }

    /**
     * Validate AJAX request
     */
    protected function validateAjaxRequest(Request $request, array $rules, array $messages = []): array
    {
        return $request->validate($rules, $messages);
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjaxRequest(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson();
    }

    /**
     * Ensure request is AJAX or return error
     */
    protected function requireAjaxRequest(Request $request): ?JsonResponse
    {
        if (!$this->isAjaxRequest($request)) {
            return $this->errorResponse('This endpoint only accepts AJAX requests', null, 400, 'INVALID_REQUEST_TYPE');
        }

        return null;
    }

    /**
     * Return paginated response for DataTables
     */
    protected function datatableResponse($query, Request $request, array $columns = []): JsonResponse
    {
        try {
            $draw = $request->get('draw', 1);
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $searchValue = $request->get('search.value', '');
            $orderColumn = $request->get('order.0.column', 0);
            $orderDir = $request->get('order.0.dir', 'asc');

            // Apply school context if not super admin
            $query = $this->applySchoolContext($query);

            // Count total records before filtering
            $totalRecords = $query->count();

            // Apply search if provided
            if (!empty($searchValue) && !empty($columns)) {
                $query->where(function($q) use ($columns, $searchValue) {
                    foreach ($columns as $column) {
                        $q->orWhere($column, 'like', "%{$searchValue}%");
                    }
                });
            }

            // Count filtered records
            $filteredRecords = $query->count();

            // Apply ordering
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }

            // Apply pagination
            $data = $query->skip($start)->take($length)->get();

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);

        } catch (Exception $e) {
            logger()->error('DataTable Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred while loading data'
            ]);
        }
    }

    /**
     * Return response for select2 dropdown
     */
    protected function select2Response($query, Request $request, string $textColumn = 'name', string $idColumn = 'id'): JsonResponse
    {
        try {
            $search = $request->get('q', '');
            $page = $request->get('page', 1);
            $perPage = 20;

            // Apply school context
            $query = $this->applySchoolContext($query);

            // Apply search
            if (!empty($search)) {
                $query->where($textColumn, 'like', "%{$search}%");
            }

            // Get paginated results
            $results = $query->select($idColumn, $textColumn)
                           ->skip(($page - 1) * $perPage)
                           ->take($perPage)
                           ->get();

            // Check if there are more results
            $hasMore = $query->skip($page * $perPage)->exists();

            return response()->json([
                'results' => $results->map(function($item) use ($textColumn, $idColumn) {
                    return [
                        'id' => $item->{$idColumn},
                        'text' => $item->{$textColumn}
                    ];
                }),
                'pagination' => [
                    'more' => $hasMore
                ]
            ]);

        } catch (Exception $e) {
            logger()->error('Select2 Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'results' => [],
                'pagination' => ['more' => false],
                'error' => 'An error occurred while loading options'
            ]);
        }
    }
}