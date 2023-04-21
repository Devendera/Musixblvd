<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConnectionProjectMapping extends Model
{
    protected $table = 'connection_project_mapping';

    protected $fillable = ['user_id', 'contributor_id', 'project_id','connection_id'];
}
