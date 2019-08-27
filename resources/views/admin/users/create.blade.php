@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-user"></i> @lang('users.title')</h1>
@stop

@section('content')
{{ Form::open(['method' => 'POST', 'route' => [$route]]) }}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('username', __('users.fields.username').'*', ['class' => 'control-label']) }}
                        {{ Form::text('username', old('login'), ['class' => 'form-control', 'placeholder' => __('users.fields.username'), 'required' => '']) }}
                        @if($errors->has('username'))
                            <span class="text-danger">{{ $errors->first('username') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        {{ Form::label('name', __('users.fields.name').'*', ['class' => 'control-label']) }}
                        {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => __('users.fields.name'), 'required' => '']) }}
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        {{ Form::label('email', __('users.fields.email').'*', ['class' => 'control-label']) }}
                        {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => __('users.fields.email'), 'required' => '']) }}
                        @if($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        {{ Form::label('password', __('users.fields.password').'*', ['class' => 'control-label']) }}
                        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('users.fields.password'), 'required' => '']) }}
                        @if($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        {{ Form::label('password_confirmation', __('users.fields.password_confirmation').'*', ['class' => 'control-label']) }}
                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => __('users.fields.password_confirmation')]) }}
                        @if($errors->has('password_confirmation'))
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('roles', __('users.fields.roles').'*', ['class' => 'control-label']) }}
                        {{ Form::select('roles[]', $roles, old('roles'), ['class' => 'form-control select2', 'multiple' => 'multiple']) }}
                        @if($errors->has('roles'))
                            <span class="text-danger">{{ $errors->first('roles') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(__('global.app_create'), ['class' => 'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}
@stop
