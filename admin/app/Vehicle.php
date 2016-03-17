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
    protected $fillable = ['title','type','sat_number'];
    
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
        return VehicleLocation::where('vehicle_id', $this->id)->orderBy('timestamp', 'desc')->first()->timestamp;
    }
    
    /**
     * Get last_tracked attribute
     *
     * @return obj
     */
    public function count_messages()
    {
        return 0;
    }
    
    
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['locations','last_tracked'];
}
