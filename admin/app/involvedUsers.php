<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class involvedUsers extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['case_id','user_id','last_message_seen'];

   
    protected $dates = ['created_at', 'updated_at'];
    
}
