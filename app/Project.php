<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Project extends Model
{

    protected $hidden = [
        'updated_at', 'pivot'
    ];

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

    public function getImgAttribute($value){
        if($value){
            if($this->platform == "musixblvd")
                return URL::to('img/projects/' . $value);
        }

        return $value;
    }

    public function getAudioAttribute($value){
        if($value){
            return URL::to('audio/projects/' . $value);
        }

        return $value;
    }

    public function artists(){

        return $this->belongsToMany('App\User', 'user_projects')
            ->select('users.id', 'users.name', 'users.img', 'users.type','user_projects.role')
            ->where([
                ['user_projects.is_approved', true],
                ['user_projects.role', 'artist']
            ]);
    }

    public function featured_artists(){

        return $this->belongsToMany('App\User', 'user_projects')
            ->select('users.id', 'users.name', 'users.img', 'users.type','user_projects.role')
            ->where([
                ['user_projects.is_approved', true],
                ['user_projects.role', 'featured_artist']
            ]);
    }

    public function composers(){

        return $this->belongsToMany('App\User', 'user_projects')
            ->select('users.id', 'users.name', 'users.img', 'users.type','user_projects.role')
            ->where([
                ['user_projects.is_approved', true],
                ['user_projects.role', 'composer']
            ]);
    }

    public function producers(){

        return $this->belongsToMany('App\User', 'user_projects')
            ->select('users.id', 'users.name', 'users.img', 'users.type','user_projects.role')
            ->where([
                ['user_projects.is_approved', true],
                ['user_projects.role', 'producer']
            ]);
    }

    public function engineers(){

        return $this->belongsToMany('App\User', 'user_projects')
            ->select('users.id', 'users.name', 'users.img', 'users.type','user_projects.role')
            ->where([
                ['user_projects.is_approved', true],
                ['user_projects.role', 'engineer']
            ]);
    }

    public function studios(){

        return $this->belongsToMany('App\User', 'user_projects')
            ->select('users.id', 'users.name', 'users.img', 'users.type','user_projects.role')
            ->where([
                ['user_projects.is_approved', true],
                ['user_projects.role', 'studio']
            ]);
    }

    public function contributors(){

        return $this->belongsToMany('App\User', 'user_projects')
            ->select('users.id', 'users.name', 'users.img', 'users.type','user_projects.role')
            ->where([
                ['user_projects.is_approved', true],
                ['user_projects.role', 'contributor']
            ]);
    }

    public function allUsers(){

        return $this->belongsToMany(User::class, UserProject::class)
            ->select('users.id', 'users.name', 'users.img', 'users.type','user_projects.role')
            ->where('user_projects.role', '!=','creator');

    }

}
