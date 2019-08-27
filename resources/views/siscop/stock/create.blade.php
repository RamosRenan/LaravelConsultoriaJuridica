@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-shopping-cart"></i> @lang('global.dentist.supplies_stock.title') <small>@lang('global.app_create')</small></h1>
    @stop

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['dentist.stock.store'], 'onsubmit' => 'this.preventDefault();']) !!}
    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    {!! Form::label('unit_id', __('global.dentist.supplies.fields.unit').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('unit_id', $units, old('unit_id'), ['class' => 'form-control select2', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('unit_id'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('unit_id') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {!! Form::label('supply_id', __('global.dentist.supplies.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('supply_id', $supplies, old('supply_id'), ['class' => 'form-control select2', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('supply_id'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('supply_id') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 form-group">
                    {!! Form::label('date', __('global.dentist.supplies.fields.date').'*', ['class' => 'control-label']) !!}
                    {!! Form::date('date', old('date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('date'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('date') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('lot', __('global.dentist.supplies.fields.lot').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('lot', old('lot'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('lot'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('lot') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('quantity', __('global.dentist.supplies.fields.quantity').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('quantity', old('quantity'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('quantity'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('quantity') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {!! Form::label('price', __('global.dentist.supplies.fields.price').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('price', old('price'), ['class' => 'form-control', 'step' => '0.01', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('price'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('price') }}</span>
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
