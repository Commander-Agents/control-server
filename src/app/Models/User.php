<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

use App\Models\Base\User as BaseUser;

class User extends BaseUser
{
    use HasFactory, Notifiable;
    
}
