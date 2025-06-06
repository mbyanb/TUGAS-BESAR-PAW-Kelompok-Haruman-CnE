<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Basic Statistics
        $stats = [
            'total_users' => User::count(),
            'total_reports' => Report::count(),
            'total_articles' => Article::count(),
            'total_comments' => Comment::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('status', 'in-progress')->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
            'rejected_reports' => Report::where('status', 'rejected')->count(),
        ];

        // Recent Activities
        $recent_reports = Report::with('user')->latest()->limit(5)->get();
        $recent_users = User::where('role', 'user')->latest()->limit(5)->get();
        $recent_comments = Comment::with(['user', 'report'])->latest()->limit(5)->get();

        // Monthly Statistics
        $monthly_reports = Report::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Category Statistics
        $category_stats = Report::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->get();

        return view('admin.dashboard', compact(
            'user', 'stats', 'recent_reports', 'recent_users', 
            'recent_comments', 'monthly_reports', 'category_stats'
        ));
    }

    /**
     * Manage Users
     */
    public function users(Request $request)
    {
        $user = Auth::user();
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users', 'user'));
    }

    /**
     * Show user details
     */
    public function showUser(User $targetUser)
    {
        $user = Auth::user();
        $targetUser->load(['reports', 'comments.report']);
        
        $userStats = [
            'total_reports' => $targetUser->reports->count(),
            'pending_reports' => $targetUser->reports->where('status', 'pending')->count(),
            'resolved_reports' => $targetUser->reports->where('status', 'resolved')->count(),
            'total_comments' => $targetUser->comments->count(),
        ];

        return view('admin.users.show', compact('user', 'targetUser', 'userStats'));
    }

    /**
     * Update user role
     */
    public function updateUserRole(Request $request, User $targetUser)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $targetUser->update(['role' => $request->role]);

        return back()->with('success', 'Role user berhasil diperbarui!');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $targetUser)
    {
        // Prevent deleting self
        if ($targetUser->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $targetUser->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus!');
    }

    /**
     * Manage Reports
     */
    public function reports(Request $request)
    {
        $user = Auth::user();
        $query = Report::with(['user', 'comments']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $reports = $query->latest()->paginate(15);

        return view('admin.reports.index', compact('reports', 'user'));
    }

    /**
     * Bulk update report status
     */
    public function bulkUpdateReports(Request $request)
    {
        $request->validate([
            'report_ids' => 'required|array',
            'status' => 'required|in:pending,in-progress,resolved,rejected'
        ]);

        Report::whereIn('id', $request->report_ids)
              ->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    /**
     * Delete report
     */
    public function deleteReport(Report $report)
    {
        // Delete associated files
        foreach ($report->files as $file) {
            \Storage::disk('public')->delete($file->path);
        }

        $report->delete();

        return back()->with('success', 'Laporan berhasil dihapus!');
    }

    /**
     * Manage Comments
     */
    public function comments(Request $request)
    {
        $user = Auth::user();
        $query = Comment::with(['user', 'report']);

        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        $comments = $query->latest()->paginate(15);

        return view('admin.comments.index', compact('comments', 'user'));
    }

    /**
     * Delete comment
     */
    public function deleteComment(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus!');
    }

    /**
     * System Settings
     */
    public function settings()
    {
        $user = Auth::user();
        
        // System info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_size' => $this->getDatabaseSize(),
            'storage_size' => $this->getStorageSize(),
        ];

        return view('admin.settings', compact('user', 'systemInfo'));
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        try {
            $size = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
            ")[0]->size_mb ?? 0;
            
            return $size . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Get storage size
     */
    private function getStorageSize()
    {
        try {
            $path = storage_path('app/public');
            if (!is_dir($path)) {
                return '0 MB';
            }
            
            $size = 0;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path)
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
            
            return round($size / 1024 / 1024, 2) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
