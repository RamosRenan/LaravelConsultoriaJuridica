@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-hospital"></i> @lang('dentist.units.title')</h1>
@stop

@section('content')
{!! Form::open(['method' => 'POST', 'route' => ['dentist.units.store']]) !!}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-10 form-group">
                    {!! Form::label('name', __('dentist.units.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-sm-2 form-group">
                    {!! Form::label('code', __('dentist.units.fields.code').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('code', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('code'))
                        <span class="text-danger">{{ $errors->first('code') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {!! Form::submit(trans('global.app_create'), ['class' => 'btn btn-success']) !!}
        </div>
    </div>
{!! Form::close() !!}
@stop
