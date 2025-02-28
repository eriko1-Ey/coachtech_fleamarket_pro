<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>住所の変更</title>
    <link rel="stylesheet" href="edit_address.css" />
    <link rel="stylesheet" href="sanitize.css" />
</head>

<body>
    <header class="header">
        <img src="{{asset('storage/logo.svg')}}" alt="COACHTECHロゴ" class="logo" />
        <div class="header-search">
            <input type="text" placeholder="なにをお探しですか？" />
        </div>
        <div class="header-links">
            <form action="/logout" method="post">
                @csrf
                <button class="logout-btn">ログアウト</button>
            </form>
            <a href="/mypage" class="mypage-btn">マイページ</a>
            <a href="/sell" class="header-btn">出品</a>
        </div>
    </header>
    <main>
        <div class="main">
            <h1 class="main-title">住所の変更</h1>
            <form class="address-form">
                <div class="form-group">
                    <label for="postcode" class="label">郵便番号</label>
                    <input
                        type="text"
                        id="postcode"
                        class="input"
                        placeholder="郵便番号を入力" />
                </div>
                <div class="form-group">
                    <label for="address" class="label">住所</label>
                    <input
                        type="text"
                        id="address"
                        class="input"
                        placeholder="住所を入力" />
                </div>
                <div class="form-group">
                    <label for="building" class="label">建物名</label>
                    <input
                        type="text"
                        id="building"
                        class="input"
                        placeholder="建物名を入力" />
                </div>
                <button type="submit" class="button">更新する</button>
            </form>
        </div>
    </main>
</body>

</html>