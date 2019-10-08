@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-exclamation"></i> @lang('legaladvice.priorities.title')</h1>
@stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['legaladvice.priorities.store']]) }}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('name', __('legaladvice.priorities.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(__('global.app_create'), ['class' => 'btn btn-success']) }}
            {{ Form::close() }}
        </div>
    </div>
@stop
