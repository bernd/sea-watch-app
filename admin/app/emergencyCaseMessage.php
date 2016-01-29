<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyCaseMessage extends Model
{
    protected $fillable = ['emergency_case_id','emergency_case_location_id','receiver_type','receiver_id','sender_type','sender_id','message'];
}
