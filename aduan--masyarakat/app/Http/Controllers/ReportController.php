<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    // ... method index, create, show, edit (tidak ada perubahan) ...

    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Report::with(['user', 'comments']);

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

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
        return view('reports.create');
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
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $report = Report::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'location' => $request->location,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // === PERUBAHAN DI SINI ===
                // Mengubah 'reports' menjadi 'images'
                $path = $file->store('images', 'public');
                
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
        
        if (!$user->isAdmin() && $report->user_id !== $user->id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MELIHAT LAPORAN INI.');
        }

        return view('reports.show', compact('report', 'user'));
    }

    /**
     * Show the form for editing the specified report.
     */
    public function edit(Report $report)
    {
        if ($report->user_id !== Auth::id() || $report->status !== 'pending') {
            abort(403, 'LAPORAN TIDAK DAPAT DIEDIT.');
        }

        return view('reports.edit', compact('report'));
    }

    /**
     * Update the specified report in storage.
     */
    public function update(Request $request, Report $report)
    {
        if ($report->user_id !== Auth::id() || $report->status !== 'pending') {
            abort(403, 'LAPORAN TIDAK DAPAT DIPERBARUI.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'location' => 'nullable|string|max:255',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'delete_files' => 'nullable|array'
        ]);

        $report->update($request->only(['title', 'description', 'category', 'location']));

        if ($request->has('delete_files')) {
            foreach ($report->files as $file) {
                if (in_array($file->id, $request->delete_files)) {
                    Storage::disk('public')->delete($file->path);
                    $file->delete();
                }
            }
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // === PERUBAHAN DI SINI JUGA ===
                // Mengubah 'reports' menjadi 'images' agar konsisten
                $path = $file->store('images', 'public');

                $report->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil diperbarui!');
    }
    
    // ... method destroy dan lainnya (tidak ada perubahan) ...
    public function destroy(Report $report)
    {
        if ($report->user_id !== Auth::id() || $report->status !== 'pending') {
            abort(403, 'LAPORAN TIDAK DAPAT DIHAPUS.');
        }

        foreach ($report->files as $file) {
            Storage::disk('public')->delete($file->path);
        }
        
        $report->files()->delete();
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus!');
    }
    
    public function updateStatus(Request $request, Report $report)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'HANYA ADMIN YANG DAPAT MENGUBAH STATUS.');
        }

        $request->validate([
            'status' => ['required', Rule::in(['pending', 'in-progress', 'resolved', 'rejected'])],
        ]);

        $report->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan berhasil diperbarui!');
    }
    
    public function storeComment(Request $request, Report $report)
    {
        $request->validate(['content' => 'required|string']);

        $report->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function userReports(User $user)
    {
        $reports = Report::where('user_id', $user->id)->latest()->get();
        return view('reports.user', compact('user', 'reports'));
    }
}