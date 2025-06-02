<?php
// app/Http/Controllers/Api/LikeController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Report;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, $reportId)
    {
        $report = Report::findOrFail($reportId);
        $userId = $request->user()->id;

        $like = Like::where('report_id', $reportId)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            // Unlike
            $like->delete();
            $report->decrementLikes();
            $isLiked = false;
        } else {
            // Like
            Like::create([
                'report_id' => $reportId,
                'user_id' => $userId,
            ]);
            $report->incrementLikes();
            $isLiked = true;
        }

        return response()->json([
            'success' => true,
            'message' => $isLiked ? 'Report liked' : 'Report unliked',
            'data' => [
                'is_liked' => $isLiked,
                'likes_count' => $report->fresh()->likes_count
            ]
        ]);
    }

    public function check(Request $request, $reportId)
    {
        $isLiked = Like::where('report_id', $reportId)
            ->where('user_id', $request->user()->id)
            ->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'is_liked' => $isLiked
            ]
        ]);
    }
}