@extends('layouts.modal')

@section('content_header')
    <h1><i class="fa fa-list"></i> @lang('legaladvice.registries.fields.procedures')</h1>
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('.formAjax').ajaxForm({
            success:function(data) {
                if(data.code == 'error') {
                    Swal.fire(
                        '{{ __("global.app_error") }}',
                        data.messages,
                        'error'
                    )
                }

                if(data.code == 'success') {
                    Swal.fire(
                        '{{ __("global.app_success") }}',
                        data.messages,
                        'success'
                    )
                    $('#modalBox').modal('hide')
                }
            }
        });
    });
</script>
@stop

@section('content')
    {!! Form::model($item, ['class' => 'formAjax', 'method' => 'PUT', 'route' => ['legaladvice.procedures.update', $_GET['id']]]) !!}
    {!! Form::hidden('registry_id', $_GET['registry_id'], ['id' => 'registry_id']) !!}
    <div class="row">
        <div class="col-md-6 form-group">
            {!! Form::label('document_type', __('legaladvice.registries.fields.document_type').'*', ['class' => 'control-label']) !!}
            {!! Form::select('document_type', $doctypes, old('document_type'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
            @if($errors->has('document_type'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('document_type') }}</span>
                </div>
            @endif
        </div>
        <div class="col-md-6 form-group">
            {!! Form::label('document_number', __('legaladvice.registries.fields.document_number').'*', ['class' => 'control-label']) !!}
            {!! Form::text('document_number', old('document_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
            @if($errors->has('document_number'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('document_number') }}</span>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            {!! Form::label('source', __('legaladvice.registries.fields.source').'*', ['class' => 'control-label']) !!}
            {!! Form::text('source', old('source'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
            @if($errors->has('source'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('source') }}</span>
                </div>
            @endif
        </div>
        <div class="col-md-6 form-group">
            {!! Form::label('date', __('legaladvice.registries.fields.date').'*', ['class' => 'control-label']) !!}
            {!! Form::date('date', old('date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
            @if($errors->has('date'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('date') }}</span>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 form-group">
            {!! Form::label('files', __('legaladvice.registries.fields.files'), ['class' => 'control-label']) !!}<br />
            {!! Form::select('files[]', $files, old('files'), ['style' => 'width: 100%;', 'class' => 'form-control select2', 'multiple' => '']) !!}
            @if($errors->has('files'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('files') }}</span>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 form-group">
            {!! Form::label('subject', __('legaladvice.registries.fields.subject'), ['class' => 'control-label']) !!}
            {!! Form::textarea('subject', old('subject'), ['rows' => 3, 'class' => 'form-control', 'placeholder' => '']) !!}
            @if($errors->has('subject'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('subject') }}</span>
                </div>
            @endif
        </div>
    </div>
    {!! Form::button(__('global.app_edit'), ['type' => "submit", 'class' => 'btn btn-primary float-right']) !!}
    {!! Form::close() !!}
@stop
