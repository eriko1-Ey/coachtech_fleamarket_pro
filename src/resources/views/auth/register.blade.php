<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>会員登録</title>
    <link rel="stylesheet" href="{{asset('css/register.css')}}" />
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}" />
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="header-left">
                <img src="{{asset('storage/logo.svg')}}" alt="COACHTECHロゴ" class="logo" />
            </div>
        </header>
        <main>
            <div class="main">
                <h1 class="main-title">会員登録</h1>
                <form class="register-form" action="/register" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="label"> ユーザー名 </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="input"
                            placeholder="ユーザー名を入力" />
                    </div>
                    <div style="color:red">
                        @error('name')
                        {{ $message }}
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="label">メールアドレス</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="input"
                            placeholder="メールアドレスを入力" />
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

                    <div class="form-group">
                        <label for="confirm-password" class="label">確認用パスワード</label>
                        <input
                            type="password"
                            id="confirm-password"
                            name="password_confirmation"
                            class="input"
                            placeholder="もう一度パスワードを入力" />
                    </div>
                    <div style="color:red">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </div>

                    <button type="submit" class="button">登録する</button>
                    <a href="/login" class="login-link">ログインはこちら</a>
                </form>
            </div>
        </main>
    </div>
</body>

</html>