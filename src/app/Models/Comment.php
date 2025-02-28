<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'content',
    ];

    //commentsとusersは多対1の関係
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //commentsとproductsは多対1の関係
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
