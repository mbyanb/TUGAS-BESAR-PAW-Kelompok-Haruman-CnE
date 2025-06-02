<?php
// app/Http/Controllers/Api/ArticleController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['report', 'user'])
            ->orderBy('created_at', 'desc');

        // Only show published articles for non-admin users
        if (!$request->user()->isAdmin()) {
            $query->where('status', 'published');
        }

        $articles = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    public function store(Request $request)
    {
        // Only admin can create articles
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'report_id' => 'required|exists:reports,id',
            'status' => 'required|in:draft,published',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'report_id' => $request->report_id,
            'user_id' => $request->user()->id,
            'status' => $request->status,
        ]);

        $article->load(['report', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Article created successfully',
            'data' => $article
        ], 201);
    }

    public function show($id)
    {
        $article = Article::with(['report', 'user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    public function update(Request $request, $id)
    {
        // Only admin can update articles
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'report_id' => 'sometimes|required|exists:reports,id',
            'status' => 'sometimes|required|in:draft,published',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $article->update($request->only(['title', 'content', 'report_id', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully',
            'data' => $article
        ]);
    }

    public function destroy($id)
    {
        // Only admin can delete articles
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully'
        ]);
    }
}