<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Validator;
use DB;
use DateTime;

use App\CompetitionGroup;
use App\CompetitionGroupUser;
use App\CompetitionQuestion;
use App\CompetitionAnswer;
use App\Models\User;
use App\Models\MutipleQuestion;
use App\Models\MutipleOption;


class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $today = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")) + ((60*60) * 6.5));
    	$validator = Validator::make($request->all(), [
            'user_id'  => 'required|exists:competition_group_users,user_id',
        ]);

        if ($validator->fails()) {
            if($validator->errors()->has('user_id'))
                return response()->json("You are not TLG member.", 400);
        }

        $competition_question = CompetitionQuestion::where('start_date','<=',$today)->where('end_date','>=',$today)->orderBy('id','desc')->first();

        /*// Changes for multi question
        $multiple_question = [];

        // For Textbox
        $textbox_question = ['id'=>1, 'type' => 'text','question'=>'What is your name?','option'=>[], 'answer'=>'Saw'];
        $multiple_question[] = $textbox_question;

        // For Textbox
        $textbox_question = ['id'=>2, 'type' => 'text','question'=>'How old are you?','option'=>[], 'answer'=>'25'];
        $multiple_question[] = $textbox_question;

        // For Checkbox
        $checkbox_question = ['id'=>3, 'type' => 'checkbox','question'=>'What is your favourite food?','option'=>['Cakes','Cookies','Crackers','Beverages'], 'answer'=>''];
        $multiple_question[] = $checkbox_question;

        // For Checkbox
        $checkbox_question = ['id'=>4, 'type' => 'checkbox','question'=>'What is your favourite programme?','option'=>['Java','PHP','ASP.NET','C#'], 'answer'=>''];
        $multiple_question[] = $checkbox_question;

        // For Radio
        $radio_question = ['id'=>5, 'type' => 'radio','question'=>'What is your Gender?','option'=>['Male','Female'], 'answer'=>''];
        $multiple_question[] = $radio_question;

        // For Radio
        $radio_question = ['id'=>6, 'type' => 'radio','question'=>'Are you programmer?','option'=>['Yes','No'], 'answer'=>''];
        $multiple_question[] = $radio_question;

        // For Image
        $image_question = ['id'=>7, 'type' => 'image','question'=>'Which is your photo?','option'=>['http://api.iwomenapp.org//users_photo/x400/photo_20160303085631_1637244829png_01.png','http://api.iwomenapp.org//users_photo/x400/photo_20160303085725_908458365png_02.png','http://api.iwomenapp.org//users_photo/x400/photo_20160303085800_905029675png_03.png'], 'answer'=>''];*/
        $multiple_question = MutipleQuestion::where('question_id', $competition_question->id)->get();
        foreach ($multiple_question as $key => $value) {
            $multiple_question[$key]['option'] = MutipleOption::where('mutiple_question_id', $value->id)->get();
        }

        $competition_question['multiple_question'] = $multiple_question;
        if($competition_question){
        	$datetime1 = new DateTime($today);
			$datetime2 = new DateTime($competition_question->end_date);
			$interval = $datetime1->diff($datetime2);
        	$competition_question['day_left'] = $interval->format('%a');
        	$competition_question['current_datetime'] = $today;
        	return response()->json($competition_question);
        }else{
        	$competition_answer = CompetitionAnswer::with('competitiongroupuser')->where('correct', true)->orderBy('id','desc')->get();
        	if($competition_answer){
                $answer_list = array();
                foreach ($competition_answer as $i => $answer) {
                    $answer_list[] = $answer;
                }

                $competition_question = CompetitionQuestion::where('id',$competition_answer[0]->question_id)->first();
        		$competition_question['correct_answer'] = $answer_list;
        		return response()->json($competition_question);
        	}
        	return response()->json('Hello, Competition is not started.', 403);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $question = new CompetitionQuestion();
            $question->question = $request->question;
            $question->question_mm = $request->question_mm;
            $question->description = $request->description;
            $question->description_mm = $request->description_mm;
            $question->instruction_about_game = $request->instruction_about_game;
            $question->start_date = $request->start_date;
            $question->end_date = $request->end_date;
            $question->save();

            $users = User::all();

            if($users){     

                $group_users = array();

                foreach ($users as $i => $user) {

                    //Grouping
                    if(isset($user['tlg_city_address']) && $user['tlg_city_address'])
                       $group_users[$user['tlg_city_address']][] = $user;
                    
                   
                }
                $group_index = 1;
                $i = 0;
                foreach ($group_users as $key => $city) {
                	foreach ($city as $user) {
                		
                		if($i % 5 == 0){
	                        $group_name =  'Group - '. ($group_index++);
	                    }

	                    $group_user = new CompetitionGroupUser();
	                    $group_user->group_name = $group_name;
	                    $group_user->user_id = $user['id'];
	                    $group_user->username = $user['username'];
	                    $group_user->phone = $user['phoneNo'];
	                    $group_user->profile_img = isset($user['user_profile_img']) ? json_encode($user['user_profile_img']) : null;
	                    $group_user->group_city = $key;
	                    $group_user->competition_question_id = $question->id;
	                    $group_user->save();

	                    $i++;
                	}
                }
                
            }else{
                DB::rollBack();
                return response()->json('Something went wrong in parse.', 400);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json('Something went wrong.', 400);
        }
         

        return response()->json($question);
    }

    public function getGroupUser(Request $request){
        $validator = Validator::make($request->all(), [
            'question_id'     => 'required',
            'user_id'         => 'required',
        ]);

        if ($validator->fails()) {
            if($validator->errors()->has('question_id'))
                return response()->json($validator->errors()->first('question_id'), 400);
            if($validator->errors()->has('group_user_id'))
                return response()->json($validator->errors()->first('user_id'), 400);
        }

        $group_name = CompetitionGroupUser::where('competition_question_id', $request->question_id)->where('user_id', $request->user_id)->pluck('group_name');
        $group_user_ids = CompetitionGroupUser::where('group_name', $group_name)->where('competition_question_id',$request->question_id)->lists('id');
        $group_list = CompetitionGroupUser::with('answer')->where('competition_question_id', $request->question_id)->where('group_name', $group_name)->get();

        foreach ($group_list as $key => $value) {
            $profile_img = json_decode($value->profile_img, true);
            if($profile_img){
                $group_list[$key]['image_url'] = isset($profile_img['url']) ? $profile_img['url'] : '';
            }else
                $group_list[$key]['image_url'] = "http://url";
            $group_list[$key]['total_has_answer'] = count($group_list) * 3;
            $group_list[$key]['current_has_answer'] = CompetitionAnswer::wherein('competition_group_user_id', $group_user_ids)->count();
            $group_list[$key]['init_answer'] = null;
            foreach ($group_list[$key]['answer'] as $i => $value) {
                if($i == 0){
                    $group_list[$key]['init_answer'] = $value;
                }
            }
        }
        return response()->json($group_list);
    }

    public function getUserAnswer($id){
    	$answer = CompetitionAnswer::where('competition_group_user_id', $id)->get();
    	return response()->json($answer);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function answer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id'           => 'required|exists:competition_questions,id',
            'group_user_id'         => 'required|exists:competition_group_users,id',
            'answer1'               => 'unique:competition_group_answers,answer',
            'answer_mm1'            => 'unique:competition_group_answers,answer_mm',
            'answer2'               => 'unique:competition_group_answers,answer',
            'answer_mm2'            => 'unique:competition_group_answers,answer_mm',
            'answer3'               => 'unique:competition_group_answers,answer',
            'answer_mm3'            => 'unique:competition_group_answers,answer_mm',
        ]);

        if ($validator->fails()) {
            if($validator->errors()->has('question_id'))
                return response()->json($validator->errors()->first('question_id'), 400);
            if($validator->errors()->has('group_user_id'))
                return response()->json($validator->errors()->first('group_user_id'), 400);
        }
        if($request->answer1 || $request->answer_mm1){
            $checkExisted = CompetitionAnswer::where('id',$request->answer1_id)->first();
            if(!$checkExisted){
                $competition_answer = new CompetitionAnswer();
                $competition_answer->question_id = $request->question_id;
                $competition_answer->competition_group_user_id = $request->group_user_id;
                $competition_answer->answer = $request->answer1;
                $competition_answer->answer_mm = $request->answer_mm1;
                $competition_answer->save();
            }
        }
        if($request->answer2 || $request->answer_mm2){
            $checkExisted = CompetitionAnswer::where('id',$request->answer2_id)->first();
            if(!$checkExisted){
                $competition_answer = new CompetitionAnswer();
                $competition_answer->question_id = $request->question_id;
                $competition_answer->competition_group_user_id = $request->group_user_id;
                $competition_answer->answer = $request->answer2;
                $competition_answer->answer_mm = $request->answer_mm2;
                $competition_answer->save();
            }
        }
        if($request->answer3 || $request->answer_mm3){
            $checkExisted = CompetitionAnswer::where('id',$request->answer3_id)->first();
            if(!$checkExisted){
                $competition_answer = new CompetitionAnswer();
                $competition_answer->question_id = $request->question_id;
                $competition_answer->competition_group_user_id = $request->group_user_id;
                $competition_answer->answer = $request->answer3;
                $competition_answer->answer_mm = $request->answer_mm3;
                $competition_answer->save();
            }
        }

        // Updating for Submit Anwser;
        $group_name = CompetitionGroupUser::where('id', $request->group_user_id)->pluck('group_name');
        $competition_groups = CompetitionGroupUser::where('group_name', $group_name)->where('competition_question_id', $request->question_id)->lists('id');
        if(count($competition_groups) > 0){
            $competition_anwsers = CompetitionAnswer::where('question_id',$request->question_id)->wherein('competition_group_user_id',$competition_groups)->get();
            if(count($competition_anwsers) == count($competition_groups) *3){
                $group_anwser = CompetitionAnswer::where('question_id',$request->question_id)->wherein('competition_group_user_id',$competition_groups)->update(array('status'=>1));
               	return response()->json('Congratulations!, your team has finished the challenge, Anwsers submitted!');
            }else{
            	return response()->json('Thanks, please submit 3 answers or let other members submit answers to compelete.');
            }
        }  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
