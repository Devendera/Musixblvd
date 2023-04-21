<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries_web';
    protected $fillable = [
        'code',
        'name',
    ];
    public function states()
    {
        return $this->hasMany('App\State');
    }
}
