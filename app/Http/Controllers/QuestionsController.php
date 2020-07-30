<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$q = \App\Question::all()->toArray();
		foreach ($q as $key => $question) {
			$q[$key]['answers'] = \App\Answer::where('questions_id',$question['id'])->get()->toArray();
		}
        return json_encode($q);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$answers_res  = [];
		$questionData = array('name'   => trim($request['name']), 
		                      'sort'   => intval($request['sort'], 
							  'status' => trim($request['status']));
		$id           = \App\Question::create($questionData)->id;
		
		if ($id) {
			foreach ($request['answers'] as $answer) {
					$answerData = array('questions_id' => $id, 'name' => trim($answer['name']));
					if (\App\Answer::create($answerData)) {
						$answers_res[] = true;
					}
			}
		}
		if ($id && count($answers_res) == count($request['answers'])){
			$res=['status'=>'1',
				  'msg'=>'success'];
		}else{
			$res=['status'=>'0',
				  'msg'=>'fail'];
		}
		return json_encode($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$q = \App\Question::where('id',intval($id))->get()->toArray();
		foreach ($q as $key => $question) {
			$q[$key]['answers'] = \App\Answer::where('questions_id',$question['id'])->get()->toArray();
		}
        return json_encode($q);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$question_res = false;
		$res          = false;
		if ($id > 0) {
			$question = \App\Question::find(intval($id));
			$question->name   = trim($request['name']);
			$question->sort   = trim($request['sort']);
			$question->status = trim($request['status']);
			$question_res     = $question->save();
			$answers_res      = [];
			foreach ($request['answers'] as $answer) {
				if ($answer['id'] > 0) {
					$answer        = \App\Answer::find(intval($answer['id']));
					$answer->name  = trim($answer['name']);
					if ($answer->save()) {
						$answers_res[] = true;
					}
				}
				else {
					$answerData = array('questions_id' => intval($id), 'name' => trim($answer['name']));
					if (\App\Answer::create($answerData)) {
						$answers_res[] = true;
					}
				}
			}
		}
		if ($question_res && count($answers_res) == count($request['answers'])){
			$res=['status'=>'1',
				  'msg'=>'success'];
		}else{
			$res=['status'=>'0',
				  'msg'=>'fail'];
		}
		return json_encode($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$res      = false;
        $answer   = \App\Answer::where('questions_id',intval($id))->delete();
        $question = \App\Question::where('id',intval($id))->delete();
		if ($answer && $question){
			$res=['status'=>'1',
				  'msg'=>'success'];
		}else{
			$res=['status'=>'0',
				  'msg'=>'fail'];
		}
		return json_encode($res);
    }
}
