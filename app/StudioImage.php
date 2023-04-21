<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class StudioImage extends Model
{
    protected $fillable = ['image'];

    protected $hidden = [
        'id', 'created_at', 'updated_at', 'studio_id'
    ];

    public function getImageAttribute($value){
        return URL::to('img/users/' . $value);
    }

}
