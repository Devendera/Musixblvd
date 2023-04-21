<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthenticationProvider extends Model
{
    protected $fillable = ['provider_key', 'provider_type', 'user_id'];
}
