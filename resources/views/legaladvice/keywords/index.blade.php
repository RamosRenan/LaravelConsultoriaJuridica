@extends('layouts.app')

@section('content_header')
    <h1> Defina palavras chaves</h1>
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

    $( "table tbody" ).sortable({
        opacity: 0.4, 
        cursor: 'move',
        connectWith: ".item-order",
        handle: '.order-handle',
        update: function( event, ui ) {
            $.ajax({
                url: '{{ route("legaladvice.priorities.order") }}',
                type: 'get',
                data: 'q='+$(this).sortable('toArray').toString(),
                traditional: true,
                success: function (data) {
                },
            });
        }
    }).disableSelection();

    var route = '{{ route('legaladvice.priorities.mass_destroy') }}';
</script>
@stop

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('legaladvice.keywords.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Inserir </a>
                </div>
                <div class="col-md-6">
                    <div class="float-sm-right">
                         
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
                        <th>@lang('legaladvice.priorities.fields.name')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr id="{{ $item->id }}" data-entry-id="{{ $item->id }}" class="item-order">
                        <td class="align-middle text-center">
                            <div class="checkbox icheck-primary">
                                {{ Form::checkbox('ids[]', $item->id, false, ['id' => 'selectId'.$item->id]) }}
                                {{ Form::label('selectId'.$item->id, '&nbsp;') }}
                            </div>
                        </td>
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle text-right">
                            {{ Form::open(array(
                                'id' => 'deleteItem'.$item->id,
                                'method' => 'DELETE',
                                'route' => ['legaladvice.priorities.destroy', $item->id])) }}
                            {{ Form::close() }}
                            <a href="#" class="btn btn-sm btn-secondary order-handle"><i class="fa fa-arrows-alt"></i></a>
                            <div class="btn-group">
                                <a href="{{ route('legaladvice.priorities.edit',[$item->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> @lang('global.app_edit')</a>
                                {{ Form::button('<i class="fa fa-trash"></i> ' . __('global.app_delete'), ['type' => 'button', 'data-form' => 'deleteItem'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem']) }}
                            </div>
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
                    @if (count($items) > 0)
                        <button class="btn btn-danger massDelete"><i class="fa fa-trash"></i> @lang('global.app_delete_selected')</button>
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="float-right">
                     
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
