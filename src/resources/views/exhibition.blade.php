<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{asset('css/exhibition.css')}}?v={{ time() }}" />
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}" />
</head>

<body>
    <header class="header">
        <a href="{{ route('index') }}">
            <img src="{{asset('storage/logo.svg')}}" alt="COACHTECHロゴ" class="logo" />
        </a>
        <div class="header-search">
            <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="なにをお探しですか？">
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const searchInput = document.getElementById("search-input");

                searchInput.addEventListener("input", function() {
                    const searchQuery = searchInput.value.trim();
                    const url = new URL(window.location.href);
                    if (searchQuery) {
                        url.searchParams.set("search", searchQuery);
                    } else {
                        url.searchParams.delete("search");
                    }
                    window.history.replaceState({}, "", url);

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const newDocument = parser.parseFromString(html, "text/html");
                            const newProductList = newDocument.querySelector(".product-list");
                            document.querySelector(".product-list").innerHTML = newProductList.innerHTML;
                        })
                        .catch(error => console.error("検索エラー:", error));
                });
            });
        </script>


        <div class="header-links">
            @auth
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="logout-btn">ログアウト</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="login-btn">ログイン</a>
            @endauth

            <a href="{{ auth()->check() ? route('getMypage') : route('login') }}" class="mypage-btn">マイページ</a>

            <a href="{{ auth()->check() ? route('getSell') : route('login') }}" class="header-btn">出品</a>
        </div>
    </header>


    <main>
        <div class="main-container">
            <nav class="tabs">
                <ul>
                    <li><a href="{{ route('index') }}" class="{{ request('liked') ? '' : 'active' }}">おすすめ</a></li>
                    <li><a href="{{ route('index', ['liked' => true]) }}" class="{{ request('liked') ? 'active' : '' }}">マイリスト</a></li>
                </ul>
            </nav>

            <!-- 商品リスト -->
            <div class="product-list">
                @if ($products->isEmpty())
                <p>検索結果が見つかりませんでした。</p>
                @else
                @foreach ($products as $product)
                <div class="product-item">
                    <a href="{{ route('showDetail', ['product' => $product->id]) }}">
                        <div class="product-image">
                            @if ($product->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="商品画像" width="220">
                            @else
                            <img src="{{ asset('storage/no-image.png') }}" alt="商品画像なし" width="220">
                            @endif
                        </div>
                    </a>
                    @if ($product->is_sold)
                    <div class="sold-label">SOLD</div>
                    @endif
                    <p class="product-name">{{ $product->name }}</p>
                    <p class="product-price">¥{{ number_format($product->price) }}</p>
                </div>
                @endforeach
                @endif
            </div>
    </main>
</body>

</html>