<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
    ];

    //productImagesとproductsは多対1の関係
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
