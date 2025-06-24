<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class MypageController extends Controller
{
    public function getMypage()
    {
        $user = User::find(Auth::id());

        /* ① 自分の出品・購入商品など既存の取得処理 */
        $products          = $user->products()->with('images')->latest()->get();
        $purchasedProducts = $user->purchases()->with('product.images')->latest()->get();

        /* ② チャットを購入者側・出品者側で取得 */
        $buyerChats  = Chat::with(['product.images'])->where('buyer_id',  $user->id)->get();
        $sellerChats = Chat::with(['product.images'])->where('seller_id', $user->id)->get();

        /* ③ 合体して「取引中チャット」 */
        $activeChats = $buyerChats->merge($sellerChats);

        /* ★★★ ここに追記 ★★★ */
        foreach ($activeChats as $chat) {
            // 買い手がこのチャットで評価済みか
            $chat->buyer_reviewed = Review::where('chat_id', $chat->id)
                ->where('reviewer_id', $chat->buyer_id)
                ->exists();

            // 売り手がこのチャットで評価済みか
            $chat->seller_reviewed = Review::where('chat_id', $chat->id)
                ->where('reviewer_id', $chat->seller_id)
                ->exists();
        }

        /* ④ 既存の未読数や自分のレビュー済み一覧など */
        $unreadMessagesByChat = Message::whereHas('chat', function ($q) use ($user) {
            $q->where('buyer_id', $user->id)->orWhere('seller_id', $user->id);
        })
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->get()
            ->groupBy('chat_id');

        $unreadReviewsByChat = Review::where('reviewee_id', $user->id)
            ->where('is_read', false)          // ★まだ読んでいないレビュー
            ->get()
            ->groupBy('chat_id');

        // 件数を合算しておく（タブ用）
        $unreadTotal = $unreadMessagesByChat->flatten()->count()
            + $unreadReviewsByChat->flatten()->count();

        $reviewedChatIds = Review::where('reviewer_id', $user->id)
            ->pluck('chat_id')
            ->toArray();

        /* ⑤ ビューへ */
        return view('mypage', compact(
            'user',
            'products',
            'purchasedProducts',
            'activeChats',
            'unreadMessagesByChat',
            'reviewedChatIds',
            'unreadReviewsByChat',
            'unreadTotal'
        ));
    }

    // チャット表示画面
    public function showChat(Chat $chat)
    {
        $user = Auth::user();

        if ($chat->buyer_id !== $user->id && $chat->seller_id !== $user->id) {
            abort(403); //購入者でも出品者でもない場合、403エラー
        }

        $messages = $chat->messages()->with('user')->get();

        return view('chat', compact('chat', 'messages'));
    }
}
