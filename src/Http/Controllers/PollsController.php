<?php

namespace Hooks\VoyagerPolls\Http\Controllers;

use Illuminate\Http\Request;
use Hooks\VoyagerPolls\Models\Poll;
use Hooks\VoyagerPolls\Models\PollQuestion;
use Hooks\VoyagerPolls\Models\PollAnswer;

class PollsController extends \App\Http\Controllers\Controller
{
    //***************************************
    //               ____
    //              |  _ \
    //              | |_) |
    //              |  _ <
    //              | |_) |
    //              |____/
    //
    //      Browse the polls (B)READ
    //
    //****************************************

    public function browse(){
    	$polls = Poll::paginate(10);
    	return view('polls::browse', compact('polls'));
    }


    //***************************************
    //                _____
    //               |  __ \
    //               | |__) |
    //               |  _  /
    //               | | \ \
    //               |_|  \_\
    //
    //      Read a specific poll B(R)EAD
    //
    //****************************************

    public function read($id){

    }


    //***************************************
    //                ______
    //               |  ____|
    //               | |__
    //               |  __|
    //               | |____
    //               |______|
    //
    //          Edit a poll BR(E)AD
    //
    //****************************************

    public function edit($id){
    	$poll = $this->getPollData($id);
    	return view('polls::edit-add', compact('poll'));
    }

    // BR(E)AD POST REQUEST
    public function edit_post(Request $request){
        return $this->updateOrCreatePoll($request, 'Successfully updated poll!');
    }

    //***************************************
    //
    //                   /\
    //                  /  \
    //                 / /\ \
    //                / ____ \
    //               /_/    \_\
    //
    //
    //          Add a new poll BRE(A)D
    //
    //****************************************

    public function add(){
    	return view('polls::edit-add');
    }

    // BRE(A)D POST REQUEST
    public function add_post(Request $request){
    	return $this->updateOrCreatePoll($request, 'Successfully created new poll!');
    }

    //***************************************
    //                _____
    //               |  __ \
    //               | |  | |
    //               | |  | |
    //               | |__| |
    //               |_____/
    //
    //          Delete a poll BREA(D)
    //
    //****************************************

    public function delete(Request $request){
        $id = $request->id;
        $data = Poll::destroy($id)
            ? [
                'message'    => "Successfully Deleted Poll",
                'alert-type' => 'success',
            ]
            : [
                'message'    => "Sorry it appears there was a problem deleting this poll",
                'alert-type' => 'error',
            ];

        return redirect()->route("voyager.polls")->with($data);
    }


    private function updateOrCreatePoll($request, $success_msg){
        try{
            $request->poll = json_decode(json_encode($request->poll), FALSE);
        
            $poll = Poll::updateOrCreate(
                    ['id' => $request->poll->id],
                    ['name' => $request->poll->name, 'slug' => $request->poll->slug ]
                );

            // delete any questions that have been removed
            PollQuestion::where('poll_id', '=', $poll->id)->whereNotIn('id', array_pluck($request->poll->questions, 'id'))->delete();

            $question_order = 1;
            foreach($request->poll->questions as $questions){

                $question = PollQuestion::updateOrCreate(['id' => $questions->id], ['poll_id' => $poll->id, 'question' => $questions->question, 'order' => $question_order]);
                $question_order += 1;

                // delete any answers that have been removed
                PollAnswer::where('question_id', '=', $question->id)->whereNotIn('id', array_pluck($questions->answers, 'id'))->delete();

                $answer_order = 1;
                foreach($questions->answers as $answer){
                    if(!empty($answer->answer)){
                        $answer = PollAnswer::updateOrCreate(['id' => $answer->id], ['question_id' => $question->id, 'answer' => $answer->answer, 'order' => $answer_order]);
                        $answer_order += 1;
                    }
                }

            }

            $poll = $this->getPollData($poll->id);
            return response()->json( ['status' => 'success', 'message' => $success_msg, 'poll' => $poll] );
        } catch(Exception $e){
            return response()->json( ['status' => 'error', 'message' => $e->getMessage] );
        }
    }

    private function getPollData($id){
        $poll = Poll::with('questions')->findOrFail($id);
        foreach($poll->questions as $question){
            $question['answers'] = $question->answers;
        }
        return $poll;
    }

    public function json($slug){
        $poll = Poll::where('slug', '=', $slug)->firstOrFail();
        return response()->json($this->getPollData($poll->id));
    }

    public function api_vote(Request $request, $id){
        if($request->ajax()){
            $answer = PollAnswer::find($id);
            if(!isset($answer)){
                return response()->json( ['status' => 'error', 'message' => 'Answer Not Found'] );
            }
            $answer->votes += 1;
            $answer->save();

            $question = $answer->question;

            return response()->json( ['status' => 'success', 'message' => 'Successfully Voted', 'question_id' => $question->id] );
        } else {
            return response()->json( ['status' => 'error', 'message' => 'Route cannot be called directly'] );
        }
    }
}
