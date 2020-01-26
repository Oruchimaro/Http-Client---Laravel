<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'service_id',
        'grant_type',
        'access_token',
        'refresh_token',
        'token_expires_at'
    ];

    protected $hidden = [
        'remember_token',
        'access_token',
        'refresh_token',
        'token_expires_at'

    ];


    protected $casts = [];
}
