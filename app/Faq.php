<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faq';
    protected $fillable = [
        'id',
        'question',
        'answer'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
