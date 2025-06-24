<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\MessageRequest;

class MessageController extends Controller
{
    public function store(MessageRequest $request, Chat $chat)
    {
        $validated = $request->validated();

        $user = Auth::user();

        // メッセージ作成用データ
        $data = [
            'chat_id' => $chat->id, //どのチャットに属するメッセージなのか
            'user_id' => $user->id, //誰が送ったメッセージなのか
            'content' => $request->input('content'), //本文
        ];

        // 画像がアップロードされている場合
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('chat_images', 'public');
            $data['image_path'] = $path; // image_pathカラムに保存
        }

        // メッセージ保存
        $message = Message::create($data);

        // リレーションの読み込み
        $message->load('user');

        // 通知メール送信（Mailhog / Mailtrap）_メッセージ毎に通知メールを送信しないため、コメントアウトとする。
        //$receiver = $chat->buyer_id === $user->id ? $chat->seller : $chat->buyer; //メッセージを送る相手（受信者）を判断
        //Mail::to($receiver->email)->send(new \App\Mail\NewChatMessage($message));

        return redirect()->route('showChat', $chat->id);
    }


    //メッセージの編集
    public function update(Request $request, Message $message)
    {
        $this->authorize('update', $message); // ユーザー本人のみ許可したい場合

        $message->update([
            'content' => $request->input('content'),
            'is_edited' => true,
        ]);

        return redirect()->route('showChat', $message->chat_id)->with('success', 'メッセージを更新しました。');
    }

    //メッセージの削除
    public function destroy(Request $request, Message $message)
    {
        $this->authorize('delete', $message);

        if ($message->image_path && \Storage::disk('public')->exists($message->image_path)) {
            \Storage::disk('public')->delete($message->image_path);
        }

        $message->delete();

        return redirect()->route('showChat', $message->chat_id)->with('success', 'メッセージを削除しました。');
    }
}
