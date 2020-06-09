@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-@lang('refectory.supplies.icon')"></i> @lang('refectory.supplies.title')</h1>
@stop

@section('content')
    {{ Form::model($item, ['method' => 'PUT', 'route' => ['refectory.supplies.update', $id]]) }}
    {{ Form::hidden('supply_id', $id ) }}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_edit')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 form-group">
                {{ Form::label('name', __('refectory.supplies.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(trans('global.app_edit'), ['class' => 'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::close() }}
@stop