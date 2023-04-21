<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states_web';
    protected $fillable = [
        'code',
        'name'
    ];
    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
