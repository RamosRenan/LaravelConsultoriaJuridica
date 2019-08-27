@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-stethoscope"></i> @lang('dentist.specialties.title')</h1>
@stop

@section('content')
    {{ Form::model($item, ['method' => 'PUT', 'route' => ['dentist.specialties.update', $item->id]]) }}

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_edit')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('name', __('dentist.specialties.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('description', __('dentist.specialties.fields.description'), ['class' => 'control-label']) }}
                    {{ Form::textarea('description', old('name'), ['class' => 'form-control', 'placeholder' => '']) }}
                    @if($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(__('global.app_edit'), ['class' => 'btn btn-primary']) }}
            {{ Form::close() }}
        </div>
    </div>
@stop
