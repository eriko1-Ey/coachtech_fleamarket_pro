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
        $users = User::take(2)->get();

        $products = [
            ['name' => '腕時計', 'description' => 'スタイリッシュな...', 'price' => 15000, 'condition' => '良好', 'brand' => 'Armani', 'image' => 'Armani+Mens+Clock.jpg', 'category' => 'メンズ'],
            ['name' => 'HDD', 'description' => '高速なHDD...', 'price' => 5000, 'condition' => '目立った傷や汚れなし', 'brand' => '', 'image' => 'HDD+Hard+Disk.jpg', 'category' => '家電'],
            ['name' => '玉ねぎ3束', 'description' => '新鮮な玉ねぎ...', 'price' => 300, 'condition' => 'やや傷や汚れあり', 'brand' => '', 'image' => 'iLoveIMG+d.jpg', 'category' => 'キッチン'],
            ['name' => '革靴', 'description' => 'クラシックな革靴...', 'price' => 4000, 'condition' => '状態が悪い', 'brand' => '', 'image' => 'Leather+Shoes+Product+Photo.jpg', 'category' => 'メンズ'],
            ['name' => 'ノートPC', 'description' => '高性能なノートPC...', 'price' => 45000, 'condition' => '良好', 'brand' => '', 'image' => 'Living+Room+Laptop.jpg', 'category' => '家電'],
            ['name' => 'マイク', 'description' => '高音質マイク...', 'price' => 8000, 'condition' => '目立った傷や汚れなし', 'brand' => '', 'image' => 'Music+Mic+4632231.jpg', 'category' => '家電'],
            ['name' => 'ショルダーバッグ', 'description' => 'おしゃれバッグ...', 'price' => 3500, 'condition' => 'やや傷や汚れあり', 'brand' => '', 'image' => 'Purse+fashion+pocket.jpg', 'category' => 'ファッション'],
            ['name' => 'タンブラー', 'description' => '使いやすいタンブラー', 'price' => 500, 'condition' => '状態が悪い', 'brand' => '', 'image' => 'Tumbler+souvenir.jpg', 'category' => 'キッチン'],
            ['name' => 'コーヒーミル', 'description' => '手動ミル...', 'price' => 4000, 'condition' => '良好', 'brand' => '', 'image' => 'Waitress+with+Coffee+Grinder.jpg', 'category' => 'キッチン'],
            ['name' => 'メイクセット', 'description' => '便利なメイクアップ', 'price' => 2500, 'condition' => '目立った傷や汚れなし', 'brand' => '', 'image' => 'makeupset.jpg', 'category' => 'コスメ'],
        ];

        foreach ($products as $index => $productData) {
            $user = $users[$index < 5 ? 0 : 1];

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

            $category = Category::where('name', $productData['category'])->first();
            if ($category) {
                $product->categories()->attach($category->id);
            }
        }
    }
}
