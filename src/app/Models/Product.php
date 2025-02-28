<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'is_sold',
        'condition',
        'brand',
    ];

    //productsとusersは多対1の関係
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //productsとproductImagesは1対多の関係
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    //productsとcategoriesは多対多の関係（中間テーブル使用）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    //productsとcommentsは1対多の関係
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //productsとpurchasesは1対1の関係
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    //productsとlikesは1対多の関係
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
