@extends('layouts.app')

@section('content_header')
    <h1> Palavras chaves </h1>
@stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['legaladvice.keywords.store']]) }}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('name', 'Nome da palavra-chave', ['class' => 'control-label']) }}
                    {{ Form::text('name', '', ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(__('global.app_create'), ['class' => 'btn btn-success']) }}
        </div>
    </div>
    {{ Form::close() }}
@stop
