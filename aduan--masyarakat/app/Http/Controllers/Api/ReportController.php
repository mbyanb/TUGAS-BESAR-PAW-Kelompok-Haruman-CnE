<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Report::with(['user', 'comments']);
        
        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan kategori
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        // Pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $reports = $query->latest()->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    public function show(Report $report): JsonResponse
    {
        $report->load(['user', 'comments.user', 'files']);
        
        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
        ]);
        
        $report = Report::create([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'location' => $request->location,
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dibuat',
            'data' => $report
        ], 201);
    }

    public function update(Request $request, Report $report): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
        ]);
        
        $report->update($request->only(['title', 'category', 'description', 'location']));
        
        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui',
            'data' => $report
        ]);
    }

    public function destroy(Report $report): JsonResponse
    {
        $report->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
        ]);
    }
}