@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-@lang('refectory.stocks.icon')"></i> @lang('refectory.supplies_stock.title')</h1>
    @stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['refectory.stock.store'], 'onsubmit' => 'this.preventDefault();']) }}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::label('unit_id', __('refectory.supplies.fields.unit').'*', ['class' => 'control-label']) }}
                    {{ Form::select('unit_id', $units, old('unit_id'), ['class' => 'form-control select2', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('unit_id'))
                        <span class="text-danger">{{ $errors->first('unit_id') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::label('supply_id', __('refectory.supplies.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::select('supply_id', $supplies, old('supply_id'), ['class' => 'form-control select2', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('supply_id'))
                        <span class="text-danger">{{ $errors->first('supply_id') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 form-group">
                    {{ Form::label('date', __('refectory.supplies.fields.date').'*', ['class' => 'control-label']) }}
                    {{ Form::date('date', old('date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('lot', __('refectory.supplies.fields.lot').'*', ['class' => 'control-label']) }}
                    {{ Form::number('lot', old('lot'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('lot'))
                        <span class="text-danger">{{ $errors->first('lot') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('quantity', __('refectory.supplies.fields.quantity').'*', ['class' => 'control-label']) }}
                    {{ Form::number('quantity', old('quantity'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('quantity'))
                        <span class="text-danger">{{ $errors->first('quantity') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('price', __('refectory.supplies.fields.price').'*', ['class' => 'control-label']) }}
                    {{ Form::number('price', old('price'), ['class' => 'form-control', 'step' => '0.01', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('price'))
                        <span class="text-danger">{{ $errors->first('price') }}</span>
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
