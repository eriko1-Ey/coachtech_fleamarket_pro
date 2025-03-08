<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller

{
    //会員情報（プロフィール入力）画面を表示
    public function getProfile()
    {
        $user = Auth::user(); // ログイン中のユーザー情報を取得
        return view('profile', compact('user'));
    }

    //会員情報（プロフィール）を登録/修正
    public function postProfile(ProfileRequest $request)
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return redirect()->route('getProfile')->with('error', 'ユーザーが見つかりませんでした。');
        }

        $validated = $request->validated(); // バリデーション済みのデータを取得

        // 画像アップロード処理
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
                Storage::delete('public/' . $user->profile_image);
            }
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        } else {
            $imagePath = $user->profile_image;
        }

        // ユーザー情報を更新
        $user->update([
            'name' => $validated['name'],
            'post_code' => $validated['post_code'],
            'address' => $validated['address'],
            'building' => $validated['building'],
            'profile_image' => $imagePath,
        ]);

        return redirect()->route('index')->with('success', 'プロフィールを更新しました！');
    }

    public function editAddress(Request $request)
    {
        $user = Auth::user(); // ✅ ログインユーザーの情報を取得
        $product_id = $request->query('product_id'); // ✅ `GET` で `product_id` を取得

        return view('edit_address', compact('user', 'product_id')); // ✅ ビューに `product_id` を渡す
    }

    public function updateAddress(Request $request)
    {
        $user = User::find(Auth::id());

        // ✅ バリデーションを追加
        $request->validate([
            'post_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        // ✅ 住所情報を更新
        $user->update([
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        // ✅ `product_id` を取得し、リダイレクト時に渡す
        return redirect()->route('showPurchase', ['product' => $request->product_id])->with('success', '住所を更新しました！');
    }
}
