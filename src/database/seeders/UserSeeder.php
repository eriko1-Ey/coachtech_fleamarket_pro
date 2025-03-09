<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 仮のユーザーを10人作成
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'ユーザー' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password123'),
                'post_code' => sprintf("%07d", rand(1000000, 9999999)),
                'address' => '東京都渋谷区' . $i . '-丁目',
                'building' => $i % 2 == 0 ? 'マンション' . $i : null,
                'profile_image' => 'profile_images/user' . $i . '.jpg',
            ]);
        }
    }
}
