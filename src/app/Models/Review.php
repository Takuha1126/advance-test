<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'shop_id', 'rating', 'comment','image_path'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
