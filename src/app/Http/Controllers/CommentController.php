<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Product;

class CommentController extends Controller
{

    public function store(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'ログインが必要です'], 401);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'コメントが追加されました',
            'comment' => [
                'id' => $comment->id,
                'user' => Auth::user()->name,
                'profile_image' => Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('storage/default-avatar.png'),
                'content' => $comment->content,
            ]
        ]);
    }
}
