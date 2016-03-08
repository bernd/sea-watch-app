<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleLocation extends Model
{
    
    
    protected $fillable = ['vessel_id','lat','lon', 'altitude', 'heading', 'connection_type','timestamp'];
  
}
