@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-shopping-cart"></i> @lang('dentist.supplies_stock.title')</h1>
@stop

@section('js') 
<script>
    var route = '{{ route('dentist.stock.mass_destroy') }}';
</script>
@stop

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('dentist.stock.create') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> @lang('global.app_create')</a>
                </div>
                <div class="col-md-6">
                    <div class="float-sm-right">
                        {{ Form::open(['method' => 'GET', 'route' => ['dentist.stock.index']]) }}
                        <div class="input-group input-group-sm">
                            {{ Form::text('search', $search, ['class' => 'form-control', 'placeholder' => __('global.app_search')]) }}
                            <span class="input-group-append">
                                {{ Form::button('<i class="fa fa-search"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-flat']) }}
                            </span>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            @if (count($items) > 0)
            <table class="table table-head-fixed table-hover">
                <thead>
                    <tr>
                        <th class="text-center">
                            <div class="checkbox icheck-primary">
                                {{ Form::checkbox('', false, false, ['id' => 'select-all']) }}
                                {{ Form::label('select-all', '&nbsp;') }}
                            </div>
                        </th>
                        <th>@lang('dentist.supplies.fields.name')</th>
                        <th>@lang('dentist.supplies.fields.unit')</th>
                        <th>@lang('dentist.supplies.fields.last_date')</th>
                        <th>@lang('dentist.supplies.fields.quantity')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr data-entry-id="{{ $item->id }}">
                        <td class="align-middle text-center">
                            <div class="checkbox icheck-primary">
                                {{ Form::checkbox('ids[]', $item->id, false, ['id' => 'selectId'.$item->id]) }}
                                {{ Form::label('selectId'.$item->id, '&nbsp;') }}
                            </div>
                        </td>
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle">{{ $item->unit }}</td>
                        <td class="align-middle">{{ $item->date }}</td>
                        <td class="align-middle">{{ $item->quantity }}</td>
                        <td class="align-middle text-right">
                            <a href="{{ route('dentist.stock.edit',[$item->unit_id.','.$item->id]) }}" class="btn btn-sm btn-warning"><i class="fa fa-eye"></i> @lang('global.app_show')</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div class="m-3">
                @lang('global.app_no_entries_in_table')
            </div>
            @endif
        </div>
        <div class="card-footer">
            <div class="col-md-3">
                @if (count($items) > 0)
                <button class="btn btn-danger massDelete"><i class="fa fa-trash"></i> @lang('global.app_delete_selected')</button>
                @endif
            </div>
            <div class="col-md-9">
                <div class="float-right">
                {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
