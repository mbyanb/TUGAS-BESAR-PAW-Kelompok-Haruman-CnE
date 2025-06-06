<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Article::with('user');

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->latest()->paginate(10);

        return view('articles.index', compact('articles', 'user'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        // Only admin can create articles
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $user = Auth::user();
        $reports = Report::all();
        return view('articles.create', compact('reports', 'user'));
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request)
    {
        // Only admin can create articles
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'related_report_id' => 'nullable|exists:reports,id',
        ]);

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'related_report_id' => $request->related_report_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dibuat!');
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        $user = Auth::user();
        $article->load(['user', 'relatedReport']);
        
        // Increment views
        $article->increment('views');

        return view('articles.show', compact('article', 'user'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article)
    {
        // Only admin can edit articles
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $user = Auth::user();
        $reports = Report::all();
        return view('articles.edit', compact('article', 'reports', 'user'));
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, Article $article)
    {
        // Only admin can update articles
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'related_report_id' => 'nullable|exists:reports,id',
        ]);

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'related_report_id' => $request->related_report_id,
        ]);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Remove the specified article.
     */
    public function destroy(Article $article)
    {
        // Only admin can delete articles
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus!');
    }
}
