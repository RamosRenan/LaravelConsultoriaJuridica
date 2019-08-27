@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-ambulance"></i> @lang('global.dentist.procedures.title') <small>@lang('global.app_create')</small></h1>
@stop

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['dentist.procedures.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('name', __('global.dentist.procedures.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('name'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-xs-4 form-group">
                    {!! Form::label('price', __('global.dentist.procedures.fields.price'), ['class' => 'control-label']) !!}
                    {!! Form::number('price', old('price'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('price'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('price') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-xs-4 form-group">
                    {!! Form::label('date', __('global.dentist.procedures.fields.date'), ['class' => 'control-label']) !!}
                    {!! Form::date('date', old('date'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('date'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('date') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-footer">
            {!! Form::submit(trans('global.app_create'), ['class' => 'btn btn-success']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
