<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaProvider extends Model
{
    protected $fillable = [
        'provider_key', 'provider_type', 'img',
        'username', 'followers', 'songs','user_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
