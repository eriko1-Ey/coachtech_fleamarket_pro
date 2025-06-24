<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{
    public function store(Request $request, Chat $chat)
    {
        $user = Auth::user();

        // すでに評価済みならリダイレクト
        $exists = Review::where('chat_id', $chat->id)->where('reviewer_id', $user->id)->exists();
        if ($exists) {
            return back()->with('error', 'すでに評価済みです。');
        }


        // 相手ユーザーの取得
        $revieweeId = ($chat->buyer_id === $user->id) ? $chat->seller_id : $chat->buyer_id; //レビュー対象者の判断

        Review::create([
            'chat_id' => $chat->id,
            'reviewer_id' => $user->id,
            'reviewee_id' => $revieweeId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);


        // 取引完了フラグ更新（両者が評価したら完了）
        $bothReviewed = Review::where('chat_id', $chat->id)->count() >= 2;
        if ($bothReviewed) {
            $chat->update(['is_finished' => true]);
        }

        // メール通知（相手に）
        $reviewee = User::find($revieweeId);
        Mail::send('emails.review_notification', ['user' => $user], function ($message) use ($reviewee) {
            $message->to($reviewee->email)
                ->subject('取引評価のお知らせ');
        });

        return back()->with('success', '評価を送信しました。');
    }
}
