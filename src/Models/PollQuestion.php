<?php

namespace Hooks\VoyagerPolls\Models;

use Illuminate\Database\Eloquent\Model;

class PollQuestion extends Model
{
    protected $table = 'voyager_poll_questions';
    protected $fillable = ['poll_id', 'question', 'order'];

    public function answers(){
    	return $this->hasMany('Hooks\VoyagerPolls\Models\PollAnswer', 'question_id')->orderBy('order', 'ASC');
    }

}
