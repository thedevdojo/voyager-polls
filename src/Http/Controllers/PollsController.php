<?php

namespace Hooks\VoyagerPolls\Http\Controllers;

use Illuminate\Http\Request;

class PollsController extends \App\Http\Controllers\Controller
{
    public function browse(){
    	return view('polls::browse');
    }

    public function add(){
    	return view('polls::edit-add');
    }
}
