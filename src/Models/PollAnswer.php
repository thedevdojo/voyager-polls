<?php

namespace Hooks\VoyagerPolls\Models;

use Illuminate\Database\Eloquent\Model;

class PollAnswer extends Model
{
    protected $table = 'voyager_poll_answers';
    protected $fillable = ['question_id', 'answer', 'order'];
}
