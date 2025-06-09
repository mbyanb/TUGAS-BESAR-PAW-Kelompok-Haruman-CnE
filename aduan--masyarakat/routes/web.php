<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Reports routes
    Route::resource('reports', ReportController::class);
    Route::put('/reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.update-status')->middleware('admin');
    Route::get('/reports/files/{fileId}/download', [ReportController::class, 'downloadFile'])->name('reports.download-file');
    
    // Comments routes
    Route::post('/reports/{report}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Articles routes
    Route::resource('articles', ArticleController::class);
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{targetUser}', [AdminController::class, 'showUser'])->name('users.show');
    Route::put('/users/{targetUser}/role', [AdminController::class, 'updateUserRole'])->name('users.update-role');
    Route::delete('/users/{targetUser}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Report management
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::post('/reports/bulk-update', [AdminController::class, 'bulkUpdateReports'])->name('reports.bulk-update');
    Route::delete('/reports/{report}', [AdminController::class, 'deleteReport'])->name('reports.delete');
    
    // Comment management
    Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
    Route::delete('/comments/{comment}', [AdminController::class, 'deleteComment'])->name('comments.delete');
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::redirect('/admin', '/admin/dashboard');
});
