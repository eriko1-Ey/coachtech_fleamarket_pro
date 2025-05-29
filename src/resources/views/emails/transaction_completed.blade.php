<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>取引完了のお知らせ</title>
</head>

<body>
    <p>{{ $chat->buyer->name }} さんとの取引が完了しました。</p>
    <p>商品名：{{ $chat->product->name }}</p>

</body>

</html>