@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-list"></i> @lang('legaladvice.registries.title')</h1>
@stop

@section('js') 
<script>
    $(document).ready(function() {
        loadCalls = function() {
            $.ajax("{{ route('legaladvice.procedures.index') }}?id={{ $id }}").done(function(data) {
                $("#proceduresBox").html(data);
            });

            $.ajax("{{ route('legaladvice.registries.uploadindex') }}?id={{ $id }}").done(function(data) {
                $("#filesBox").html(data);
            });
        }

        loadCalls();

        $('#modalBox').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var modal = $(this)

            $('#modalBoxContent').load(button.data('url'), function(){
                $('#myModal').modal({ show:true });
            });
        });

        $('#modalBox').on('hide.bs.modal', function (event) {
            loadCalls();
        });
    });
</script>
@stop

@section('content')
    <div class="modal fade" id="modalBox" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body" id="modalBoxContent"></div>
            </div>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_edit')</h3>
        </div>
        <div class="card-body">
            {{ Form::model($registry, ['id' => 'editForm', 'method' => 'PUT', 'route' => ['legaladvice.registries.update', $id]]) }}
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="checkbox icheck-danger d-inline float-left">
                        {{ Form::checkbox('urgent', true, old('urgent'), ['id' => 'urgent']) }}
                        {{ Form::label('urgent', 'URGENTE') }}
                    </div>
                </div>
            </div>
            <div class="row">
		<div class="col-md-3 form-group">
                    {{ Form::label('protocol', __('legaladvice.registries.fields.protocol').'*', ['class' => 'control-label']) }}
                    {{ Form::text('protocol', old('protocol'), ['class' => 'form-control', 'data-inputmask' => '"mask": "99.999.999-9"', 'data-mask' => '', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('protocol'))
                        <span class="text-danger">{{ $errors->first('protocol') }}</span>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('document_type', __('legaladvice.registries.fields.document_type').'*', ['class' => 'control-label']) }}
                    {{ Form::select('document_type', $doctypes, old('document_type'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('document_type'))
                        <span class="text-danger">{{ $errors->first('document_type') }}</span>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('document_number', __('legaladvice.registries.fields.document_number').'*', ['class' => 'control-label']) }}
                    {{ Form::text('document_number', old('document_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('document_number'))
                        <span class="text-danger">{{ $errors->first('document_number') }}</span>
                    @endif
                </div>
                <div class="col-md-5 form-group">
                    {{ Form::label('source', __('legaladvice.registries.fields.source').'*', ['class' => 'control-label']) }}
                    {{ Form::text('source', old('source'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('source'))
                        <span class="text-danger">{{ $errors->first('source') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    {{ Form::label('status', __('legaladvice.registries.fields.status'), ['class' => 'control-label']) }}
                    {{ Form::select('status', $statuses, old('status'), ['class' => 'form-control', 'placeholder' => '']) }}
                    @if($errors->has('status'))
                        <span class="text-danger">{{ $errors->first('status') }}</span>
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
                        <span class="text-danger">{{ $errors->first('interested') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 form-group">
                    {{ Form::label('date_in', __('legaladvice.registries.fields.date_in').'*', ['class' => 'control-label']) }}
                    {{ Form::text('date_in', old('date_in'), ['class' => 'form-control datepicker', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('date_in'))
                        <span class="text-danger">{{ $errors->first('date_in') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('deadline', __('legaladvice.registries.fields.deadline').'*', ['class' => 'control-label']) }}
                    {{ Form::text('deadline', old('deadline'), ['class' => 'form-control datepicker', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('deadline'))
                        <span class="text-danger">{{ $errors->first('deadline') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('date_out', __('legaladvice.registries.fields.date_out'), ['class' => 'control-label']) }}
                    {{ Form::text('date_out', old('date_out'), ['class' => 'form-control datepicker', 'placeholder' => '']) }}
                    @if($errors->has('date_out'))
                        <span class="text-danger">{{ $errors->first('date_out') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('date_return', __('legaladvice.registries.fields.date_return'), ['class' => 'control-label']) }}
                    {{ Form::text('date_return', old('date_return'), ['class' => 'form-control datepicker', 'placeholder' => '']) }}
                    @if($errors->has('date_return'))
                        <span class="text-danger">{{ $errors->first('date_return') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('subject', __('legaladvice.registries.fields.subject'), ['class' => 'control-label']) }}
                    {{ Form::textarea('subject', old('subject'), ['class' => 'form-control', 'rows' => 4, 'placeholder' => '']) }}
                    @if($errors->has('subject'))
                        <span class="text-danger">{{ $errors->first('subject') }}</span>
                    @endif
                </div>
            </div>
            {{ Form::close() }}
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('source_file', __('legaladvice.registries.fields.files') . ' (tamanho máximo por arquivo: ' . ini_get('upload_max_filesize') . 'B)', ['class' => 'control-label']) }}
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalBox" data-url="{{ route('legaladvice.registries.uploadcreate') }}?id={{ $id }}"><i class="fa fa-plus"></i> @lang('global.app_create')</button>
                    <div id="filesBox"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('procedures', __('legaladvice.registries.fields.procedures'), ['class' => 'control-label']) }}
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalBox" data-url="{{ route('legaladvice.procedures.create') }}?id={{ $id }}"><i class="fa fa-plus"></i> @lang('global.app_create')</button>
                    <div id="proceduresBox"></div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::button(__('global.app_edit'), ['onclick' => "event.preventDefault(); document.getElementById('editForm').submit();", 'class' => 'btn btn-primary']) }}
        </div>
    </div>
@stop
