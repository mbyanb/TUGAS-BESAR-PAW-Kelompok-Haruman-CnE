<?php
// app/Http/Controllers/Web/DashboardController.php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // HAPUS SEMUA CONSTRUCTOR MIDDLEWARE
    
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin stats
            $stats = [
                'total_reports' => Report::count(),
                'pending_reports' => Report::where('status', 'pending')->count(),
                'in_progress_reports' => Report::where('status', 'in_progress')->count(),
                'resolved_reports' => Report::where('status', 'resolved')->count(),
                'total_users' => User::where('role', 'user')->count(),
                'total_articles' => Article::count(),
            ];

            $recent_reports = Report::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            // User stats
            $stats = [
                'total_reports' => Report::where('user_id', $user->id)->count(),
                'pending_reports' => Report::where('user_id', $user->id)->where('status', 'pending')->count(),
                'in_progress_reports' => Report::where('user_id', $user->id)->where('status', 'in_progress')->count(),
                'resolved_reports' => Report::where('user_id', $user->id)->where('status', 'resolved')->count(),
            ];

            $recent_reports = Report::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', compact('stats', 'recent_reports'));
    }
}