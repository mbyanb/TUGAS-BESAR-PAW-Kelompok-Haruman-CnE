<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as AuthController;

abstract class Controller extends AuthController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return a success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = 'Error', $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Return a validation error response.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrorResponse($validator)
    {
        return $this->errorResponse(
            'Validation failed',
            422,
            $validator->errors()
        );
    }

    /**
     * Check if the current user is admin.
     *
     * @return bool
     */
    protected function isAdmin()
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Check if the current user is authenticated.
     *
     * @return bool
     */
    protected function isAuthenticated()
    {
        return auth()->check();
    }

    /**
     * Get the current authenticated user.
     *
     * @return \App\Models\User|null
     */
    protected function getCurrentUser()
    {
        return auth()->user();
    }

    /**
     * Redirect with success message.
     *
     * @param string $route
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithSuccess($route, $message = 'Operation successful')
    {
        return redirect()->route($route)->with('success', $message);
    }

    /**
     * Redirect with error message.
     *
     * @param string $route
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithError($route, $message = 'Operation failed')
    {
        return redirect()->route($route)->with('error', $message);
    }

    /**
     * Redirect back with success message.
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBackWithSuccess($message = 'Operation successful')
    {
        return redirect()->back()->with('success', $message);
    }

    /**
     * Redirect back with error message.
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBackWithError($message = 'Operation failed')
    {
        return redirect()->back()->with('error', $message);
    }

    /**
     * Handle pagination for API responses.
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function paginatedResponse($paginator, $message = 'Data retrieved successfully')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ]
        ]);
    }

    /**
     * Upload file helper.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return string|false
     */
    protected function uploadFile($file, $directory = 'uploads', $disk = 'public')
    {
        try {
            return $file->store($directory, $disk);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete file helper.
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    protected function deleteFile($path, $disk = 'public')
    {
        try {
            return \Storage::disk($disk)->delete($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Format date for display.
     *
     * @param \Carbon\Carbon|string $date
     * @param string $format
     * @return string
     */
    protected function formatDate($date, $format = 'd M Y')
    {
        if (!$date) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format($format);
    }

    /**
     * Format date time for display.
     *
     * @param \Carbon\Carbon|string $datetime
     * @param string $format
     * @return string
     */
    protected function formatDateTime($datetime, $format = 'd M Y H:i')
    {
        if (!$datetime) {
            return '-';
        }

        return \Carbon\Carbon::parse($datetime)->format($format);
    }

    /**
     * Get status badge class for UI.
     *
     * @param string $status
     * @return string
     */
    protected function getStatusBadgeClass($status)
    {
        $classes = [
            'pending' => 'badge-warning',
            'in-progress' => 'badge-info',
            'resolved' => 'badge-success',
            'rejected' => 'badge-danger',
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
        ];

        return $classes[$status] ?? 'badge-secondary';
    }

    /**
     * Get status label for display.
     *
     * @param string $status
     * @return string
     */
    protected function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu',
            'in-progress' => 'Diproses',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
        ];

        return $labels[$status] ?? ucfirst($status);
    }
}
