<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $fillable = [
        'type','connected_user_id','user_id', 'is_approved'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean'
    ];

    protected $hidden = [
        'connected_user_id', 'created_at', 'updated_at', 'user_id', 'is_approved'
    ];

    public function getStatusAttribute($value)
    {
        if($value == null){
            return "Not connected";
        }

        if($value){
            return "connected";
        }else{
            return "pending";
        }
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function connections() {
        return $this->belongsTo('App\User', 'connected_user_id')->select('id', 'name');
    }
    public function connectedUser(){
        return $this->belongsTo('App\User', 'connected_user_id')
            ->with('creative', 'manager', 'studio')
            ->withCount('connections')
            ->withCount('projects');
    }
    public function senderConnectedUser(){
        return $this->belongsTo('App\User', 'connected_user_id')
            ->with('creative', 'manager', 'studio')
            ->withCount('connections')
            ->withCount('projects');
    }
    public function receiverConnectedUser(){
        return $this->belongsTo('App\User', 'user_id')
            ->with('creative', 'manager', 'studio')
            ->withCount('connections')
            ->withCount('projects');
    }
}
