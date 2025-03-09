<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        //仮のユーザーを取得（10人）
        $users = User::all();

        // ユーザーが存在しない場合は処理を終了
        if ($users->isEmpty()) {
            $this->command->info('ユーザーが存在しないため、シードをスキップします。');
            return;
        }

        // 商品データ
        $products = [
            [
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'condition' => '良好',
                'brand' => 'Armani',
                'image' => 'Armani+Mens+Clock.jpg',
                'category' => 'メンズ',
            ],
            [
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => '目立った傷や汚れなし',
                'brand' => '',
                'image' => 'HDD+Hard+Disk.jpg',
                'category' => '家電',
            ],
            [
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'condition' => 'やや傷や汚れあり',
                'brand' => '',
                'image' => 'iLoveIMG+d.jpg',
                'category' => 'キッチン',
            ],
            [
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'condition' => '状態が悪い',
                'brand' => '',
                'image' => 'Leather+Shoes+Product+Photo.jpg',
                'category' => 'メンズ',
            ],
            [
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => '良好',
                'brand' => '',
                'image' => 'Living+Room+Laptop.jpg',
                'category' => '家電',
            ],
            [
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'condition' => '目立った傷や汚れなし',
                'brand' => '',
                'image' => 'Music+Mic+4632231.jpg',
                'category' => '家電',
            ],
            [
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 'やや傷や汚れあり',
                'brand' => '',
                'image' => 'Purse+fashion+pocket.jpg',
                'category' => 'ファッション',
            ],
            [
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => '状態が悪い',
                'brand' => '',
                'image' => 'Tumbler+souvenir.jpg',
                'category' => 'キッチン',
            ],
            [
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => '良好',
                'brand' => '',
                'image' => 'Waitress+with+Coffee+Grinder.jpg',
                'category' => 'キッチン',
            ],
            [
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => '目立った傷や汚れなし',
                'brand' => '',
                'image' => 'makeupset.jpg',
                'category' => 'コスメ',
            ],
        ];

        foreach ($products as $index => $productData) {
            //仮のユーザーを順番に割り当てる
            $user = $users[$index % $users->count()];

            //商品を作成
            $product = Product::create([
                'user_id' => $user->id,
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'is_sold' => false,
                'condition' => $productData['condition'],
                'brand' => $productData['brand'],
            ]);

            //画像を保存
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'product_images/' . $productData['image'],
            ]);

            //カテゴリを関連付け
            $category = Category::where('name', $productData['category'])->first();
            if ($category) {
                $product->categories()->attach($category->id); // ✅ 商品とカテゴリを紐付け
            } else {
                $this->command->warn("カテゴリ '{$productData['category']}' が見つかりませんでした。");
            }
        }
    }
}
