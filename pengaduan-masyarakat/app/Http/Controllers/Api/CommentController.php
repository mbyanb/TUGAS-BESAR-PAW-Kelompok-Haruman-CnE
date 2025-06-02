<?php
// app/Http/Controllers/Api/CommentController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index($reportId)
    {
        $comments = Comment::with('user')
            ->where('report_id', $reportId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    public function store(Request $request, $reportId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $report = Report::findOrFail($reportId);

        $comment = Comment::create([
            'content' => $request->content,
            'report_id' => $reportId,
            'user_id' => $request->user()->id,
        ]);

        // Increment comments count
        $report->incrementComments();

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ], 201);
    }

    public function update(Request $request, $reportId, $commentId)
    {
        $comment = Comment::where('report_id', $reportId)
            ->where('id', $commentId)
            ->firstOrFail();

        // Check if user owns the comment
        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment->update(['content' => $request->content]);

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    public function destroy($reportId, $commentId)
    {
        $comment = Comment::where('report_id', $reportId)
            ->where('id', $commentId)
            ->firstOrFail();

        // Check if user owns the comment or is admin
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $report = Report::findOrFail($reportId);
        $comment->delete();

        // Decrement comments count
        $report->decrementComments();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}