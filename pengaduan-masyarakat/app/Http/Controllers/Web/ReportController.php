<?php
// app/Http/Controllers/Web/ReportController.php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('user')
            ->latest()
            ->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $categories = [
            'Infrastruktur',
            'Fasilitas Umum',
            'Kebersihan',
            'Keamanan',
            'Pelayanan Publik',
            'Lingkungan',
            'Lainnya'
        ];

        return view('reports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'images' => 'nullable|array|max:5', // Maximum 5 images
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max per image
        ]);

        $report = new Report();
        $report->title = $request->title;
        $report->category = $request->category;
        $report->description = $request->description;
        $report->location = $request->location;
        $report->status = 'pending';
        $report->user_id = Auth::id();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('reports', 'public');
                $imagePaths[] = $path;
            }
            $report->images = json_encode($imagePaths);
        }

        $report->save();

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibuat.');
    }

    public function show(Report $report)
    {
        $report->load('user', 'comments.user');
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        // Only allow owner or admin to edit
        if ($report->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $categories = [
            'Infrastruktur',
            'Fasilitas Umum',
            'Kebersihan',
            'Keamanan',
            'Pelayanan Publik',
            'Lingkungan',
            'Lainnya'
        ];

        return view('reports.edit', compact('report', 'categories'));
    }

    public function update(Request $request, Report $report)
    {
        // Only allow owner or admin to update
        if ($report->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,in_progress,resolved',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
        ]);

        // Handle image removal
        if ($request->has('remove_images')) {
            $currentImages = json_decode($report->images, true) ?? [];
            $imagesToRemove = $request->remove_images;
            
            foreach ($imagesToRemove as $imageToRemove) {
                if (in_array($imageToRemove, $currentImages)) {
                    Storage::disk('public')->delete($imageToRemove);
                    $currentImages = array_filter($currentImages, function($img) use ($imageToRemove) {
                        return $img !== $imageToRemove;
                    });
                }
            }
            
            $report->images = json_encode(array_values($currentImages));
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $currentImages = json_decode($report->images, true) ?? [];
            
            foreach ($request->file('images') as $image) {
                if (count($currentImages) < 5) { // Max 5 images
                    $path = $image->store('reports', 'public');
                    $currentImages[] = $path;
                }
            }
            
            $report->images = json_encode($currentImages);
        }

        $report->update([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'location' => $request->location,
            'status' => $request->status ?? $report->status,
            'images' => $report->images,
        ]);

        return redirect()->route('reports.show', $report)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        // Only allow owner or admin to delete
        if ($report->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Delete associated images
        if ($report->images) {
            $images = json_decode($report->images, true);
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $report->delete();

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dihapus.');
    }
}