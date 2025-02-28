<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // ✅ 仮のユーザーを取得（10人）
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
            ],
            [
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => '目立った傷や汚れなし',
                'brand' => '',
                'image' => 'HDD+Hard+Disk.jpg',
            ],
            [
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'condition' => 'やや傷や汚れあり',
                'brand' => '',
                'image' => 'iLoveIMG+d.jpg',
            ],
            [
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'condition' => '状態が悪い',
                'brand' => '',
                'image' => 'Leather+Shoes+Product+Photo.jpg',
            ],
            [
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => '良好',
                'brand' => '',
                'image' => 'Living+Room+Laptop.jpg',
            ],
            [
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'condition' => '目立った傷や汚れなし',
                'brand' => '',
                'image' => 'Music+Mic+4632231.jpg',
            ],
            [
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 'やや傷や汚れあり',
                'brand' => '',
                'image' => 'Purse+fashion+pocket.jpg',
            ],
            [
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => '状態が悪い',
                'brand' => '',
                'image' => 'Tumbler+souvenir.jpg',
            ],
            [
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => '良好',
                'brand' => '',
                'image' => 'Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => '目立った傷や汚れなし',
                'brand' => '',
                'image' => 'makeupset.jpg',
            ],
        ];

        foreach ($products as $index => $productData) {
            // ✅ 仮のユーザーを順番に割り当てる
            $user = $users[$index % $users->count()];

            $product = Product::create([
                'user_id' => $user->id,
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'is_sold' => false,
                'condition' => $productData['condition'],
                'brand' => $productData['brand'],
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'product_images/' . $productData['image'],
            ]);
        }
    }
}
