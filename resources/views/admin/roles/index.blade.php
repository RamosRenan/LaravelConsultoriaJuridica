@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-suitcase"></i> @lang('roles.title')</h1>
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

        var route = '{{ route('admin.roles.mass_destroy') }}';
    </script>
@stop

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    @can('@@ superadmin @@')
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> @lang('global.app_create')</a>
                    @endcan
                    @can('@@ admin @@')
                    <a href="{{ route('manager.roles.create') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> @lang('global.app_create')</a>
                    @endcan
                </div>
                <div class="col-md-6">
                    <div class="float-sm-right">
                        @can('@@ superadmin @@')
                        {{ Form::open(['method' => 'GET', 'route' => ['admin.roles.index']]) }}
                        @endcan
                        @can('@@ admin @@')
                        {{ Form::open(['method' => 'GET', 'route' => ['manager.roles.index']]) }}
                        @endcan
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
                        @can('@@ superadmin @@')
                        <th class="text-center">
                            <div class="checkbox icheck-primary">
                                {{ Form::checkbox('', false, false, ['id' => 'select-all']) }}
                                {{ Form::label('select-all', '&nbsp;') }}
                            </div>
                        </th>
                        @endcan
                        <th>@lang('roles.fields.name')</th>
                        <th>@lang('roles.fields.permission')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                <tr data-entry-id="{{ $item->id }}">
                    @can('@@ superadmin @@')
                    <td class="align-middle" style="text-align:center;">
                        <div class="checkbox icheck-primary">
                            {{ Form::checkbox('ids[]', $item->id, false, ['id' => 'selectId'.$item->id]) }}
                            {{ Form::label('selectId'.$item->id, '&nbsp;') }}
                        </div>
                    </td>
                    @endcan
                    <td class="align-middle">{{ $item->name }}</td>
                    <td class="align-middle">
                        @foreach ($item->permissions()->pluck('name') as $permission)
                            <span class="badge badge-info">{{ $permission }}</span>
                        @endforeach
                    </td>
                    <td class="align-middle">
                        @can('@@ superadmin @@')
                        {{ Form::open(array(
                            'id' => 'deleteItem'.$item->id,
                            'method' => 'DELETE',
                            'route' => ['admin.roles.destroy', $item->id])) }}
                        {{ Form::close() }}

                        <div class="btn-group">
                            <a href="{{ route('admin.roles.edit',[$item->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> @lang('global.app_edit')</a>
                            {{ Form::button('<i class="fa fa-trash"></i> ' . __('global.app_delete'), ['type' => 'button', 'data-form' => 'deleteItem'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem']) }}
                        </div>
                        @endcan
                        @can('@@ admin @@')
                        {{ Form::open(array(
                            'id' => 'deleteItem'.$item->id,
                            'method' => 'DELETE',
                            'route' => ['manager.roles.destroy', $item->id])) }}
                        {{ Form::close() }}

                        <div class="btn-group">
                            <a href="{{ route('manager.roles.edit',[$item->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> @lang('global.app_edit')</a>
                            {{ Form::button('<i class="fa fa-trash"></i> ' . __('global.app_delete'), ['type' => 'button', 'data-form' => 'deleteItem'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem']) }}
                        </div>
                        @endcan
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
            <div class="row">
                <div class="col-md-3">
                    @can('@@ superadmin @@')
                    @if (count($items) > 0)
                    <button class="btn btn-danger massDelete"><i class="fa fa-trash"></i> @lang('global.app_delete_selected')</button>
                    @endif
                    @endcan
                </div>
                <div class="col-md-9">
                    <div class="float-right">
                    {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
