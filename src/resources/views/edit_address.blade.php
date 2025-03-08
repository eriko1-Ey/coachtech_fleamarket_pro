<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>住所の変更</title>
    <link rel="stylesheet" href="{{ asset('css/edit_address.css') }}?v={{ time() }}" />
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}" />
</head>

<body>
    <div class="container">
        <header class="header">
            <a href="{{ route('index') }}">
                <img src="{{asset('storage/logo.svg')}}" alt="COACHTECHロゴ" class="logo" />
            </a>
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
                <form class="address-form" action="{{ route('updateAddress') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product_id }}">

                    <div class="form-group">
                        <label for="post_code" class="label">郵便番号</label>
                        <input type="text" id="post_code" name="post_code" class="input"
                            value="{{ old('post_code', $user->post_code) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="address" class="label">住所</label>
                        <input type="text" id="address" name="address" class="input"
                            value="{{ old('address', $user->address) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="building" class="label">建物名</label>
                        <input type="text" id="building" name="building" class="input"
                            value="{{ old('building', $user->building) }}">
                    </div>

                    <button type="submit" class="button">更新する</button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>