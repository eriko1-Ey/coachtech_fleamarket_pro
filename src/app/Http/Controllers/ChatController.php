<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
    public function show(Chat $chat)
    {
        $user = Auth::user();

        if ($chat->buyer_id !== $user->id && $chat->seller_id !== $user->id) {
            abort(403);
        }

        $messages = $chat->messages()->with('user')->orderBy('created_at')->get();

        // 自分以外が送った未読メッセージを既読にする
        $buyerChats = Chat::with(['product'])
            ->where('buyer_id', $user->id)
            ->where('is_finished', false);

        $sellerChats = Chat::with(['product'])
            ->where('seller_id', $user->id)
            ->where('is_finished', false);

        $activeChats = $buyerChats->union($sellerChats)->get()->sortByDesc('created_at');

        // メッセージを既読に
        $chat->messages()
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat', compact('chat', 'messages', 'activeChats'));
    }

    public function completeTransaction(Request $request, Chat $chat)
    {
        $user = Auth::user();

        if ($chat->buyer_id !== $user->id) {
            abort(403);
        }

        // 重複防止
        if ($chat->is_finished) {
            return back()->with('error', 'すでに取引は完了しています。');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'chat_id' => $chat->id,
            'reviewer_id' => $user->id,
            'reviewee_id' => $chat->seller_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $chat->update(['is_finished' => true]);

        // 通知メール送信
        Mail::to($chat->seller->email)->send(new \App\Mail\TransactionCompletedMail($chat));

        return redirect()->route('getMypage')->with('message', '取引を完了しました');
    }
}
