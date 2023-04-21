<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $fillable = ['name', 'address', 'booking_email',
                            'hourly_rate', 'latitude', 'longitude', 'type'];

    protected $hidden = [
        'id', 'created_at', 'updated_at', 'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function images(){
        return $this->hasMany('App\StudioImage');
    }

    public function getTypeAttribute($value){
        if($value == "residential")
            return "Residential";
        else if($value == "commercial")
            return "Commercial";

        return $value;
    }
    public function clients(){
        return $this->hasMany('App\Connection', 'user_id', 'user_id')
            ->where([
                ['type', 'management'],
                ['is_approved', true]
            ]);
    }
}
