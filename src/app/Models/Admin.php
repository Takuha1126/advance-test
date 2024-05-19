<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminVerifyEmail;


class Admin extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'admin_name',
        'email',
        'password',
        'role',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new AdminVerifyEmail);
    }

}