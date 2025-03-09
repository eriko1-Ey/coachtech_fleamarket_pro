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
        $user = User::find(Auth::id());
        $products = $user->products()->with('images')->latest()->get();
        $purchasedProducts = $user->purchases()->with('product.images')->latest()->get();

        return view('mypage', compact('user', 'products', 'purchasedProducts'));
    }
}
