@extends('layouts.app')

@section('title', 'Competition Question Update')

@section('styles')
	<link href="{{Request::root()}}/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css" rel="stylesheet">
	<link href="{{Request::root()}}/vendors/bower_components/summernote/dist/summernote.css" rel="stylesheet">
	<link href="{{Request::root()}}/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
@endsection

@section('content')


<section id="content">
    <div class="container">
        <div class="block-header hide">
            <h2>Update Competition Question</h2>
        </div>
	
		<div class="card">
	        <!-- <div class="card-header">
	            <h2>	Update New Competition Question <small></small></h2>
	        </div> -->
		    <div class="listview lv-bordered lv-lg">
	            <div class="lv-header-alt clearfix">
	                <h2 class="lvh-label hidden-xs">Update New Competition Question</h2>
	            </div>
	        </div>
		    @if ($errors->has())
		        <div class="row">
		        	<div class="col-sm-12">
				        <div class="alert alert-danger alert-dismissible" role="alert">
			                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			                    @foreach ($errors->all() as $key=>$error)
			                          ({{$key+1}}) {{ $error }} &nbsp;        
			                    @endforeach
			            </div>
		            </div>
	            </div>
	        @endif

	        <div class="card-body card-padding lv-body">
	            <form action="/admin/competition-question/{{$question->id}}/update" class="form-horizontal" method="post" role="form">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Question</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                            	<textarea class="col-sm-12"	rows="5" name="question">{{$question->question}}</textarea>
                               <!-- <div ="html-editor"></div>
	                            <input type="hidden" id="question" value="">
	                            <br/> -->
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Question (Myanmar)</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                            	<textarea class="col-sm-12"	rows="5" name="question_mm">{{$question->question_mm}}</textarea>
                               <!-- <div class="html-editor" name="question_mm"></div> -->
	                            
	                            <br/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                            	<textarea class="col-sm-12"	rows="5" name="description">{{$question->description}}</textarea>
                               <!-- <div class="html-editor" name="description"></div> -->
	                            
	                            <br/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Description (Myanmar)</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                            	<textarea class="col-sm-12"	rows="5" name="description_mm">{{$question->description_mm}}</textarea>
                               <!-- <div class="html-editor" name="description_mm"></div> -->
	                            
	                            <br/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Instruction About Game</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                            	<textarea class="col-sm-12"	rows="5" name="instruction">{{$question->instruction_about_game}}</textarea>
                               <!-- <div class="html-editor" name="instruction"></div> -->
	                            
	                            <br/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Instruction About Game (Myanmar)</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                            	<textarea class="col-sm-12"	rows="5" name="instruction_mm">{{$question->instruction_about_game_mm}}</textarea>
                               <!-- <div class="html-editor" name="instruction"></div> -->
	                            
	                            <br/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Group Description</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                                <textarea class="col-sm-12" rows="5" name="group_description">{{$question->group_description}}</textarea>
                               <!-- <div class="html-editor" name="instruction"></div> -->
                                
                                <br/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Group Description (Myanmar)</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                                <textarea class="col-sm-12" rows="5" name="group_description_mm">{{$question->group_description_mm}}</textarea>
                               <!-- <div class="html-editor" name="instruction"></div> -->
                                
                                <br/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Answer Submit Description</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                                <textarea class="col-sm-12" rows="5" name="answer_submit_description">{{$question->answer_submit_description}}</textarea>
                                <br/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Answer Submit Description (Myanmar)</label>
                        <div class="col-sm-10">
                            <div class="fg-line">
                                <textarea class="col-sm-12" rows="5" name="answer_submit_description_mm">{{$question->answer_submit_description_mm}}</textarea>
                               <!-- <div class="html-editor" name="instruction"></div> -->
                                
                                <br/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="row">
		                        <div class="col-sm-4">
		                            <div class="input-group form-group">
		                                <span class="input-group-addon"> <i class="zmdi zmdi-calendar"></i> Start Date</span>
		                                    <div class="dtp-container fg-line">
		                                    <input type='text' class="form-control date-time-picker" value="" name="start_date" placeholder="{{date('m/d/Y h:i A', strtotime($question->start_date))}}">
		                                </div>
		                            </div>
		                        </div>

		                        <div class="col-sm-4">
		                            <div class="input-group form-group">
		                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> End Date  </span>
		                                    <div class="dtp-container fg-line">
		                                    <input type='text' class="form-control date-time-picker" value="" name="end_date" placeholder="{{date('m/d/Y h:i A', strtotime($question->end_date))}}">
                                            

		                                </div>
		                            </div>
		                        </div>
		                        

		                        <div class="col-sm-4">
		                            <div class="input-group form-group">
		                                <span class="input-group-addon"><i class="zmdi zmdi-accounts"></i> Group Users </span>
		                                    <div class="dtp-container fg-line">
		                                    <input type='number' class="form-control" value="{{$question->user_count}}" name="groupusers" placeholder="">
		                                </div>
		                            </div>
		                        </div>
		                    </div>
                        </div>
                    </div>
                    <!-- Isallow Field -->
                    <div class="form-group">
                        <div class="fg-line"> 
                            <div class="checkbox  col-md-offset-2">
                                <label>
                                    {!! Form::checkbox('all_user', 1, false) !!}
                                    <i class="input-helper"></i>
                                    All User
                                </label>
                            </div>

                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-offset-2 col-sm-10">
                    		<button type="submit" id='save'  class="btn btn-primary btn-sm m-t-10 waves-effect">Update New Question</button>
                    	</div>
                    </div>

		        </form>
	            
	        </div>
	        <br/>
	    </div>
	</div>
</section>
@endsection

@section('scripts')
    <!-- @parent -->
    <script src="{{Request::root()}}/vendors/bower_components/summernote/dist/summernote.min.js"></script>
    <script src="{{Request::root()}}/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    
    <script src="{{Request::root()}}/vendors/bower_components/moment/min/moment.min.js"></script>
	<script src="{{Request::root()}}/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
	<script src="{{Request::root()}}/vendors/bower_components/nouislider/distribute/jquery.nouislider.all.min.js"></script>
	<script src="{{Request::root()}}/vendors/bower_components/summernote/dist/summernote.min.js"></script>
	<script src="{{Request::root()}}/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript">
	     (function(){
	     	('.date-time-picker').datetimepicker();
	     	$('#html-editor').summernote({
	            height: 150
	        });
	     	$('#save').click(function(){
	     		alert($('.html-editor-question').code()[0]);
	     	});
	     });
    </script>
@endsection


