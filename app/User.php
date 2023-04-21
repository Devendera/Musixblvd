<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'password', 'state', 'city', 'country', 'type'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_at', 'updated_at', 'email_verified_at',
        'notification_token', 'pivot'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'has_profile' => 'boolean',
        'is_verified' => 'boolean'
    ];

    protected 
        $redirectTo = 'login',
        $guard = 'web';
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getImgAttribute($value){
        if($value)
            return URL::to('public/img/users/' . $value);

        return $value;
    }

    public function creative(){
        return $this->hasOne('App\Creative')->withCount('clients');
    }

    public function manager(){
        return $this->hasOne('App\Manager')->withCount('clients');
    }

    public function studio(){
        return $this->hasOne('App\Studio')->with('images')->withCount('clients');
    }

    public function connections(){
        return $this->hasMany('App\Connection')
            ->where([
                ['type', 'connection'],
                ['is_approved', true]
            ]);
    }

    public function projects(){
        return $this->belongsToMany('App\Project', 'user_projects')
                    ->where('user_projects.is_approved', true);
    }

    public function projectsGrouped(){
        return $this->projects()
            ->select('projects.id', 'projects.title', 'projects.img')
            ->groupBy('project_id')
            ->get();
    }

    public function platforms(){
        return $this->belongsToMany('App\Platform', 'user_platforms')
            ->select('platforms.id', 'platforms.title', 'platforms.logo', 'user_platforms.url');
    }
    public function getTypeAttribute($value){
        if($value == 1)
            return Config::get('constants.Creative');
        else if($value == 2)
            return Config::get('constants.Manager');
        else if($value == 3)
            return Config::get('constants.Studio');
        return $value;
    }
    public function managementConnection(){
        $user = Auth::guard($this->guard)->user();
        $conditions = array();
        if(isset($user) && !empty($user) && $user->id !=''){
            array_push($conditions, ['user_id', '=', $user->id]);
        }
        return $this->hasOne('App\Connection', 'connected_user_id', 'id')
            ->where($conditions);
    }
    public function getConnections(){
        $user = Auth::guard($this->guard)->user();
        $conditions = array();
        if(isset($user) && !empty($user) && $user->id !=''){
            array_push($conditions, ['user_id', '=', $user->id]);
        }
        return $this->hasOne('App\Connection', 'connected_user_id', 'id')
            ->where($conditions);
    }
    public function sender(){
        $user = Auth::guard($this->guard)->user();
        $conditions = array();
        if(isset($user) && !empty($user) && $user->id !=''){
            array_push($conditions, ['user_id', '=', $user->id]);
        }
        return $this->hasOne('App\Connection', 'connected_user_id', 'id')
            ->where($conditions);
    }
    public function receiver(){
        $user = Auth::guard($this->guard)->user();
        $conditions = array();
        if(isset($user) && !empty($user) && $user->id !=''){
            array_push($conditions, ['connected_user_id', '=', $user->id]);
        }
        return $this->hasOne('App\Connection', 'user_id', 'id')
            ->where($conditions);
    }
}
