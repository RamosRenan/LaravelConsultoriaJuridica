@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-bars"></i> @lang('menu.title')</h1>
@stop

@section('content')
{{ Form::open(['method' => 'POST', 'route' => ['admin.menu.store']]) }}
    {{ Form::hidden('mode', 'add_menu') }}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::label('name', __('menu.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('title'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::label('call', __('menu.fields.call').'*', ['class' => 'control-label']) }}
                    {{ Form::text('call', old('call'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('call'))
                            <span class="text-danger">{{ $errors->first('call') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(trans('global.app_create'), ['class' => 'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::close() }}

@stop
