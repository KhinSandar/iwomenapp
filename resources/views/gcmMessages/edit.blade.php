@extends('layouts.app')

@section('content')
<div class="container">

    @include('common.errors')

    {!! Form::model($gcmMessage, ['route' => ['gcmMessages.update', $gcmMessage->id], 'method' => 'patch']) !!}

        @include('gcmMessages.fields')

    {!! Form::close() !!}
</div>
@endsection
