<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="{{asset('css/login.css')}}" />
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}" />
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="header-left">
                <a href="{{ route('index') }}">
                    <img src="{{asset('storage/logo.svg')}}" alt="COACHTECHロゴ" class="logo" />
                </a>
            </div>
        </header>
        <main>
            <div class="main">
                <h1 class="main-title">ログイン</h1>
                <form class="login-form" action="/login" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="label"> ユーザー名/メールアドレス </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="input"
                            placeholder="ユーザー名を入力" />
                    </div>
                    <div style="color:red">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="label">パスワード</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="input"
                            placeholder="パスワードを入力" />
                    </div>
                    <div style="color:red">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </div>

                    <button type="submit" class="button">ログインする</button>
                    <a href="/register" class="login-link">会員登録はこちら</a>
                </form>
            </div>
        </main>
    </div>
</body>

</html>