@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-@lang('refectory.procedures.icon')"></i> @lang('refectory.procedures.title')</h1>
@stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['refectory.procedures.store']]) }}

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    {{ Form::label('name', __('refectory.procedures.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-4 form-group">
                    {{ Form::label('price', __('refectory.procedures.fields.price'), ['class' => 'control-label']) }}
                    {{ Form::number('price', old('price'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('price'))
                        <span class="text-danger">{{ $errors->first('price') }}</span>
                    @endif
                </div>
                <div class="col-md-4 form-group">
                    {{ Form::label('date', __('refectory.procedures.fields.date'), ['class' => 'control-label']) }}
                    {{ Form::date('date', old('date'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(__('global.app_create'), ['class' => 'btn btn-success']) }}
            {{ Form::close() }}
        </div>
    </div>
@stop
