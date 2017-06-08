<?php

namespace Hooks\VoyagerPolls\Http\Controllers;

use Illuminate\Http\Request;
use Hooks\VoyagerPolls\Models\Poll;
use Hooks\VoyagerPolls\Models\PollQuestion;
use Hooks\VoyagerPolls\Models\PollAnswer;

class PollsController extends \App\Http\Controllers\Controller
{
    public function browse(){
    	$polls = Poll::paginate(10);
    	return view('polls::browse', compact('polls'));
    }

    public function edit($id){
    	$poll = Poll::with('questions')->findOrFail($id);
    	return view('polls::edit-add', compact('poll'));
    }

    public function add(){
    	return view('polls::edit-add');
    }

    public function add_post(Request $request){



    	try{
    		$request->poll = json_decode(json_encode($request->poll), FALSE);
    		$poll = Poll::create(['name' => $request->poll->name, 'slug' => $request->poll->slug ]);


    		foreach($request->poll->questions as $order => $questions){

    			$question = PollQuestion::create(['poll_id' => $poll->id, 'question' => $questions->question, 'order' => $order+1]);

    			foreach($questions->answers as $answer_order => $answer){
    				$answer = PollAnswer::create(['question_id' => $question->id, 'answer' => $answer, 'order' => $answer_order+1]);
    			}
    		}

    		return response()->json( ['status' => 'success', 'message' => 'Successfully created new poll'] );
    	} catch(Exception $e){
    		return response()->json( ['status' => 'error', 'message' => $e->getMessage] );
    	}
    	
    }
}
