@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-shopping-cart"></i> @lang('dentist.supplies_stock.title') <small>@lang('global.app_edit')</small></h1>
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
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">{{ $supply->name }}</h3>
        </div>
        <div class="card-body table-responsive p-0">
            @if (count($stock) > 0)
            <table class="table table-head-fixed table-hover">
                <thead>
                    <tr>
                        <th>@lang('dentist.supplies.fields.unit')</th>
                        <th>@lang('dentist.supplies.fields.date')</th>
                        <th>@lang('dentist.supplies.fields.lot')</th>
                        <th>@lang('dentist.supplies.fields.quantity')</th>
                        <th>@lang('dentist.supplies.fields.price')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($stock as $item)
                    {{ Form::model($item, ['id' => 'editItem_'. $item->id, 'method' => 'PUT', 'route' => ['dentist.stock.update', $id]]) }}
                    {{ Form::hidden('item_id', $item->id) }}
                    <tr data-entry-id="{{ $item->id }}">
                        <td>
                            {{ $units->where('id', $item->unit_id)->pluck('name')->first() }}
                        </td>
                        <td>
                            {{ Form::date('date', old('date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('date'))
                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            @endif
                        </td>
                        <td>
                            {{ Form::number('lot', old('lot'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('lot'))
                                <span class="text-danger">{{ $errors->first('lot') }}</span>
                            @endif
                        </td>
                        <td>
                            {{ Form::number('quantity', old('quantity'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('quantity'))
                                <span class="text-danger">{{ $errors->first('quantity') }}</span>
                            @endif
                        </td>
                        <td>
                            {{ Form::number('price', old('price'), ['class' => 'form-control', 'step' => '0.01', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('price'))
                                <span class="text-danger">{{ $errors->first('price') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                {{ Form::button('<i class="fa fa-edit"></i> ' . __('global.app_edit'), ['id' => "buttonEdit",'onClick' => '$("#editItem_'.$item->id.'").submit();',  'class' => 'btn btn-sm btn-primary']) }}
                                {{ Form::button('<i class="fa fa-trash"></i> ' . __('global.app_delete'), ['type' => 'button', 'data-form' => 'deleteItem'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem']) }}
                            </div>
                        </td>
                    </tr>
                {{ Form::close() }}

                {{ Form::open([
                    'id' => "deleteItem".$item->id,
                    'style' => 'display: inline-block;',
                    'method' => 'DELETE',
                    'route' => ['dentist.stock.destroy', $id]
                ]) }}
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