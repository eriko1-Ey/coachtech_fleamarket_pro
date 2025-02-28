<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Like;
use App\Models\User;

class LikeController extends Controller
{
    public function toggleLike(Product $product)
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return response()->json(['message' => 'ログインが必要です'], 401);
        }

        $liked = $user->likedProducts()->toggle($product->id);

        // 最新のいいね数を取得
        $likeCount = $product->likes()->count();

        return response()->json([
            'liked' => !empty($liked['attached']), // いいねされた場合 true, 削除された場合 false
            'likeCount' => $likeCount
        ]);
    }
}
