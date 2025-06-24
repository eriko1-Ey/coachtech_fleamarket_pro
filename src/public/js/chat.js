// モーダルを開く
function openModal(actionUrl) {
    const modal = document.getElementById("reviewModal");
    const form = document.getElementById("reviewForm");
    if (form && modal) {
        form.action = actionUrl;
        modal.style.display = "flex"; // "block" でも OK（CSS に合わせて）
    }
}

// モーダルを閉じる（背景クリック）
window.addEventListener("click", function (e) {
    if (e.target.classList.contains("modal")) {
        e.target.style.display = "none";
    }
});

// 編集モーダルを開く
function openEditModal(element) {
    const messageId = element.dataset.id;
    const content = element.dataset.content;
    const form = document.getElementById("editForm");

    form.action = `/message/${messageId}/update`;
    document.getElementById("editMessageContent").value = content;
    document.getElementById("editModal").style.display = "flex";
}

// 削除モーダルを開く
function openDeleteModal(element) {
    const messageId = element.dataset.id;
    const form = document.getElementById("deleteForm");

    form.action = `/message/${messageId}/delete`;
    document.getElementById("deleteModal").style.display = "flex";
}

// DOM読み込み後にすべての初期化処理を実行
document.addEventListener("DOMContentLoaded", function () {
    // 画像プレビュー処理
    const imageInput = document.getElementById("imageInput");
    const imagePreview = document.getElementById("imagePreview");

    if (imageInput && imagePreview) {
        imageInput.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = "";
                imagePreview.style.display = "none";
            }
        });
    }

    // チャット入力の保持処理
    const input = document.getElementById("chatInput");
    if (input) {
        const chatId = input.dataset.chatId;
        const storageKey = `chat_input_${chatId}`;

        // 入力値の復元
        const saved = localStorage.getItem(storageKey);
        if (saved) {
            input.value = saved;
        }

        // 入力変更時に保存
        input.addEventListener("input", () => {
            localStorage.setItem(storageKey, input.value);
        });

        // 送信時にlocalStorageから削除
        const form = input.closest("form");
        if (form) {
            form.addEventListener("submit", () => {
                localStorage.removeItem(storageKey);
            });
        }
    }
});
