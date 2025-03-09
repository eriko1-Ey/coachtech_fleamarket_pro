<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>商品詳細</title>
    <link rel="stylesheet" href="{{asset('css/detail.css')}}?v={{ time() }}" />
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}" />
    <style>
        /* モーダルの基本スタイル */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        /* モーダルのコンテンツ */
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
        }

        /* モーダル内のボタン */
        .modal .login-btn,
        .modal .close-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px;
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal .close-btn {
            background-color: #ccc;
            color: black;
        }
    </style>
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
        <div class="main">
            <div class="product-detail">
                <div class="product-image-section">
                    @if ($product->images->isNotEmpty())
                    <img src="{{ Storage::url($product->images->first()->image_path) }}" alt="商品画像" width="300">
                    @else
                    <img src="{{ asset('storage/no-image.png') }}" alt="商品画像なし" width="300">
                    @endif

                    @if ($product->is_sold)
                    <div class="sold-label">SOLD</div>
                    @endif
                </div>

                <div class="product-info-section">
                    <h1 class="product-name">{{ $product->name }}</h1>
                    <p class="product-brand">ブランド: {{ $product->brand ?? '指定なし' }}</p>
                    <p class="product-price">¥{{ number_format($product->price) }} (税込)</p>
                    <div class="product-actions-icons">
                        <!-- いいねボタン -->
                        <div class="like-section">
                            @auth
                            <button class="like-button" data-product-id="{{ $product->id }}" onclick="toggleLike(this)">
                                <img src="{{ asset(auth()->user()->likedProducts->contains($product->id) ? 'storage/liked.png' : 'storage/like.png') }}"
                                    alt="いいねアイコン" class="action-icon" id="like-icon-{{ $product->id }}">
                            </button>
                            @else
                            <!-- 未ログインユーザーはログインモーダルを表示 -->
                            <button class="like-button" onclick="showLoginModal()">
                                <img src="{{ asset('storage/like.png') }}" alt="いいねアイコン" class="action-icon">
                            </button>
                            @endauth
                            <span id="like-count-{{ $product->id }}">{{ $product->likes()->count() }}</span>
                        </div>

                        <!-- コメントボタン -->
                        <div class="comment-section">
                            <button class="comment-button">
                                <img src="{{ asset('storage/comment.png') }}" alt="コメントアイコン" class="action-icon">
                            </button>
                            <span id="comment-count-btn">{{ $product->comments->count() }}</span>
                        </div>
                    </div>

                    <div id="loginModal" class="modal">
                        <div class="modal-content">
                            <p>ログインが必要です。</p>
                            <a href="{{ route('login') }}" class="login-btn">ログインする</a>
                            <button class="close-btn" onclick="closeLoginModal()">閉じる</button>
                        </div>
                    </div>

                    <script>
                        function showLoginModal() {
                            document.getElementById('loginModal').style.display = 'flex';
                        }

                        function closeLoginModal() {
                            document.getElementById('loginModal').style.display = 'none';
                        }

                        function toggleLike(button) {
                            const productId = button.getAttribute('data-product-id');
                            const likeIcon = document.getElementById(`like-icon-${productId}`);

                            fetch(`/product/${productId}/like`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById(`like-count-${productId}`).textContent = data.likeCount;

                                    // アイコンの変更処理
                                    if (data.liked) {
                                        likeIcon.src = "{{ asset('storage/liked.png') }}";
                                    } else {
                                        likeIcon.src = "{{ asset('storage/like.png') }}";
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    </script>

                    <script>
                        function toggleLike(button) {
                            const productId = button.getAttribute('data-product-id');

                            fetch(`/product/${productId}/like`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById(`like-count-${productId}`).textContent = data.likeCount;
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    </script>

                    <div class="product-actions">
                        @if ($product->is_sold)
                        <button class="buy-now-btn disabled" disabled>購入手続きへ</button>
                        @else
                        @auth
                        <a href="{{ route('showPurchase', $product->id) }}" class="buy-now-btn">購入手続きへ</a>
                        @else
                        <button class="buy-now-btn" onclick="showLoginModal()">購入手続きへ</button>
                        @endauth
                        @endif
                    </div>

                    <!-- ログインモーダル -->
                    <div id="loginModal" class="modal">
                        <div class="modal-content">
                            <p>ログインが必要です。</p>
                            <a href="{{ route('login') }}" class="login-btn">ログインする</a>
                            <button class="close-btn" onclick="closeLoginModal()">閉じる</button>
                        </div>
                    </div>

                    <script>
                        function showLoginModal() {
                            document.getElementById('loginModal').style.display = 'flex';
                        }

                        function closeLoginModal() {
                            document.getElementById('loginModal').style.display = 'none';
                        }
                    </script>

                    <section class="product-description">
                        <h2>商品説明</h2>
                        <p>{{ $product->description }}</p>
                    </section>
                    <section class="product-info">
                        <h2>商品の情報</h2>
                        <p>カテゴリ:
                            @foreach ($product->categories as $category)
                            {{ $category->name }}
                            @endforeach
                        </p>
                        <p>商品の状態: {{ $product->condition }}</p>
                    </section>

                    <section class="comments-section">
                        <h2>コメント</h2>
                        <div id="comments-list">
                            @foreach ($comments as $comment)
                            <div class="comment">
                                <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('storage/default-avatar.png') }}"
                                    alt="{{ $comment->user->name }}" class="comment-avatar">

                                <div class="comment-info">
                                    <span class="comment-user">{{ $comment->user->name }}</span>
                                    <p class="comment-text">{{ $comment->content }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if ($product->is_sold)
                        <button class="comment-submit-btn disabled" disabled>コメントを投稿する</button>
                        @else
                        @auth
                        <div class="comment-form">
                            <textarea id="comment-input" class="comment-input" placeholder="商品へのコメント"></textarea>
                            <button id="comment-submit-btn" data-product-id="{{ $product->id }}" class="comment-submit-btn">コメントを投稿する</button>
                        </div>
                        @else
                        <button class="comment-submit-btn" onclick="showLoginModal()">コメントを投稿する</button>
                        @endauth
                        @endif



                        <!-- ログインモーダル -->
                        <div id="loginModal" class="modal">
                            <div class="modal-content">
                                <p>コメントするにはログインが必要です。</p>
                                <a href="{{ route('login') }}" class="login-btn">ログインする</a>
                                <button class="close-btn" onclick="closeLoginModal()">閉じる</button>
                            </div>
                        </div>
                    </section>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const commentBtn = document.getElementById('comment-submit-btn');
                            if (commentBtn) {
                                commentBtn.addEventListener('click', function() {
                                    const content = document.getElementById('comment-input').value.trim();
                                    const productId = this.getAttribute('data-product-id');

                                    if (!content) {
                                        alert('コメントを入力してください');
                                        return;
                                    }

                                    fetch(`/product/${productId}/comment`, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                content
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.comment) {
                                                const commentList = document.getElementById('comments-list');

                                                const newComment = document.createElement('div');
                                                newComment.classList.add('comment');
                                                newComment.innerHTML = `
                        <p class="comment-text">${data.comment.content}</p>
                    `;
                                                commentList.prepend(newComment);

                                                const commentCount = document.getElementById('comment-count');
                                                const commentCountBtn = document.getElementById('comment-count-btn');
                                                const newCount = parseInt(commentCount.textContent) + 1;
                                                commentCount.textContent = newCount;
                                                commentCountBtn.textContent = newCount;

                                                document.getElementById('comment-input').value = '';
                                            }
                                        })
                                        .catch(error => alert('エラー: ' + error.message));
                                });
                            }
                        });
                    </script>

                </div>
            </div>
        </div>
    </main>
</body>

</html>