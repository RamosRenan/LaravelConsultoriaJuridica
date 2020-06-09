@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-user-plus"></i> @lang('dentist.patients.title')</h1>
@stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['dentist.patients.store']]) }}

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::label('name', __('dentist.patients.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('rg', __('dentist.dentists.fields.rg').'*', ['class' => 'control-label']) }}
                    {{ Form::text('rg', old('rg'), ['class' => 'form-control', 'data-inputmask' => "'mask': ['9.999.999-9', '99.999.999-9']", 'data-mask' => '', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('rg'))
                        <span class="text-danger">{{ $errors->first('rg') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('cpf', __('dentist.dentists.fields.cpf').'*', ['class' => 'control-label']) }}
                    {{ Form::text('cpf', old('cpf'), ['class' => 'form-control', 'data-inputmask' => "'mask': ['999.999.999-99']", 'data-mask' => '', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('cpf'))
                        <span class="text-danger">{{ $errors->first('cpf') }}</span>
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
