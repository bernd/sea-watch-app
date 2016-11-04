<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyCaseMessage extends Model
{
    protected $fillable = ['message_type', 'author_id', 'text', 'seen_by', 'received_by'];
}
