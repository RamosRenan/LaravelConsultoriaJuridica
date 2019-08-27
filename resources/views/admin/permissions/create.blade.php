@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-key"></i> @lang('permissions.title')</h1>
@stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['admin.permissions.store']]) }}

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::label('name', __('permissions.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::label('module', __('permissions.fields.module').'*', ['class' => 'control-label']) }}
                    {{ Form::text('module', old('module'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('module'))
                        <span class="text-danger">{{ $errors->first('module') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(__('global.app_create'), ['class' => 'btn btn-primary']) }}
            {{ Form::close() }}
        </div>
    </div>
@stop
