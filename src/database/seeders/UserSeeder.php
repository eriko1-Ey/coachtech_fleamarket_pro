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
        User::create([
            'name' => 'ユーザー1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
            'post_code' => sprintf("%07d", rand(1000000, 9999999)),
            'address' => '東京都渋谷区1-丁目',
            'building' => null,
            'profile_image' => 'profile_images/IMG_0623.jpg',
        ]);

        User::create([
            'name' => 'ユーザー2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
            'post_code' => sprintf("%07d", rand(1000000, 9999999)),
            'address' => '東京都渋谷区2-丁目',
            'building' => 'マンション2',
            'profile_image' => 'profile_images/IMG_1735.jpg',
        ]);

        User::create([
            'name' => 'ユーザー3',
            'email' => 'user3@example.com',
            'password' => Hash::make('password123'),
            'post_code' => sprintf("%07d", rand(1000000, 9999999)),
            'address' => '東京都渋谷区3-丁目',
            'building' => null,
            'profile_image' => 'profile_images/IMG_1828.jpg',
        ]);
    }
}
