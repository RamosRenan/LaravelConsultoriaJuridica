@extends('layouts.modal')

@section('content_header')
    <h1><i class="fa fa-@lang('refectory.stockitems.icon')"></i> @lang('refectory.stockitems.title')</h1>
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
    {{ Form::open(['class' => 'formAjax', 'method' => 'POST', 'route' => ['refectory.stockitems.store']]) }}
    {{ Form::hidden('registry_id', $_GET['id'], ['id' => 'registry_id']) }}
    <div class="row">
        <div class="col-md-8 form-group">
            {{ Form::label('supply_id', __('refectory.stockitems.fields.supply').'*', ['class' => 'control-label']) }}
            {{ Form::select('supply_id', $supplies, old('supply_id'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
            @if($errors->has('supply_id'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('supply_id') }}</span>
                </div>
            @endif
        </div>
        <div class="col-md-4 form-group">
            {{ Form::label('lot', __('refectory.stockitems.fields.lot').'*', ['class' => 'control-label']) }}
            {{ Form::text('lot', old('lot'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
            @if($errors->has('lot'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('lot') }}</span>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 form-group">
            {{ Form::label('expiration', __('refectory.stockitems.fields.expiration').'*', ['class' => 'control-label']) }}
            {{ Form::date('expiration', old('expiration'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
            @if($errors->has('expiration'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('expiration') }}</span>
                </div>
            @endif
        </div>
        <div class="col-md-4 form-group">
            {{ Form::label('quantity', __('refectory.stockitems.fields.quantity').'*', ['class' => 'control-label']) }}
            {{ Form::number('quantity', old('quantity'), ['class' => 'form-control', 'step' => '0.01', 'placeholder' => '', 'required' => '']) }}
            @if($errors->has('quantity'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('quantity') }}</span>
                </div>
            @endif
        </div>
        <div class="col-md-4 form-group">
            {{ Form::label('price', __('refectory.stockitems.fields.price').'*', ['class' => 'control-label']) }}
            {{ Form::number('price', old('price'), ['class' => 'form-control', 'step' => '0.01', 'placeholder' => '', 'required' => '']) }}
            @if($errors->has('price'))
                <div class="form-group has-error">
                    <span class="help-block">{{ $errors->first('price') }}</span>
                </div>
            @endif
        </div>
    </div>
    {{ Form::button(__('global.app_create'), ['type' => "submit", 'class' => 'btn btn-primary float-right']) }}
    {{ Form::close() }}
@stop
