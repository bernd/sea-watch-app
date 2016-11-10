<?php

namespace App;

use \Cache;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','type','sat_number','sog', 'key','marker_color','user_id','location_alarm','logo_url','location_alarm_mails'];
    
    protected $dates = ['created_at', 'updated_at'];
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
        
        return VehicleLocation::where('vehicle_id', $this->id)->where('timestamp','>',time()-86400)->orderBy('timestamp', 'desc')->limit(100)->get();
    }
    public function updated_at(){
        $timestamp = strtotime($this->updated_at);
        if(time() < $timestamp + 86400*3)
            return \Carbon\Carbon::createFromTimeStamp(strtotime($this->updated_at))->diffForHumans();
        else
            return $this->updated_at;
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
     * Get base64 string of logo entered in logo_url
     *
     * @return obj
     */
    public function getLogo64Attribute()
    {
        return Cache::get('logo_'.$this->id, null);
    }
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['locations','last_tracked','logo64'];
}
