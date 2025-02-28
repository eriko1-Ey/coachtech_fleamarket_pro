<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>プロフィール設定</title>
    <link rel="stylesheet" href="{{asset('css/profile.css')}}" />
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}" />
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
            <h1 class="main-title">プロフィール設定</h1>
            <form class="profile-form" action="{{ route('postProfile') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="avatar-section">
                    <div class="avatar">
                        <img id="avatar-preview"
                            src="{{ asset($user->profile_image ? 'storage/' . $user->profile_image : 'storage/default-avatar.png') }}"
                            alt="プロフィール画像"
                            width="150">
                    </div>
                    <button type="button" class="avatar-btn" id="select-image-btn">画像を選択する</button>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" hidden>
                </div>
                <div class="form-group">
                    <label for="name" class="label">ユーザー名</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="input"
                        placeholder="ユーザー名を入力" />
                </div>
                <div style="color:red">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>

                <div class="form-group">
                    <label for="post_code" class="label">郵便番号</label>
                    <input
                        type="text"
                        id="post_code"
                        name="post_code"
                        value="{{ old('post_code', $user->post_code) }}"
                        class="input"
                        placeholder="郵便番号を入力" />
                </div>
                <div style="color:red">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="label">住所</label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        value="{{ old('address', $user->address) }}"
                        class="input"
                        placeholder="住所を入力" />
                </div>
                <div style="color:red">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>

                <div class="form-group">
                    <label for="building" class="label">建物名</label>
                    <input
                        type="text"
                        id="building"
                        name="building"
                        value="{{ old('building', $user->building) }}"
                        class="input"
                        placeholder="建物名を入力" />
                </div>
                <div style="color:red">
                    @error('building')
                    {{ $message }}
                    @enderror
                </div>

                <button type="submit" class="button">更新する</button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById("profile_image");
            const selectImageBtn = document.getElementById("select-image-btn");
            const avatarPreview = document.getElementById("avatar-preview");

            // ✅ ボタンをクリックするとファイル選択ダイアログを開く
            selectImageBtn.addEventListener("click", function() {
                fileInput.click();
            });

            // ✅ 画像を選択するとプレビューを更新
            fileInput.addEventListener("change", function() {
                if (fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            });
        });
    </script>
</body>

</html>