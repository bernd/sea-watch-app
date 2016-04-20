<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use App\User;
use App\emergencyCaseLocation;
use App\emergencyCaseMessage;
use App\involvedUsers;
use DB;





class emergencyCase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['boat_status','boat_condition','boat_type','other_involved','engine_working','passenger_count','additional_informations','spotting_distance','spotting_direction','picture','session_token','created_at','updated_at','operation_area','source_type'];

   
    protected $dates = ['created_at', 'updated_at'];
    
    public function count_messages(){
        return emergencyCaseMessage::where('emergency_case_id', $this->id)->count();
    }
    public function first_location(){
        return emergencyCaseLocation::where('emergency_case_id', $this->id)->orderBy('created_at', 'desc')->first();
    }
    
    public function last_location(){
        return emergencyCaseLocation::where('emergency_case_id', $this->id)->first();
    }
    public function updated_at(){
        $timestamp = strtotime($this->updated_at);
        if(time() < $timestamp + 86400*3)
            return \Carbon\Carbon::createFromTimeStamp(strtotime($this->updated_at))->diffForHumans();
        else
            return $this->updated_at;
    }
    
    public function involved_users(){
        $result = [];
                
        $involved_users = involvedUsers::where('case_id', '=', $this->id)->get();
        foreach($involved_users AS $involved_user){
            $user = User::where('id', '=', $involved_user->user_id)->get()[0];
            $result[] = $user->organisation.' '.$user->name;
        }
        
        return $result;
    }
    
    
    /**
     * Get the Locations
     *
     * @return obj
     */
    public function getLocationsAttribute()
    {
        return emergencyCaseLocation::where('emergency_case_id', $this->id)->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * Get the Locations
     *
     * @return obj
     */
    public function getEmergencyCaseTitleAttribute()
    {
        return Operation_area::find($this->operation_area)->title;
    }
    /**
     * Get no of messages
     *
     * @return int
     */
    public function getCountMessagesAttribute()
    {
        return $this->count_messages();
    }
    /**
     * Getmessages
     *
     * @return int
     */
    public function getMessagesAttribute()
    {
        return emergencyCaseMessage::where('emergency_case_id', $this->id)->get();
    }
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['locations', 'emergency_case_title', 'count_messages', 'messages'];
    
    public function translateColumnName($columnName){
        return ['id'=>'ID','boat_status'=>'status','boat_condition'=>'condition','boat_type'=>'type','other_involved'=>'other involved','engine_working'=>'engine working','passenger_count'=>'passenger count','additional_informations'=>'additional infos','spotting_distance'=>'spotting distance','spotting_direction'=>'spotting direction','picture'=>'picture','operation_area'=>'operation area'][$columnName];
    }
    
    public function emergency_case_locations()
    {
        return $this->hasMany('App\emergencyCaseLocation');
    }
}
