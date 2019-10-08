@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-list"></i> @lang('legaladvice.registries.title')</h1>
@stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['legaladvice.registries.store']]) }}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    {{ Form::label('protocol', __('legaladvice.registries.fields.protocol').'*', ['class' => 'control-label']) }}
                    {{ Form::text('protocol', old('protocol'), ['class' => 'form-control', 'data-inputmask' => '"mask": "99.999.999-9"', 'data-mask' => '', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('protocol'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('protocol') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('document_type', __('legaladvice.registries.fields.document_type').'*', ['class' => 'control-label']) }}
                    {{ Form::select('document_type', $doctypes, old('document_type'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('document_type'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('document_type') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('document_number', __('legaladvice.registries.fields.document_number').'*', ['class' => 'control-label']) }}
                    {{ Form::text('document_number', old('document_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('document_number'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('document_number') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-5 form-group">
                    {{ Form::label('source', __('legaladvice.registries.fields.source').'*', ['class' => 'control-label']) }}
                    {{ Form::text('source', old('source'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('source'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('source') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    {{ Form::label('status', __('legaladvice.registries.fields.status'), ['class' => 'control-label']) }}
                    {{ Form::select('status', $statuses, old('status'), ['class' => 'form-control', 'placeholder' => '']) }}
                    @if($errors->has('status'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('status') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('priority', __('legaladvice.registries.fields.priority').'*', ['class' => 'control-label']) }}
                    {{ Form::select('priority', $priorities, old('priority'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('priority'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('priority') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('place', __('legaladvice.registries.fields.place'), ['class' => 'control-label']) }}
                    {{ Form::select('place', $places, old('place'), ['class' => 'form-control', 'placeholder' => '']) }}
                    @if($errors->has('place'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('place') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-5 form-group">
                    {{ Form::label('interested', __('legaladvice.registries.fields.interested').'*', ['class' => 'control-label']) }}
                    {{ Form::text('interested', old('interested'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('interested'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('interested') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 form-group">
                    {{ Form::label('date_in', __('legaladvice.registries.fields.date_in').'*', ['class' => 'control-label']) }}
                    {{ Form::date('date_in', old('date_in'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('date_in'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('date_in') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('deadline', __('legaladvice.registries.fields.deadline').'*', ['class' => 'control-label']) }}
                    {{ Form::date('deadline', old('deadline'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('deadline'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('deadline') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('date_out', __('legaladvice.registries.fields.date_out'), ['class' => 'control-label']) }}
                    {{ Form::date('date_out', old('date_out'), ['class' => 'form-control', 'placeholder' => '']) }}
                    @if($errors->has('date_out'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('date_out') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('date_return', __('legaladvice.registries.fields.date_return'), ['class' => 'control-label']) }}
                    {{ Form::date('date_return', old('date_return'), ['class' => 'form-control', 'placeholder' => '']) }}
                    @if($errors->has('date_return'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('date_return') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('subject', __('legaladvice.registries.fields.subject').'*', ['class' => 'control-label']) }}
                    {{ Form::textarea('subject', old('subject'), ['class' => 'form-control', 'rows' => 4, 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('subject'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('subject') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(trans('global.app_create'), ['class' => 'btn btn-success']) }}
            {{ Form::close() }}
        </div>
    </div>
@stop
