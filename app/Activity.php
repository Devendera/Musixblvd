<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['sender_id', 'request_id', 
    'message',
    'message_es',
    'message_fr',
    'message_zh',
    'type',
    'user_id'
];

    protected $hidden = [
        'updated_at', 'sender_id', 'user_id', 'request_id', 'type'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'sender_id');
    }

    public function getCreatedAtAttribute($value){

        $MINUTES = 60;
        $HOURS = 60 * $MINUTES;
        $DAYS = 24 * $HOURS;
        $MONTHS = 30 * $DAYS;
        $YEARS = 12 * $MONTHS;
        $diffSeconds = Carbon::parse($value)->diffInSeconds(Carbon::now());

        if ($diffSeconds < $MINUTES) {
            return "Just now";
        } else if ($diffSeconds < 2 * $MINUTES) {
            return "1 minute ago";
        } else if ($diffSeconds < 60 * $MINUTES) {
            return floor($diffSeconds / $MINUTES)  . " " . "minutes ago";
        } else if ($diffSeconds < 120 * $MINUTES) {
            return "1 hour ago";
        } else if ($diffSeconds < 24 * $HOURS) {
            return floor($diffSeconds / $HOURS) . " " . "hours ago";
        } else if ($diffSeconds < 48 * $HOURS) {
            return "yesterday";
        } else if($diffSeconds < 30 * $DAYS){
            return floor($diffSeconds / $DAYS) . " " . "days ago";
        } else if ($diffSeconds < 2 * $MONTHS) {
            return "1 month ago";
        } else if ($diffSeconds < 12 * $MONTHS) {
            return floor($diffSeconds / $MONTHS) . " " . "months ago";
        } else if ($diffSeconds < 2 * $YEARS) {
            return "1 year ago";
        } else {
            return floor($diffSeconds / $YEARS) . " " . "years ago";
        }

    }

    public function getStatusAttribute($value){

        if($value)
            return "Accepted";
        else
            return "Pending";

    }

}
