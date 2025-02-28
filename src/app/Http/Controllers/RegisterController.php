<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    //会員登録をしてプロフィール画面へ遷移
    public function postRegister(RegisterRequest $request)
    {
        $validated = $request->validated();

        // ユーザー作成（プロフィール情報は後で登録するので省略）
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 自動ログイン
        Auth::login($user);

        // プロフィール編集ページにリダイレクト
        return redirect()->route('getProfile');
    }
}
