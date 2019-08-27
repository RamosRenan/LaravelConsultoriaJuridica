@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-ambulance"></i> @lang('global.dentist.patients.title') <small>@lang('global.app_edit')</small></h1>
@stop

@section('content')
    {!! Form::model($item, ['method' => 'PUT', 'route' => ['dentist.patients.update', $item->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
        <div class="row">
                <div class="col-xs-6 form-group">
                    {!! Form::label('name', __('global.dentist.patients.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('name'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-xs-3 form-group">
                    {!! Form::label('rg', __('global.dentist.patients.fields.rg').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('rg', old('rg'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('rg'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('rg') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-xs-3 form-group">
                    {!! Form::label('cpf', __('global.dentist.patients.fields.cpf').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('cpf', old('cpf'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('cpf'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('cpf') }}</span>
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
