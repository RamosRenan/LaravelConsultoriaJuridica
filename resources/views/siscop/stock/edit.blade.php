@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-shopping-cart"></i> @lang('global.dentist.supplies_stock.title') <small>@lang('global.app_edit')</small></h1>
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>{{ $supply->name }}</h4>
        </div>
        <div class="panel-body">
            <div class="panel-body table-responsive">
                <table class="table table-bordered table-striped dt-select">
                    <thead>
                        <tr>
                            <th>@lang('global.dentist.supplies.fields.unit')</th>
                            <th>@lang('global.dentist.supplies.fields.date')</th>
                            <th>@lang('global.dentist.supplies.fields.lot')</th>
                            <th>@lang('global.dentist.supplies.fields.quantity')</th>
                            <th>@lang('global.dentist.supplies.fields.price')</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($stock) > 0)
                            @foreach ($stock as $item)
                                {!! Form::model($item, ['id' => 'editItem_'. $item->id, 'method' => 'PUT', 'route' => ['dentist.stock.update', $id]]) !!}
                                {!! Form::hidden('item_id', $item->id) !!}
                                <tr data-entry-id="{{ $item->id }}">
                                    <td>
                                        {{ $units->where('id', $item->unit_id)->pluck('name')->first() }}
                                    </td>
                                    <td>
                                        {!! Form::date('date', old('date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                        @if($errors->has('date'))
                                            <div class="form-group has-error">
                                                <span class="help-block">{{ $errors->first('date') }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {!! Form::number('lot', old('lot'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                        @if($errors->has('lot'))
                                            <div class="form-group has-error">
                                                <span class="help-block">{{ $errors->first('lot') }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {!! Form::number('quantity', old('quantity'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                        @if($errors->has('quantity'))
                                            <div class="form-group has-error">
                                                <span class="help-block">{{ $errors->first('quantity') }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {!! Form::number('price', old('price'), ['class' => 'form-control', 'step' => '0.01', 'placeholder' => '', 'required' => '']) !!}
                                        @if($errors->has('price'))
                                            <div class="form-group has-error">
                                                <span class="help-block">{{ $errors->first('price') }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {!! Form::button('<i class="fa fa-edit"></i> ' . trans('global.app_edit'), ['id' => "buttonEdit",'onClick' => '$("#editItem_'.$item->id.'").submit();',  'class' => 'btn btn-primary']) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i> ' . trans('global.app_delete'), ['id' => "buttonEdit",'onClick' => '$("#deleteItem_'.$item->id.'").submit();',  'class' => 'btn btn-danger']) !!}
                                    </td>
                                </tr>
                            {!! Form::close() !!}

                            {!! Form::open(array(
                                'id' => "deleteItem_".$item->id,
                                'style' => 'display: inline-block;',
                                'method' => 'DELETE',
                                'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                'route' => ['dentist.stock.destroy', $id])) !!}

                                {!! Form::hidden('item_id', $item->id) !!}

                            {!! Form::close() !!}
                            @endforeach
                        @else
                                <tr>
                                    <td colspan="9"><p class='text-center'>@lang('global.app_no_entries_in_table')</p></td>
                                </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
        </div>
    </div>
@stop