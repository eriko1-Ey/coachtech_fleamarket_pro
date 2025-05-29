<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Payment;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'post_code',
        'address',
        'building',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) { // NULL や空文字を防ぐ
            if (password_get_info($value)['algo'] === 0) { // すでにハッシュ化されていない場合のみ
                $this->attributes['password'] = bcrypt($value);
            } else {
                $this->attributes['password'] = $value;
            }
        }
    }

    //usersとproductsは1対多の関係
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    //usersとpurchasesは1対多の関係
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    //usersとlikesは1対多の関係
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    //usersとcommentsは1対多の関係
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //usersとpaymentsは1対多の関係
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'likes')->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class);
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function averageRating()
    {
        return $this->receivedReviews()->avg('rating');
    }
}
