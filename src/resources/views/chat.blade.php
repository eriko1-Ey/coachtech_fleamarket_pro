<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>取引チャット</title>
    <link rel="stylesheet" href="{{asset('css/chat.css')}}?v={{ time() }}" />
    <link rel="stylesheet" href="{{asset('css/sanitize.css')}}" />
    <script src="{{ asset('js/chat.js') }}?v={{ time() }}"></script>
</head>

<body>
    <div class="container">
        <!-- ヘッダー -->
        <header class="header">
            <a href="{{ route('index') }}">
                <img src="{{asset('storage/logo.svg')}}" alt="COACHTECHロゴ" class="logo" />
            </a>
        </header>

        <div class="main">
            <!-- サイドバー -->
            <aside class="sidebar">
                <h2>その他の取引</h2>
                <ul class="chat-list">
                    @foreach ($activeChats as $chatItem)
                    <li>
                        <a href="{{ route('showChat', $chatItem->id) }}">
                            <button>{{ $chatItem->product->name }}</button>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </aside>

            <!-- チャットコンテンツ -->
            <section class="chat-content">
                <div class="chat-header">
                    <div class="chat-title">
                        「{{ $chat->seller_id === Auth::id() ? $chat->buyer->name : $chat->seller->name }}」さんとの取引画面
                    </div>
                    @php
                    use App\Models\Review;
                    $user = Auth::user();
                    $isSeller = $chat->seller_id === $user->id;
                    $buyerHasReviewed = Review::where('chat_id', $chat->id)
                    ->where('reviewer_id', $chat->buyer_id)
                    ->exists();
                    $sellerHasReviewed = Review::where('chat_id', $chat->id)
                    ->where('reviewer_id', $chat->seller_id)
                    ->exists();
                    @endphp

                    <!-- 購入者の評価送信ボタン -->
                    @if (Auth::id() === $chat->buyer_id && !$chat->is_finished)
                    <form action="{{ route('completeTransaction', $chat->id) }}" method="POST">
                        @csrf
                        <button type="button" class="complete-button"
                            onclick="openModal('{{ route('completeTransaction', $chat->id) }}')">取引を完了する</button>
                    </form>
                    @endif

                    <!-- 出品者の評価送信ボタン-->
                    @if ($isSeller && $buyerHasReviewed && !$sellerHasReviewed)
                    <button type="button" class="complete-button"
                        onclick="openModal('{{ route('submitReview', $chat->id) }}')">評価を送る</button>
                    @endif



                </div>


                <!-- モーダル -->
                <div id="reviewModal" class="modal" style="display: none;">
                    <form method="POST" id="reviewForm">
                        @csrf
                        <div class="modal-content">
                            <h3>取引が完了しました</h3>
                            <p>今回の取引相手はどうでしたか？</p>

                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>

                            <button type="submit" class="submit-review">送信</button>
                        </div>
                    </form>
                </div>

                <!-- 商品情報 -->
                <div class="product-info">
                    <div class="product-image">
                        @if ($chat->product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $chat->product->images->first()->image_path) }}"
                            alt="商品画像"
                            width="100"
                            @if ($isSeller && $buyerHasReviewed && !$sellerHasReviewed)
                            onclick="openModal('{{ route('submitReview', $chat->id) }}')"
                            style="cursor: pointer;"
                            @endif>
                        @endif
                    </div>
                    <div class="product-details">
                        <h3>{{ $chat->product->name }}</h3>
                        <p>{{ number_format($chat->product->price) }} 円</p>
                    </div>
                </div>

                <!-- チャットメッセージ一覧 -->
                @foreach ($messages as $message)
                <div class="message-wrapper {{ $message->user_id === Auth::id() ? 'self' : 'other' }}">
                    <div class="{{ $message->user_id === Auth::id() ? 'message-self' : 'message-other' }}">
                        <div class="message-meta">
                            <img src="{{ asset($message->user->profile_image ? 'storage/' . $message->user->profile_image : 'storage/default-avatar.png') }}" class="profile-img">
                            <span class="user-name">{{ $message->user->name }}</span>
                        </div>
                        <div class="bubble">
                            @if ($message->content)
                            <p class="message-content">{{ $message->content }}</p>
                            @endif

                            @if ($message->image_path)
                            <div class="chat-image">
                                <img src="{{ asset('storage/' . $message->image_path) }}" alt="画像" />
                            </div>
                            @endif

                            <small>{{ $message->created_at->format('H:i') }}</small>
                        </div>

                        @if ($message->user_id === Auth::id())
                        <div class="actions">
                            <span class="edit-link"
                                data-id="{{ $message->id }}"
                                data-content="{{ $message->content }}"
                                onclick="openEditModal(this)">編集</span>
                            <span class="delete-link"
                                data-id="{{ $message->id }}"
                                onclick="openDeleteModal(this)">削除</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
        </div>

    </div>


    <!-- 編集モーダル -->
    <div id="editModal" class="modal" style="display: none;">
        <form method="POST" id="editForm" class="modal-content">
            @csrf
            @method('PUT')
            <textarea name="content" id="editMessageContent" rows="4"></textarea>
            <button type="submit">保存</button>
        </form>
    </div>

    <!-- 削除モーダル -->
    <div id="deleteModal" class="modal" style="display: none;">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <p>本当に削除しますか？</p>
            <button type="submit">はい</button>
        </form>
    </div>


    </section>
    </div>
    <!-- 入力フォーム -->
    <div class="input-area">
        <form action="{{ route('sendMessage', $chat->id) }}" method="POST" enctype="multipart/form-data" class="input-form">
            @csrf
            <input type="text" id="chatInput" name="content" placeholder="メッセージを記入してください" required class="message-input" data-chat-id="{{ $chat->id }}" />
            <div style="color:red">
                @error('content')
                {{ $message }}
                @enderror
            </div>
            <input type="file" name="image" class="file-input" />
            <div style="color:red">
                @error('image')
                {{ $message }}
                @enderror
            </div>
            <button type="submit" class="send-button">📩</button>
        </form>
    </div>
    </div>

    <!--
    <script>
        function openModal(url) {
            const modal = document.getElementById('reviewModal');
            const form = document.getElementById('reviewForm');
            form.action = url;
            modal.style.display = 'block';
        }


        window.addEventListener('click', function(event) {
            const modal = document.getElementById('reviewModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
-->
</body>

</html>