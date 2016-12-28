<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $guarded  = array('id');
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    
    
    
    /**
     * Get the Vehicle
     *
     * @return obj
     */
    public function getVehicle()
    {   
        return Vehicle::where('user_id','=',$this->id)->first();
    }
    /**
     * Get the vehicle_id
     *
     * @return int
     */
    public function getVehicleIdAttribute()
    {   
        return $this->getVehicle()->id;
    }
    
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['vehicle_id'];
    protected $hidden = ['password', 'remember_token'];
}
