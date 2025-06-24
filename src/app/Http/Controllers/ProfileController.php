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
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    //会員情報
    public function postProfile(ProfileRequest $request)
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return redirect()->route('getProfile');
        }

        $validated = $request->validated();

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

        return redirect()->route('index');
    }

    public function editAddress(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->query('product_id');

        return view('edit_address', compact('user', 'product_id'));
    }

    public function updateAddress(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'post_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        //住所情報を更新
        $user->update([
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('showPurchase', ['product' => $request->product_id])->with('success', '住所を更新しました！');
    }
}
