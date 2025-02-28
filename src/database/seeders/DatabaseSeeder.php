<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class, // ✅ カテゴリーを最初に作成
            UserSeeder::class, // ✅ ユーザーを作成
            ProductSeeder::class, // ✅ その後に商品を作成
        ]);
    }
}
