@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-stethoscope"></i> @lang('global.dentist.specialties.title') <small>@lang('global.app_create')</small></h1>
@stop

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['dentist.specialties.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
        <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', __('global.dentist.specialties.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('name'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('description', __('global.dentist.specialties.fields.description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if($errors->has('description'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('description') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-footer">
            {!! Form::submit(trans('global.app_create'), ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
