@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-@lang('refectory.stocks.icon')"></i> @lang('refectory.stocks.title')</h1>
    @stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['refectory.stock.store'], 'onsubmit' => 'this.preventDefault();']) }}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    {{ Form::label('contract', __('refectory.stocks.fields.contract').'*', ['class' => 'control-label']) }}
                    {{ Form::text('contract', old('contract'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('contract'))
                        <span class="text-danger">{{ $errors->first('lot') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::label('unit_id', __('refectory.stocks.fields.unit').'*', ['class' => 'control-label']) }}
                    {{ Form::select('unit_id', $units, old('unit_id'), ['class' => 'form-control select2', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('unit_id'))
                        <span class="text-danger">{{ $errors->first('unit_id') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('date', __('refectory.stocks.fields.date').'*', ['class' => 'control-label']) }}
                    {{ Form::date('date', old('date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(trans('global.app_create'), ['class' => 'btn btn-primary']) }}
            {{ Form::close() }}
        </div>
    </div>
@stop
