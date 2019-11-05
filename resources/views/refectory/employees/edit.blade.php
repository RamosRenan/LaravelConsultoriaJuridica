@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-@lang('refectory.employees.icon')"></i> @lang('refectory.employees.title')</h1>
@stop

@section('content')
    {{ Form::model($item, ['method' => 'PUT', 'route' => ['refectory.employees.update', $item->id]]) }}

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_edit')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::label('name', __('refectory.employees.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('rg', __('refectory.employees.fields.rg').'*', ['class' => 'control-label']) }}
                    {{ Form::text('rg', old('rg'), ['class' => 'form-control', 'data-inputmask' => "'mask': ['9.999.999-9', '99.999.999-9']", 'data-mask' => '', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('rg'))
                        <span class="text-danger">{{ $errors->first('rg') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('cpf', __('refectory.employees.fields.cpf').'*', ['class' => 'control-label']) }}
                    {{ Form::text('cpf', old('cpf'), ['class' => 'form-control', 'data-inputmask' => "'mask': ['999.999.999-99']", 'data-mask' => '', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('cpf'))
                        <span class="text-danger">{{ $errors->first('cpf') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::label('units', __('refectory.employees.fields.units'), ['class' => 'control-label']) }}
                    {{ Form::select('units[]', $units, old('units') ? old('units') : $myUnits, ['class' => 'form-control select2', 'multiple' => 'multiple']) }}
                    @if($errors->has('units'))
                        <span class="text-danger">{{ $errors->first('units') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::label('specialties', __('refectory.employees.fields.specialty'), ['class' => 'control-label']) }}
                    {{ Form::select('specialties[]', $specialties, old('specialties') ? old('specialties') : $mySpecialties, ['class' => 'form-control select2', 'multiple' => 'multiple']) }}
                    @if($errors->has('specialties'))
                        <span class="text-danger">{{ $errors->first('specialties') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(trans('global.app_edit'), ['class' => 'btn btn-primary']) }}
            {{ Form::close() }}
        </div>
    </div>
@stop
