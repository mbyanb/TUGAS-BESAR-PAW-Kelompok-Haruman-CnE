<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Initialize stats with safe defaults
        $stats = [
            'total_reports' => 0,
            'pending_reports' => 0,
            'in_progress_reports' => 0,
            'resolved_reports' => 0,
            'rejected_reports' => 0,
            'total_users' => 0,
            'total_articles' => 0,
        ];

        try {
            // Get statistics with error handling
            $stats['total_reports'] = Report::count();
            $stats['pending_reports'] = Report::where('status', 'pending')->count();
            $stats['in_progress_reports'] = Report::where('status', 'in-progress')->count();
            $stats['resolved_reports'] = Report::where('status', 'resolved')->count();
            $stats['rejected_reports'] = Report::where('status', 'rejected')->count();

            if ($user->isAdmin()) {
                $stats['total_users'] = User::count();
                $stats['total_articles'] = Article::count();
                $stats['recent_reports'] = Report::with(['user:id,name'])
                                               ->latest()
                                               ->limit(5)
                                               ->get();
            } else {
                $stats['user_reports'] = Report::where('user_id', $user->id)->count();
                $stats['user_pending'] = Report::where('user_id', $user->id)->where('status', 'pending')->count();
                $stats['user_resolved'] = Report::where('user_id', $user->id)->where('status', 'resolved')->count();
                $stats['my_recent_reports'] = Report::where('user_id', $user->id)
                                                   ->latest()
                                                   ->limit(5)
                                                   ->get();
            }
        } catch (\Exception $e) {
            // If tables don't exist, show error message
            return view('dashboard.index', [
                'stats' => $stats,
                'user' => $user,
                'error' => 'Database tables are not set up properly. Please run migrations.'
            ]);
        }

        return view('dashboard.index', compact('stats', 'user'));
    }
}
