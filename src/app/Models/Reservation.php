<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'date',
        'reservation_time',
        'number_of_people',
        'status',
        'paid_at'
    ];


    public function isPaid()
    {
        return !is_null($this->paid_at);
    }

    public function shop()
    {
    return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
{
    return $this->hasOne(Payment::class);
}

}