<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>購入画面 | COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}?v={{ time() }}" />
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}" />
</head>

<body>
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
            <div class="purchase-container">
                <div class="left-section">
                    <form action="{{ route('completePurchase', $product->id) }}" method="POST">
                        @csrf
                        <div class="product-detail">
                            <div class="product-image">
                                <img src="{{ Storage::url($product->images->first()->image_path) }}" alt="商品画像" width="300">
                            </div>
                            <div class="product-info">
                                <h1 class="product-name">{{ $product->name }}</h1>
                                <p class="product-price">¥{{ number_format($product->price) }}</p>
                            </div>
                        </div>
                        <hr />
                        <section class="payment-method">
                            <h2>支払い方法</h2>
                            <select name="payment_method" required>
                                <option value="" disabled selected>選択してください</option>
                                <option value="credit-card">カード払い</option>
                                <option value="convenience-store">コンビニ払い</option>
                            </select>
                        </section>
                        <hr />
                        <section class="delivery-address">
                            <h2>配送先</h2>
                            <p>〒{{ $user->post_code }}<br>{{ $user->address }} {{ $user->building }}</p>
                            <a href="{{ route('editAddress', ['product_id' => $product->id]) }}" class="change-link">変更する</a>
                        </section>
                </div>
                <div class="right-section">
                    <div class="summary-box">
                        <div class="summary-item">
                            <p>商品代金</p>
                            <p>¥{{ number_format($product->price) }}</p>
                        </div>
                        <div class="summary-item">
                            <p>支払い方法</p>
                            <p id="selected-payment-method">未選択</p>
                        </div>
                    </div>
                    <button type="submit" class="purchase-btn">購入する</button>
                    </form>

                    <script>
                        document.querySelector('select[name="payment_method"]').addEventListener('change', function() {
                            document.getElementById('selected-payment-method').innerText = this.options[this.selectedIndex].text;
                        });
                    </script>
                </div>
            </div>
        </div>
    </main>
</body>

</html>