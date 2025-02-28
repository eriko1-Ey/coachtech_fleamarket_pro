<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SellRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;

class ProductController extends Controller
{
    //商品一覧表示（ログイン済/未ログインユーザの両方に表示）
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $showLiked = $request->query('liked', false);
        $search = trim(preg_replace('/\s+/', ' ', $request->query('search', ''))); // 🔹 スペースを削除・統一

        // 🔹 キーワードを分割
        $keywords = array_filter(explode(' ', $search));

        if ($showLiked) {
            if (!$user) {
                return redirect()->route('login')->with('error', 'ログインが必要です'); // 🔹 未ログインならログインページへ
            }
            $products = $user->likedProducts()->with('images')->latest()->get();
        } else {
            $products = Product::query()
                ->when(Auth::check(), function ($query) {
                    return $query->where('user_id', '!=', Auth::id());
                })
                ->with('images');

            // 🔹 複数キーワードでAND検索
            if (!empty($keywords)) {
                $products->where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    }
                });
            }

            $products = $products->latest()->get();
        }

        return view('exhibition', compact('products', 'showLiked', 'search'));
    }


    //出品画面へ遷移
    public function getSell()
    {
        $categories = Category::all(); // データベースからすべてのカテゴリーを取得
        return view('sell', compact('categories')); // Bladeに渡す
    }

    //出品商品の登録
    public function postSell(SellRequest $request) // 修正: フォームリクエストを使用
    {

        // 商品を保存
        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_sold' => false,
            'condition' => $request->condition,
            'brand' => $request->brand,
        ]);

        // 商品画像を保存
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('product_images', 'public'); // `public` に保存
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path, // ✅ "storage/" を追加しない
                ]);
            }
        }

        // ✅ カテゴリーの関連付けを修正（json_decode() を使用）
        if ($request->has('categories')) {
            $categories = json_decode($request->categories, true); // ✅ JSON を配列に変換
            if (is_array($categories)) {
                $product->categories()->attach($categories);
            }
        }

        return redirect()->route('getMypage')->with('success', '商品が出品されました！');
    }

    //商品詳細画面を表示
    public function showDetail(Product $product)
    {
        // 画像・カテゴリー・コメントをロード
        $product->load(['images', 'categories', 'comments.user']);

        return view('detail', compact('product'));
    }

    public function getLikedProducts()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインが必要です');
        }
    }
}
