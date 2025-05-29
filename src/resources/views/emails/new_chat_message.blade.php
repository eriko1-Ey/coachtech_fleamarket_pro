<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>新しいメッセージのお知らせ</title>
</head>

<body>
    <p>{{ $chatMessage->user->name }} さんから新しいメッセージがあります。</p>

    <p>内容：</p>
    <p>{{ $chatMessage->content }}</p>

    <p>チャット画面はこちら：</p>
    <a href="{{ url('/chat/' . $chatMessage->chat_id) }}">
        チャットを開く
    </a>

    <hr>
    <p>このメールはシステムから自動送信されています。</p>
</body>

</html>