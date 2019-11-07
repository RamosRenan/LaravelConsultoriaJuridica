@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-@lang('refectory.stocks.icon')"></i> @lang('refectory.stocks.title')</h1>
@stop

@section('js') 
<script>
    $(document).ready(function() {
        loadCalls = function() {
            $.ajax("{{ route('legaladvice.procedures.index') }}?id={{ $id }}").done(function(data) {
                $("#stockItemsBox").html(data);
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
    {{ Form::model($item, ['method' => 'PUT', 'route' => ['refectory.stock.update', $id]]) }}
    {{ Form::hidden('supply_id', $id ) }}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_edit')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    {{ Form::label('contract', __('refectory.stocks.fields.contract').'*', ['class' => 'control-label']) }}
                    {{ Form::text('contract', old('contract'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('contract'))
                        <span class="text-danger">{{ $errors->first('lot') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::label('unit_id', __('refectory.stocks.fields.unit').'*', ['class' => 'control-label']) }}
                    {{ Form::select('unit_id', $units, old('unit_id'), ['class' => 'form-control select2', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('unit_id'))
                        <span class="text-danger">{{ $errors->first('unit_id') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('date', __('refectory.stocks.fields.date').'*', ['class' => 'control-label']) }}
                    {{ Form::date('date', old('date'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(trans('global.app_edit'), ['class' => 'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::close() }}
    <div class="card card-default">
        <div class="card-header">
            <button type="button" class="float-left btn btn-success btn-sm" data-toggle="modal" data-target="#modalBox" data-url="{{ route('refectory.stockitems.create') }}?id={{ $id }}"><i class="fa fa-plus"></i> @lang('global.app_create')</button>
        </div>
        <div class="card-body">
            <div id="stockItemsBox"></div>
        </div>
        <div class="card-footer text-right">
        </div>
    </div>
@stop