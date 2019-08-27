@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-ambulance"></i> @lang('global.dentist.procedures.title') <small>@lang('global.app_edit')</small></h1>
@stop

@section('content')
    {!! Form::model($procedure, ['method' => 'PUT', 'route' => ['dentist.procedures.update', $id]]) !!}
    {!! Form::hidden('mode', 'edit_procedure_name') !!}
    <div class="row">
        <div class="col-xs-4 form-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            {!! Form::label('name', __('global.dentist.procedures.fields.name').'*', ['class' => 'control-label']) !!}
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                            @if($errors->has('name'))
                                <div class="form-group has-error">
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    {!! Form::submit(trans('global.app_edit'), ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="col-xs-8 form-group">
            {!! Form::open(['method' => 'POST', 'route' => ['dentist.procedures.store']]) !!}
            {!! Form::hidden('procedure_id', $id) !!}
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6 form-group">
                            {!! Form::label('price', __('global.dentist.procedures.fields.price'), ['class' => 'control-label']) !!}
                            {!! Form::number('price', old('price'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                            @if($errors->has('price'))
                                <div class="form-group has-error">
                                    <span class="help-block">{{ $errors->first('price') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-xs-6 form-group">
                            {!! Form::label('date', __('global.dentist.procedures.fields.date'), ['class' => 'control-label']) !!}
                            {!! Form::date('date', old('date'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                            @if($errors->has('date'))
                                <div class="form-group has-error">
                                    <span class="help-block">{{ $errors->first('date') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    {!! Form::submit(trans('global.app_create'), ['class' => 'btn btn-success']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped dt-select">
                <thead>
                    <tr>
                        <th>@lang('global.dentist.procedures.fields.price')</th>
                        <th>@lang('global.dentist.procedures.fields.date')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($prices) > 0)
                        @foreach ($prices as $item)
                            {!! Form::model($item, ['id' => 'editItem_'. $item->id, 'method' => 'PUT', 'route' => ['dentist.procedures.update', $id]]) !!}
                            {!! Form::hidden('mode', 'edit_procedure_price') !!}
                            {!! Form::hidden('item_id', $item->id) !!}
                            <tr data-entry-id="{{ $item->id }}">
                                <td>
                                    {!! Form::number('price', old('price'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    @if($errors->has('price'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('price') }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                {!! Form::date('date', old('date'), ['step' => '0.01', 'class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    @if($errors->has('date'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('date') }}</span>
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
                            'route' => ['dentist.procedures.destroy', $id])) !!}

                            {!! Form::hidden('mode', 'delete_procedure_price') !!}
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
        <div class="panel-footer">
            {!! Form::submit(trans('global.app_edit'), ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
