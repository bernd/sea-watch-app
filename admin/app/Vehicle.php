<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','type','sat_number','key','marker_color','user_id'];
    
    //returns last tracked location of vessel
    public function last_location(){
        return VehicleLocation::where('vehicle_id', $this->id)->orderBy('timestamp', 'desc')->first();
    }
    
    /**
     * Get the Locations
     *
     * @return obj
     */
    public function getLocationsAttribute()
    {
        return VehicleLocation::where('vehicle_id', $this->id)->orderBy('timestamp', 'desc')->get();
    }
    
    /**
     * Get last_tracked attribute
     *
     * @return obj
     */
    public function getLastTrackedAttribute()
    {
        $firstObj = VehicleLocation::where('vehicle_id', $this->id)->orderBy('timestamp', 'desc');
       
        
        try {
                $firstObj->first();
            //Do stuff when user exists.
        } catch (ErrorException $e) {
            //Do stuff if it doesn't exist.
            return array();
        }
    }
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['locations','last_tracked'];
}
