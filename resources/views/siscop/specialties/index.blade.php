@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-stethoscope"></i> @lang('global.dentist.specialties.title') <small>@lang('global.app_list')</small></h1>
@stop

@section('js') 
    <script>
        var route = '{{ route('dentist.specialties.mass_destroy') }}';
        var msg   = '{{ trans("global.app_are_you_sure") }}';
    </script>
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-8">
                    <p>
                        <a href="{{ route('dentist.specialties.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('global.app_create')</a>
                    </p>
                </div>
                <div class="col-md-4">
                    {!! Form::open(['style' => 'display: inline-block;', 'method' => 'GET', 'route' => ['dentist.specialties.index']]) !!}
                    <div class="input-group pull-right">
                        {!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => __('global.app_search'), 'required' => '']) !!}
                        <span class="input-group-btn">
                        {!! Form::button('<i class="fa fa-search"></i>', array('type' => 'submit', 'class' => 'btn btn-info btn-flat')) !!}
                        </span>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <th>@lang('global.dentist.specialties.fields.name')</th>
                        <th>@lang('global.dentist.specialties.fields.description')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($items) > 0)
                        @foreach ($items as $item)
                            <tr data-entry-id="{{ $item->id }}">
                                <td style="text-align:center;"><input type="checkbox" name="ids[]" value="{{ $item->id }}" /></td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>
                                    <a href="{{ route('dentist.specialties.edit',[$item->id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> @lang('global.app_edit')</a>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['dentist.specialties.destroy', $item->id])) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i> ' . trans('global.app_delete'), array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </td>

                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="9">
                                    <p>
                                        <button class="btn btn-danger massDelete"><i class="fa fa-trash"></i> @lang('global.app_delete_selected')</button>
                                    </p>
                                </td>
                            </tr>
                    @else
                            <tr>
                                <td colspan="9"><p class='text-center'>@lang('global.app_no_entries_in_table')</p></td>
                            </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="panel-footer text-center">
            {!! $items->links() !!}
        </div>
    </div>
@endsection
