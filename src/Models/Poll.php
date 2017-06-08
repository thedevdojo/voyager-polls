<?php

namespace Hooks\VoyagerPolls\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
	protected $table = 'voyager_polls';
    protected $fillable = ['name', 'slug'];

    public function questions(){
    	return $this->hasMany('Hooks\VoyagerPolls\Models\PollQuestion', 'poll_id');
    }
}
