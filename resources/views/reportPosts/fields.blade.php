<!-- Postid Field -->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('postId', 'Postid:') !!}
	{!! Form::file('postId') !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('userId', 'Userid:') !!}
	{!! Form::text('userId', null, ['class' => 'form-control']) !!}
</div>

<!-- Point Field -->
<div class="form-group col-sm-6 col-lg-4">
    {!! Form::label('point', 'Point:') !!}
	{!! Form::text('point', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
</div>