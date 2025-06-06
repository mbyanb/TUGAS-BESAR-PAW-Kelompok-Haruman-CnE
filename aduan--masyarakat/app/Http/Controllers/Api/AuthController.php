<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of reports
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Report::with(['user', 'comments.user']);

        // If not admin, only show user's reports
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $reports = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($reports, 'Reports retrieved successfully');
    }

    /**
     * Store a newly created report
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:infrastruktur,lingkungan,pelayanan,keamanan,kesehatan,pendidikan,lainnya',
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

        $report->load(['user', 'files']);

        return $this->successResponse($report, 'Report created successfully', 201);
    }

    /**
     * Display the specified report
     */
    public function show(Report $report)
    {
        // Check if user can view this report
        if (!Auth::user()->isAdmin() && $report->user_id !== Auth::id()) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $report->load(['user', 'comments.user', 'files']);

        return $this->successResponse($report, 'Report retrieved successfully');
    }

    /**
     * Update the specified report
     */
    public function update(Request $request, Report $report)
    {
        // Only report owner can update (and only if pending)
        if ($report->user_id !== Auth::id() || $report->status !== 'pending') {
            return $this->errorResponse('Unauthorized or report cannot be updated', 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:infrastruktur,lingkungan,pelayanan,keamanan,kesehatan,pendidikan,lainnya',
            'location' => 'nullable|string|max:255',
        ]);

        $report->update($request->only(['title', 'description', 'category', 'location']));

        return $this->successResponse($report, 'Report updated successfully');
    }

    /**
     * Remove the specified report
     */
    public function destroy(Report $report)
    {
        // Only report owner can delete (and only if pending)
        if ($report->user_id !== Auth::id() || $report->status !== 'pending') {
            return $this->errorResponse('Unauthorized or report cannot be deleted', 403);
        }

        // Delete associated files
        foreach ($report->files as $file) {
            Storage::disk('public')->delete($file->path);
        }

        $report->delete();

        return $this->successResponse(null, 'Report deleted successfully');
    }

    /**
     * Update report status (Admin only)
     */
    public function updateStatus(Request $request, Report $report)
    {
        if (!Auth::user()->isAdmin()) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $request->validate([
            'status' => 'required|in:pending,in-progress,resolved,rejected',
            'admin_note' => 'nullable|string',
        ]);

        $report->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return $this->successResponse($report, 'Report status updated successfully');
    }

    /**
     * Get public reports for transparency
     */
    public function publicReports(Request $request)
    {
        $query = Report::with(['user:id,name'])
                      ->select(['id', 'title', 'category', 'location', 'status', 'created_at', 'user_id'])
                      ->where('status', '!=', 'rejected');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($reports, 'Public reports retrieved successfully');
    }
}
