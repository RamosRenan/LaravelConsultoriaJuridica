@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-location-arrow"></i> @lang('legaladvice.places.title')</h1>
@stop

@section('content')
    {!! Form::model($item, ['method' => 'PUT', 'route' => ['legaladvice.places.update', $id]]) !!}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_edit')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    {!! Form::label('name', __('legaladvice.places.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('name'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {!! Form::submit(__('global.app_edit'), ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
