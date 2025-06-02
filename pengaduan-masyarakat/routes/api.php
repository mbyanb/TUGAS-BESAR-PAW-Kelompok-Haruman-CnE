<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public reports (can be viewed without authentication)
Route::get('/reports', [ReportController::class, 'index']);
Route::get('/reports/{id}', [ReportController::class, 'show']);
Route::get('/reports/{reportId}/comments', [CommentController::class, 'index']);

// Public articles
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/recent-reports', [DashboardController::class, 'recentReports']);

    // Reports
    Route::get('/my-reports', [ReportController::class, 'myReports']);
    Route::post('/reports', [ReportController::class, 'store']);
    Route::put('/reports/{id}', [ReportController::class, 'update']);
    Route::delete('/reports/{id}', [ReportController::class, 'destroy']);

    // Comments
    Route::post('/reports/{reportId}/comments', [CommentController::class, 'store']);
    Route::put('/reports/{reportId}/comments/{commentId}', [CommentController::class, 'update']);
    Route::delete('/reports/{reportId}/comments/{commentId}', [CommentController::class, 'destroy']);

    // Likes
    Route::post('/reports/{reportId}/like', [LikeController::class, 'toggle']);
    Route::get('/reports/{reportId}/like-status', [LikeController::class, 'check']);

    // Admin only routes
    Route::middleware('admin')->group(function () {
        Route::put('/reports/{id}/status', [ReportController::class, 'updateStatus']);
        
        // Articles management
        Route::post('/articles', [ArticleController::class, 'store']);
        Route::put('/articles/{id}', [ArticleController::class, 'update']);
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
    });
});