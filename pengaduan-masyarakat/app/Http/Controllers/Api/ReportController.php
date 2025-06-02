<?php
// app/Http/Controllers/Api/ReportController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['user', 'images'])
            ->orderBy('created_at', 'desc');

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'location' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $report = Report::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'location' => $request->location,
            'user_id' => $request->user()->id,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('reports', 'public');
                
                ReportImage::create([
                    'report_id' => $report->id,
                    'image_path' => $imagePath,
                    'original_name' => $image->getClientOriginalName(),
                ]);
            }
        }

        $report->load(['user', 'images']);

        return response()->json([
            'success' => true,
            'message' => 'Report created successfully',
            'data' => $report
        ], 201);
    }

    public function show($id)
    {
        $report = Report::with(['user', 'images', 'comments.user', 'articles'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        // Check if user owns the report or is admin
        if ($report->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category' => 'sometimes|required|string',
            'location' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:pending,in_progress,resolved',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $report->update($request->only(['title', 'description', 'category', 'location', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Report updated successfully',
            'data' => $report
        ]);
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        // Check if user owns the report or is admin
        if ($report->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete associated images
        foreach ($report->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Report deleted successfully'
        ]);
    }

    public function myReports(Request $request)
    {
        $reports = Report::with(['images'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        // Only admin can update status
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Report status updated successfully',
            'data' => $report
        ]);
    }
}