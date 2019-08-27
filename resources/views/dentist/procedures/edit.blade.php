@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-procedures"></i> @lang('dentist.procedures.title')</h1>
@stop

@section('js') 
<script>
    $(function() {
        $('.deleteItem').click(function() {
            Swal.fire({
                title: '{{ __("global.app_are_you_sure") }}',
                text: '{{ __("global.app_that_wont_be_undone") }}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '{{ __("global.app_cancel") }}',
                confirmButtonText: '{{ __("global.app_confirm") }}'
            }).then((result) => {
                if (result.value) {
                    $('#' + $(this).attr("data-form")).submit();
                }
            });
        });
    });
</script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4 form-group">
        {{ Form::model($procedure, ['method' => 'PUT', 'route' => ['dentist.procedures.update', $id]]) }}
            {{ Form::hidden('mode', 'edit_procedure_name') }}
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            {{ Form::label('name', __('dentist.procedures.fields.name').'*', ['class' => 'control-label']) }}
                            {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    {{ Form::button('<i class="fa fa-edit"></i> ' . __('global.app_edit'), ['type' => 'submit', 'class' => 'btn btn-sm btn-primary']) }}
                </div>
            </div>
        {{ Form::close() }}
        </div>
        <div class="col-md-8 form-group">
        {{ Form::open(['method' => 'POST', 'route' => ['dentist.procedures.store']]) }}
            {{ Form::hidden('procedure_id', $id) }}
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            {{ Form::label('price', __('dentist.procedures.fields.price'), ['class' => 'control-label']) }}
                            {{ Form::number('price', old('price'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('price'))
                                <span class="text-danger">{{ $errors->first('price') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 form-group">
                            {{ Form::label('date', __('dentist.procedures.fields.date'), ['class' => 'control-label']) }}
                            {{ Form::date('date', old('date'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('date'))
                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    {{ Form::button('<i class="fa fa-plus"></i> ' . __('global.app_create'), ['type' => 'submit', 'class' => 'btn btn-sm btn-success']) }}
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>

    <div class="card card-default">
        <div class="card-body table-responsive p-0">
            @if (count($prices) > 0)
            <table class="table table-head-fixed table-hover">
                <thead>
                    <tr>
                        <th>@lang('dentist.procedures.fields.price')</th>
                        <th>@lang('dentist.procedures.fields.date')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($prices as $item)
                {{ Form::model($item, ['id' => 'editItem_'. $item->id, 'method' => 'PUT', 'route' => ['dentist.procedures.update', $id]]) }}
                    {{ Form::hidden('mode', 'edit_procedure_price') }}
                    {{ Form::hidden('item_id', $item->id) }}
                    <tr data-entry-id="{{ $item->id }}">
                        <td>
                            {{ Form::number('price', old('price'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('price'))
                                <span class="text-danger">{{ $errors->first('price') }}</span>
                            @endif
                        </td>
                        <td>
                        {{ Form::date('date', old('date'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('date'))
                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            @endif
                        </td>
                        <td class="align-middle text-right">
                            <div class="btn-group">
                                {{ Form::button('<i class="fa fa-edit"></i> ' . __('global.app_edit'), ['id' => "buttonEdit",'onClick' => '$("#editItem_'.$item->id.'").submit();',  'class' => 'btn btn-sm btn-primary']) }}
                                {{ Form::button('<i class="fa fa-trash"></i> ' . __('global.app_delete'), ['type' => 'button', 'data-form' => 'deleteItem'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem']) }}
                            </div>
                        </td>
                    </tr>
                {{ Form::close() }}

                {{ Form::open(array(
                    'id' => "deleteItem".$item->id,
                    'style' => 'display: inline-block;',
                    'method' => 'DELETE',
                    'route' => ['dentist.procedures.destroy', $id])) }}

                    {{ Form::hidden('mode', 'delete_procedure_price') }}
                    {{ Form::hidden('item_id', $item->id) }}

                {{ Form::close() }}
                @endforeach
                </tbody>
            </table>
            @else
            <div class="m-3">
                @lang('global.app_no_entries_in_table')
            </div>
            @endif
        </div>
    </div>
@stop
