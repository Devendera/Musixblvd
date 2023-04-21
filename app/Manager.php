<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{

    protected $hidden = [
        'id', 'created_at', 'updated_at', 'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function creatives(){
        return $this->hasMany('App\Connection', 'user_id')
            ->where([
                ['type', 'management'],
                ['is_approved', true]
            ]);
    }
    public function clients(){
        return $this->hasMany('App\Connection', 'user_id', 'user_id')
            ->where([
                ['type', 'management'],
                ['is_approved', true]
            ]);
    }
}
