<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function getMypage()
    {
        $user = User::find(Auth::id());
        $products = $user->products()->with('images')->latest()->get();
        $purchasedProducts = $user->purchases()->with('product.images')->latest()->get();

        // 購入者としてのチャット
        $buyerChats = Chat::with(['product.images'])
            ->where('buyer_id', $user->id)
            ->where('is_finished', false)
            ->get();

        // 出品者としてのチャット
        $sellerChats = Chat::with(['product.images'])
            ->where('seller_id', $user->id)
            ->where('is_finished', false)
            ->get();

        // 購入者・出品者の両方のチャットをマージ
        $activeChats = $buyerChats->merge($sellerChats);

        // 未読メッセージ
        $unreadMessagesByChat = Message::whereHas('chat', function ($q) use ($user) {
            $q->where('buyer_id', $user->id)->orWhere('seller_id', $user->id);
        })
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->get()
            ->groupBy('chat_id');

        return view('mypage', compact(
            'user',
            'products',
            'purchasedProducts',
            'activeChats',
            'unreadMessagesByChat'
        ));
    }

    // チャット表示画面
    public function showChat(Chat $chat)
    {
        $user = Auth::user();

        if ($chat->buyer_id !== $user->id && $chat->seller_id !== $user->id) {
            abort(403); // 不正アクセス防止
        }

        $messages = $chat->messages()->with('user')->get();

        return view('chat', compact('chat', 'messages'));
    }
}
