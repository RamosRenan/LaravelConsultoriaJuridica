@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-list"></i> @lang('global.dentist.supplies_items.title') <small>@lang('global.app_create')</small></h1>
    @stop

@section('js')
<script>
    $(function () {
        $('.listOfSupplies').autoComplete();
        $('.listOfSupplies').on('autocomplete.select', function (event, item) {
            route = '{{ route('dentist.supplies.edit', '%id') }}';
            $(location).attr('href', route.replace( '%id', item.value ));

            return false;
        });

        $('.listOfSupplies').keypress(function (e) {
            var code = null;
            code = (e.keyCode ? e.keyCode : e.which);                
            return (code == 13) ? false : true;
        });
    });
</script>
@stop

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['dentist.supplies.store'], 'onsubmit' => 'this.preventDefault();']) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    {!! Form::label('name', __('global.dentist.supplies.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), [
                        'class' => 'form-control listOfSupplies', 
                        'data-url' => route ("dentist.supplies.list"),
                        'data-noresults-text' => __('global.app_no_results'),
                        'autocomplete' => 'off', 
                        'placeholder' => '', 
                        'required' => ''
                    ]) !!}
                    @if($errors->has('name'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-footer">
            {!! Form::submit(trans('global.app_create'), ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
