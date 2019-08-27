@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-tachometer-alt"></i> @lang('dashboard.title')</h1>
@stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['admin.dashboard.store']]) }}

    <div class="card card-default">
        <div class="card-header">
            <h3>@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    {{ Form::label('title', __('dashboard.fields.title').'*', ['class' => 'control-label']) }}
                    {{ Form::text('title', old('title'), ['class' => 'form-control', 'required' => '']) }}
                    @if($errors->has('title'))
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                    @endif
                </div>
                <div class="col-md-3">
                    {{ Form::label('name', __('dashboard.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::select('name', $dirs, old('name'), ['class' => 'form-control select2', 'required' => '']) }}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-2">
                    {{ Form::label('role', __('dashboard.fields.role').'*', ['class' => 'control-label']) }}
                    {{ Form::select('role', $roles, old('role'), ['class' => 'form-control select2']) }}
                    @if($errors->has('role'))
                        <span class="text-danger">{{ $errors->first('role') }}</span>
                    @endif
                </div>
                <div class="col-md-2">
                    {{ Form::label('grid', __('dashboard.fields.grid').'*', ['class' => 'control-label']) }}
                    {{ Form::select('grid', $grids, old('grid'), ['class' => 'form-control select2']) }}
                    @if($errors->has('grid'))
                        <span class="text-danger">{{ $errors->first('grid') }}</span>
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
