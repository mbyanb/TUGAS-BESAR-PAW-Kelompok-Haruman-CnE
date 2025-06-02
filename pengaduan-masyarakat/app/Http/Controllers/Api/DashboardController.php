<?php
// app/Http/Controllers/Api/DashboardController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            // Admin stats
            $stats = [
                'total_reports' => Report::count(),
                'pending_reports' => Report::where('status', 'pending')->count(),
                'in_progress_reports' => Report::where('status', 'in_progress')->count(),
                'resolved_reports' => Report::where('status', 'resolved')->count(),
                'total_users' => User::where('role', 'user')->count(),
                'total_articles' => Article::count(),
                'total_comments' => Comment::count(),
            ];
        } else {
            // User stats
            $stats = [
                'my_reports' => Report::where('user_id', $user->id)->count(),
                'pending_reports' => Report::where('user_id', $user->id)->where('status', 'pending')->count(),
                'in_progress_reports' => Report::where('user_id', $user->id)->where('status', 'in_progress')->count(),
                'resolved_reports' => Report::where('user_id', $user->id)->where('status', 'resolved')->count(),
                'my_comments' => Comment::where('user_id', $user->id)->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function recentReports(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            // Admin sees all recent reports
            $reports = Report::with(['user', 'images'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            // User sees their own recent reports
            $reports = Report::with(['images'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }
}