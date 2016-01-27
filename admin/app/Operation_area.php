<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\emergencyCase;
use DB;
class Operation_area extends Model
{
    
    protected $guarded  = array('id');
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','polygon_coordinates'];
    /**
     * Get the user that owns the task.
     */
    public function user_id()
    {
        return $this->belongsTo(User::class);
    }
    public function count_open_cases(){
	return emergencyCase::where('operation_area', '=', $this->id)->count();
    }
}
