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
        $user = User::find(Auth::id()); // ログイン中のユーザーを取得

        if (!$user) {
            return redirect()->route('getProfile')->with('error', 'ユーザーが見つかりませんでした。');
        }

        $validated = $request->validated(); // バリデーション済みのデータを取得

        // 画像アップロード処理
        if ($request->hasFile('profile_image')) {
            // 既存の画像がある場合は削除
            if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
                Storage::delete('public/' . $user->profile_image);
            }

            // ✅ 新しい画像を `storage/app/public/profile_images/` に保存
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        } else {
            $imagePath = $user->profile_image; // 既存の画像を保持
        }

        // ✅ ユーザー情報を更新
        $user->update([
            'name' => $validated['name'],
            'post_code' => $validated['post_code'],
            'address' => $validated['address'],
            'building' => $validated['building'],
            'profile_image' => $imagePath, //  DBに保存
        ]);

        return redirect()->route('index')->with('success', 'プロフィールを更新しました！');
    }

    public function updateAddress(Request $request)
    {
        $user = User::find(Auth::id());

        // ユーザーの住所情報を更新
        $user->update([
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('showPurchase', $request->product_id)->with('success', '住所を更新しました！');
    }
}
