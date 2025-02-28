<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    //productsとcategoriesは多対多の関係（中間デーブル使用）
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category');
    }
}
