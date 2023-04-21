<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    protected $fillable = ['user_id', 'project_id', 'role'];
    
    public function getRoleAttribute($value){

        switch ($value){
            
            case "creator": return "Creator";
                
            case "artist": return "Artist";

            case "featured_artist": return "Featured Artist";

            case "composer": return "Composer";

            case "producer": return "Producer";

            case "engineer": return "Engineer";

            case "studio": return "Studio Production";

            case "contributor": return "Contributor";

            default: return $value;
                
        }

    }
}
