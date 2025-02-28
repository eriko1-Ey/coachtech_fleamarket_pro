<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>商品の出品</title>
    <link rel="stylesheet" href="{{asset('css/sell.css')}}" />
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
        <div class="main-container">
            <h1>商品の出品</h1>
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form class="product-form" action="/sell" method="post" enctype="multipart/form-data">
                @csrf
                <!--画像の処理-->
                <div class="form-group">
                    <label for="product-image" class="form-label">商品画像</label>
                    <div class="image-upload">
                        <button type="button" class="upload-btn">画像を選択する</button>
                        <input type="file" name="product_images[]" id="product_images" multiple class="form-control" accept="image/*" style="display: none;">
                    </div>
                    <div id="selected-files" class="preview-container"></div>
                    @error('product_images.*')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const uploadBtn = document.querySelector(".upload-btn");
                        const fileInput = document.getElementById("product_images");
                        const selectedFiles = document.getElementById("selected-files");

                        // ボタンをクリックしたら file input を開く
                        uploadBtn.addEventListener("click", function() {
                            fileInput.click();
                        });

                        // ファイルが選択されたら、画像を表示
                        fileInput.addEventListener("change", function() {
                            selectedFiles.innerHTML = ""; // 一旦クリア

                            if (fileInput.files.length > 0) {
                                for (let i = 0; i < fileInput.files.length; i++) {
                                    const file = fileInput.files[i];

                                    // 画像ファイルのみ処理
                                    if (file.type.startsWith("image/")) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const img = document.createElement("img");
                                            img.src = e.target.result;
                                            selectedFiles.appendChild(img);
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                }
                            }
                        });
                    });
                </script>

                <!--カテゴリーの処理-->
                <div class="form-group">
                    <h2 class="form-section">商品の詳細</h2>
                    <div class="categories">
                        <label class="form-label">カテゴリー</label>
                        <div class="category-buttons">
                            @foreach ($categories as $category)
                            <button type="button" class="category-button" data-id="{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="categories" id="selected-categories">
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const categoryButtons = document.querySelectorAll(".category-button");
                        const selectedCategoriesInput = document.getElementById("selected-categories");
                        let selectedCategories = [];

                        categoryButtons.forEach(button => {
                            button.addEventListener("click", function() {
                                const categoryId = this.getAttribute("data-id");

                                if (selectedCategories.includes(categoryId)) {
                                    selectedCategories = selectedCategories.filter(id => id !== categoryId);
                                    this.classList.remove("active");
                                } else {
                                    selectedCategories.push(categoryId);
                                    this.classList.add("active");
                                }

                                selectedCategoriesInput.value = JSON.stringify(selectedCategories); // ✅ JSON 形式で送信
                            });
                        });
                    });
                </script>

                <!--商品状態の処理-->
                <div class="form-group">
                    <label for="product-condition" class="form-label">商品の状態</label>
                    <select name="condition" id="product-condition" class="form-select">
                        <option value="">選択してください</option>
                        <option value="良好">良好</option>
                        <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                        <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                        <option value="状態が悪い">状態が悪い</option>
                    </select>
                    @error('condition')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!--商品名と説明の処理-->
                <div class="form-group">
                    <h2 class="form-section">商品名と説明</h2>

                    <label for="name" class="form-label">商品名</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input"
                        placeholder="商品名" />
                    <div style="color:red">
                        @error('name')
                        {{ $message }}
                        @enderror
                    </div>

                    <label for="brand" class="form-label">ブランド名</label>
                    <input
                        type="text"
                        id="brand"
                        name="brand"
                        class="form-input"
                        placeholder="ブランド名" />
                    <div style="color:red">
                        @error('brand')
                        {{ $message }}
                        @enderror
                    </div>

                    <label for="description" class="form-label">商品の説明</label>
                    <textarea
                        id="description"
                        name="description"
                        class="form-textarea"
                        placeholder="商品の説明"></textarea>
                    <div style="color:red">
                        @error('description')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="price" class="form-label">商品価格</label>
                    <input type="text" id="price" name="price" class="form-input" placeholder="¥" />
                </div>
                <div style="color:red">
                    @error('price')
                    {{ $message }}
                    @enderror
                </div>


                <button type="submit" class="submit-btn">出品する</button>

            </form>
        </div>
    </main>
</body>

</html>