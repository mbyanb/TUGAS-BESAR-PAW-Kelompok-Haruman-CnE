<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles
     */
    public function index(Request $request)
    {
        $query = Article::with(['user:id,name']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $articles = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($articles, 'Articles retrieved successfully');
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:pengumuman,update,edukasi,kebijakan,berita',
            'related_report_id' => 'nullable|exists:reports,id',
        ]);

        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'related_report_id' => $request->related_report_id,
            'user_id' => Auth::id(),
        ]);

        $article->load(['user', 'relatedReport']);

        return $this->successResponse($article, 'Article created successfully', 201);
    }

    /**
     * Display the specified article
     */
    public function show(Article $article)
    {
        $article->load(['user', 'relatedReport']);
        
        // Increment views
        $article->increment('views');

        return $this->successResponse($article, 'Article retrieved successfully');
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:pengumuman,update,edukasi,kebijakan,berita',
            'related_report_id' => 'nullable|exists:reports,id',
        ]);

        $article->update($request->only(['title', 'content', 'category', 'related_report_id']));

        return $this->successResponse($article, 'Article updated successfully');
    }

    /**
     * Remove the specified article
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return $this->successResponse(null, 'Article deleted successfully');
    }
}
