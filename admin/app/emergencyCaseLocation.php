<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
class emergencyCaseLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lon','lat', 'accuracy', 'heading', 'connection_type', 'message' ,'created_at','updated_at'];
    public function emergency_case()
    {
        return $this->belongsTo('App\emergencyCase')->withTimestamps();
    }
}
