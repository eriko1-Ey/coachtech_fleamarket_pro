<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function getMypage()
    {
        $user = User::find(Auth::id());  // ログインユーザー情報を取得
        $products = $user->products()->with('images')->latest()->get(); // ユーザーの商品を取得
        $purchasedProducts = $user->purchases()->with('product.images')->latest()->get(); // ✅ 購入した商品を取得

        return view('mypage', compact('user', 'products', 'purchasedProducts'));
    }
}
