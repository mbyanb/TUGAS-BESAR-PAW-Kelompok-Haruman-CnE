<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Report::with(['user', 'comments']);

        // If not admin, only show user's reports
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
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

        $reports = $query->latest()->paginate(10);

        return view('reports.index', compact('reports', 'user'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        $user = Auth::user();
        return view('reports.create', compact('user'));
    }

    /**
     * Store a newly created report.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'location' => 'nullable|string|max:255',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $report = Report::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'location' => $request->location,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('reports', 'public');
                $report->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat!');
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        $user = Auth::user();
        $report->load(['user', 'comments.user', 'files']);
        
        // Check if user can view this report
        if (!$user->isAdmin() && $report->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reports.show', compact('report', 'user'));
    }

    /**
     * Show the form for editing the specified report.
     */
    public function edit(Report $report)
    {
        $user = Auth::user();
        
        // Only report owner can edit and only if pending
        if ($report->user_id !== Auth::id() || $report->status !== 'pending') {
            abort(403);
        }

        return view('reports.edit', compact('report', 'user'));
    }

    /**
     * Menampilkan laporan dari pengguna tertentu.
     */
    public function Reports(User $user)
    {
        // Ambil laporan yang terkait dengan pengguna
        $reports = Report::where('user_id', $user->id)->get();
        return view('reports.user', compact('user', 'reports'));
    }

    /**
     * Update the specified report.
     */
    public function update(Request $request, Report $report)
    {
        // Only report owner can update (and only if pending)
        if ($report->user_id !== Auth::id() || $report->status !== 'pending') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'location' => 'nullable|string|max:255',
        ]);

        $report->update($request->only(['title', 'description', 'category', 'location']));

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil diperbarui!');
    }

    /**
     * Remove the specified report.
     */
    public function destroy(Report $report)
    {
        // Only report owner can delete (and only if pending)
        if ($report->user_id !== Auth::id() || $report->status !== 'pending') {
            abort(403);
        }

        // Delete associated files
        foreach ($report->files as $file) {
            Storage::disk('public')->delete($file->path);
        }

        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus!');
    }

    /**
     * Update report status (Admin only)
     */
    public function updateStatus(Request $request, Report $report)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,in-progress,resolved,rejected',
        ]);

        $report->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    /**
     * Store a comment for the report.
     */
    public function storeComment(Request $request, Report $report)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $report->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
