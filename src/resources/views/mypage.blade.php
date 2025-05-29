<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>マイページ</title>
    <link rel="stylesheet" href="{{asset('css/mypage.css')}}?v={{ time() }}" />
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
        <div class="main-container">
            <!-- ユーザー情報 -->
            <div class="profile">
                <div class="profile-image">
                    <img src="{{ asset($user->profile_image ? 'storage/' . $user->profile_image : 'storage/default-avatar.png') }}"
                        alt="プロフィール画像" width="100" height="100">
                </div>
                <div class="profile-details">
                    <p class="username">{{ $user->name }}</p>
                    <p class="rating">
                        @php
                        $average = round($user->averageRating());
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= $average ? '★' : '☆' }}</span>
                            @endfor
                    </p>
                    <a href="{{ route('getProfile') }}" class="edit-profile-btn">プロフィールを編集</a>
                    @php
                    $average = \App\Models\Review::where('reviewee_id', $user->id)->avg('rating');
                    @endphp


                </div>
            </div>

            <!-- タブメニュー -->
            <nav class="tabs">
                <ul>
                    <li><a href="#" class="tab-link active" data-target="sold-products">出品した商品</a></li>
                    <li><a href="#" class="tab-link" data-target="purchased-products">購入した商品</a></li>
                    <li>
                        <a href="#" class="tab-link" data-target="active-products">
                            取引中の商品
                            @if ($unreadMessagesByChat->flatten()->count() > 0)
                            <span class="badge">{{ $unreadMessagesByChat->flatten()->count() }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- 商品リスト -->
            <div id="sold-products" class="product-list">
                @if ($products->isEmpty())
                <p>まだ商品を出品していません。</p>
                @else
                @foreach ($products as $product)
                <div class="product-item">
                    <div class="product-image">
                        @if ($product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="商品画像" width="220">
                        @else
                        <img src="{{ asset('storage/no-image.png') }}" alt="商品画像なし" width="220">
                        @endif
                    </div>
                    @if ($product->is_sold)
                    <div class="sold-label">SOLD</div>
                    @endif
                    <p class="product-name">{{ $product->name }}</p>
                </div>
                @endforeach
                @endif
            </div>

            <!--購入した商品リスト -->
            <div id="purchased-products" class="product-list" style="display: none;">
                @if ($purchasedProducts->isEmpty())
                <p>まだ商品を購入していません。</p>
                @else
                @foreach ($purchasedProducts as $purchase)
                <div class="product-item">
                    <div class="product-image">
                        @if ($purchase->product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $purchase->product->images->first()->image_path) }}" alt="商品画像" width="220">
                        @else
                        <img src="{{ asset('storage/no-image.png') }}" alt="商品画像なし" width="220">
                        @endif
                    </div>
                    <div class="sold-label">SOLD</div> <!--画像の下に配置 -->
                    <p class="product-name">{{ $purchase->product->name }}</p>
                </div>
                @endforeach
                @endif
            </div>

            <!--取引中の商品リスト-->
            <div id="active-products" class="product-list" style="display: none;">

                @if ($activeChats->isEmpty())
                <p>取引中の商品はありません。</p>
                @else
                @foreach ($activeChats->sortByDesc('created_at') as $chat)
                @php
                $chatUnreadCount = isset($unreadMessagesByChat[$chat->id]) ? $unreadMessagesByChat[$chat->id]->count() : 0;
                @endphp
                <div class="product-item">
                    <a href="{{ route('showChat', $chat->id) }}">
                        <div class="product-image">
                            @if ($chatUnreadCount > 0)
                            <div class="chat-badge">{{ $chatUnreadCount }}</div>
                            @endif
                            <img src="{{ asset('storage/' . optional($chat->product->images->first())->image_path ?? 'no-image.png') }}" alt="商品画像" width="220">
                        </div>
                    </a>
                    <p class="product-name">{{ $chat->product->name }}</p>
                </div>
                @endforeach
                @endif
            </div>



            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const tabs = document.querySelectorAll(".tab-link");
                    const contents = {
                        "sold-products": document.getElementById("sold-products"),
                        "purchased-products": document.getElementById("purchased-products"),
                        "active-products": document.getElementById("active-products")
                    };

                    tabs.forEach(tab => {
                        tab.addEventListener("click", function(event) {
                            event.preventDefault();

                            // すべてのタブの active クラスを削除
                            tabs.forEach(t => t.classList.remove("active"));

                            // すべてのコンテンツを非表示
                            Object.values(contents).forEach(content => {
                                content.style.display = "none"; //非表示にする
                            });

                            // クリックされたタブを active にする
                            this.classList.add("active");

                            const target = this.getAttribute("data-target");
                            contents[target].style.display = "flex";
                        });
                    });
                });
            </script>
        </div>
    </main>
</body>

</html>