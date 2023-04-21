<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class platform extends Model
{
    protected $fillable = ['title', 'logo'];

    protected $hidden = [
        'updated_at', 'pivot'
    ];

    public function getLogoAttribute($value){
        if($value)
            return URL::to('img/platforms/' . $value);

        return $value;
    }
}
