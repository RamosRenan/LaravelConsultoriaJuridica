@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-list"></i> @lang('global.dentist.supplies_items.title') <small>@lang('global.app_edit')</small></h1>
@stop

@section('content')
    {!! Form::model($item, ['method' => 'PUT', 'route' => ['dentist.supplies.update', $id]]) !!}
    {!! Form::hidden('supply_id', $id ) !!}
    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 form-group">
                {!! Form::label('name', __('global.dentist.supplies.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('name'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-footer">
            {!! Form::submit(trans('global.app_edit'), ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@stop