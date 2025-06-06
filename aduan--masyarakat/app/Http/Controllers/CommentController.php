<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request, Report $report)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
        
        Comment::create([
            'content' => $request->content,
            'report_id' => $report->id,
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }
    
    public function destroy(Comment $comment)
    {
        $user = Auth::user();
        
        // Hanya admin atau pemilik komentar yang bisa menghapus
        if ($user->isAdmin() || $comment->user_id === $user->id) {
            $comment->delete();
            return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
        }
        
        return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus komentar ini.');
    }
}