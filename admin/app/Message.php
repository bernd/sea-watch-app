<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message_type', 'author_id', 'text', 'seen_by', 'received_by'];

    
    /**
     * Get the Author
     *
     * @return obj
     */
    public function getAuthorAttribute()
    {
        return User::find($this->author_id);
    }
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['author'];
    
}
