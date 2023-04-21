<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectEditRequest extends Model
{
    protected $table = 'project_edit_requests';

    protected $fillable = ['project_id', 'user_id', 'contributor_id','is_approved'];
}

