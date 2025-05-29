<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//商品一覧表示（ログイン済み/未ログインユーザーの両方に表示）
Route::get('/', [ProductController::class, 'index'])->name('index');
Route::get('/product/{product}', [ProductController::class, 'showDetail'])->name('showDetail');

//ログイン前
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'postRegister']);
});

//ログイン後
Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'getProfile'])->name('getProfile');
    Route::post('/mypage/profile', [ProfileController::class, 'postProfile'])->name('postProfile');
    Route::get('/sell', [ProductController::class, 'getSell'])->name('getSell');
    Route::post('/sell', [ProductController::class, 'postSell']);
    Route::get('/mypage', [MypageController::class, 'getMypage'])->name('getMypage');
    Route::get('/purchase/{product}', [PurchaseController::class, 'showPurchase'])->name('showPurchase');
    Route::post('/purchase/{product}/complete', [PurchaseController::class, 'completePurchase'])->name('completePurchase');
    Route::get('/mypage/edit-address', [ProfileController::class, 'editAddress'])->name('editAddress');
    Route::post('/mypage/update-address', [ProfileController::class, 'updateAddress'])->name('updateAddress');
    Route::post('/product/{product}/like', [LikeController::class, 'toggleLike'])->name('toggleLike');
    Route::post('/product/{product}/comment', [CommentController::class, 'store'])->name('addComment');
    Route::get('/mypage', [MypageController::class, 'getMypage'])->name('getMypage'); // マイページに「取引中の商品」タブ付き
    //Route::get('/chat/{chat}', [MypageController::class, 'showChat'])->name('showChat'); // チャット画面に遷移
    Route::get('/chat/{chat}', [ChatController::class, 'show'])->name('showChat');
    Route::post('/chat/{chat}/message', [MessageController::class, 'store'])->name('sendMessage');
    Route::put('/message/{message}/update', [MessageController::class, 'update'])->name('updateMessage');
    Route::delete('/message/{message}/delete', [MessageController::class, 'destroy'])->name('deleteMessage');
    Route::post('/chat/{chat}/complete', [ChatController::class, 'completeTransaction'])->name('completeTransaction');
    Route::post('/chat/{chat}/review', [ReviewController::class, 'store'])->name('submitReview');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
