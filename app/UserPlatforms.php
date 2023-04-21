<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPlatforms extends Model
{
    protected $table = 'user_platforms';

    protected $fillable = ['user_id', 'platform_id', 'url'];

}
