<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Report $report)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $report->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit komentar ini.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentar berhasil diperbarui!');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        // Check if user owns the comment or is admin
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus!');
    }
}
