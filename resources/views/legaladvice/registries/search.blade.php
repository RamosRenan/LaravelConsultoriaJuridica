@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-list"></i> @lang('legaladvice.registries.title')</h1>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('[data-mask]').inputmask()
    });
</script>
@stop

@section('content')
{{ Form::model($form, ['method' => 'GET', 'route' => ['legaladvice.registries.search']]) }}
    {{ Form::hidden('dosearch', true) }}
    <div class="card card-default">
        <div class="card-header">
            <a data-toggle="collapse" href="#collapseOne">
                <h3 class="card-title">@lang('global.app_search')</h3>
            </a>
        </div>

        <div id="collapseOne" @if (count($items) > 0) class="panel-collapse collapse in" @endif >
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 form-group">
                        {{ Form::label('protocol', __('legaladvice.registries.fields.protocol'), ['class' => 'control-label']) }}
                        {{ Form::text('protocol', old('protocol'), ['class' => 'form-control', 'data-inputmask' => '"mask": "99.999.999-9"', 'data-mask' => '', 'placeholder' => '']) }}
                    </div>
                    <div class="col-md-2 form-group">
                        {{ Form::label('document_type', __('legaladvice.registries.fields.document_type'), ['class' => 'control-label']) }}<br />
                        {{ Form::select('document_type[]', $doctypes, old('document_type'), ['style' => 'width: 100%', 'class' => 'form-control select2', 'multiple' => '']) }}
                    </div>
                    <div class="col-md-2 form-group">
                        {{ Form::label('document_number', __('legaladvice.registries.fields.document_number'), ['class' => 'control-label']) }}
                        {{ Form::text('document_number', old('document_number'), ['class' => 'form-control', 'placeholder' => '']) }}
                    </div>
                    <div class="col-md-5 form-group">
                        {{ Form::label('source', __('legaladvice.registries.fields.source'), ['class' => 'control-label']) }}
                        {{ Form::text('source', old('source'), ['class' => 'form-control', 'placeholder' => '']) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group">
                        {{ Form::label('status', __('legaladvice.registries.fields.status'), ['class' => 'control-label']) }}<br />
                        {{ Form::select('status[]', $statuses, old('status'), ['style' => 'width: 100%', 'class' => 'form-control select2', 'multiple' => '']) }}
                    </div>
                    <div class="col-md-3 form-group">
                        {{ Form::label('priority', __('legaladvice.registries.fields.priority'), ['class' => 'control-label']) }}<br />
                        {{ Form::select('priority[]', $priorities, old('priority'), ['style' => 'width: 100%', 'class' => 'form-control select2', 'multiple' => '']) }}
                    </div>
                    <div class="col-md-7 form-group">
                        {{ Form::label('interested', __('legaladvice.registries.fields.interested'), ['class' => 'control-label']) }}
                        {{ Form::text('interested', old('interested'), ['class' => 'form-control', 'placeholder' => '']) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        {{ Form::label('date_in', __('legaladvice.registries.fields.date_in'), ['class' => 'control-label']) }}
                        <div class="row">
                            <div class="col-md-3 form-group">
                                {{ Form::select('date_in_condition', $conditions, old('date_in_condition'), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-md-9 form-group">
                                {{ Form::date('date_in', old('date_in'), ['class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 form-group">
                        {{ Form::label('deadline', __('legaladvice.registries.fields.deadline'), ['class' => 'control-label']) }}
                        <div class="row">
                            <div class="col-md-3 form-group">
                                {{ Form::select('deadline_condition', $conditions, old('deadline_condition'), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-md-9 form-group">
                                {{ Form::date('deadline', old('deadline'), ['class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 form-group">
                        {{ Form::label('date_out', __('legaladvice.registries.fields.date_out'), ['class' => 'control-label']) }}
                        <div class="row">
                            <div class="col-md-3 form-group">
                                {{ Form::select('date_out_condition', $conditions, old('date_out_condition'), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-md-9 form-group">
                                {{ Form::date('date_out', old('date_out'), ['class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 form-group">
                        {{ Form::label('date_return', __('legaladvice.registries.fields.date_return'), ['class' => 'control-label']) }}
                        <div class="row">
                            <div class="col-md-3 form-group">
                                {{ Form::select('date_return_condition', $conditions, old('date_return_condition'), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-md-9 form-group">
                                {{ Form::date('date_return', old('date_return'), ['class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {{ Form::label('subject', __('legaladvice.registries.fields.subject'), ['class' => 'control-label']) }}
                        {{ Form::text('subject', old('subject'), ['class' => 'form-control', 'placeholder' => '']) }}
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                {{ Form::submit(__('global.app_search'), ['class' => 'btn btn-primary btn-block']) }}
            </div>
        </div>
    </div>
    @if (count($items) > 0)
    <div class="text-center">
        <h3>@lang('global.app_search_results')</h3>
    </div>
    <div class="card card-default">
        <div class="card-body table-responsive p-0">
            <table class="table table-head-fixed table-hover">
                <thead>
                    <tr>
                        <th>@lang('legaladvice.registries.fields.protocol')</th>
                        <th>@lang('legaladvice.registries.fields.interested')</th>
                        <th>@lang('legaladvice.registries.fields.subject')</th>
                        <th>@lang('legaladvice.registries.fields.date_in')</th>
                        <th>@lang('legaladvice.registries.fields.deadline')</th>
                        <th><i class="fa fa-file"></i></th>
                        <th><i class="fa fa-share"></i></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                <tr data-entry-id="{{ $item->id }}">
                    <td class="align-middle">{{ $item->protocol }}</td>
                    <td class="align-middle">{{ $item->interested }}</td>
                    <td class="align-middle">{{ $item->subject }}</td>
                    <td class="align-middle">{{ $item->date_in }}</td>
                    <td class="align-middle">{{ $item->deadline }}</td>
                    <td class="align-middle">{{ $item->files }}</td>
                    <td class="align-middle">{{ $item->procedures }}</td>
                    <td class="align-middle text-right">
                        <div class="btn-group">
                            <a href="{{ route('legaladvice.registries.show',[$item->id]) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> @lang('global.app_show')</a>
                            <a href="{{ route('legaladvice.registries.edit',[$item->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> @lang('global.app_edit')</a>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-12">
                    <div class="float-right">
                    {{ $items->appends($form)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
{{ Form::close() }}
@endsection