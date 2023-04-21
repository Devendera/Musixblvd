<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    protected $table = 'advance';

    protected $fillable = 
    [
    'type', 'user_id', 'is_decisionmaker',
    'is_musicprimary',
    'is_entertainmentprimary',
    'amount_period',
    'period'

];
}
