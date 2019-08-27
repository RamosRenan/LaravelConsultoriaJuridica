@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-ambulance"></i> @lang('global.dentist.units.title') <small>@lang('global.app_edit')</small></h1>
@stop

@section('content')
    {!! Form::model($item, ['method' => 'PUT', 'route' => ['dentist.units.update', $id]]) !!}
    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-10 form-group">
                    {!! Form::label('name', __('global.dentist.units.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('name'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-xs-2 form-group">
                    {!! Form::label('code', __('global.dentist.units.fields.code'), ['class' => 'control-label']) !!}
                    {!! Form::text('code', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('code'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('code') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-footer">
            {!! Form::submit(trans('global.app_edit'), ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
