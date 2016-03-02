@extends('layouts.app')

@section('content')

    <div class="container">

        @include('flash::message')

        <div class="row">
            <h1 class="pull-left">SubResourceDetails</h1>
            <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('subResourceDetails.create') !!}">Add New</a>
        </div>

        <div class="row">
            @if($subResourceDetails->isEmpty())
                <div class="well text-center">No SubResourceDetails found.</div>
            @else
                @include('subResourceDetails.table')
            @endif
        </div>

        @include('common.paginate', ['records' => $subResourceDetails])


    </div>
@endsection
