@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-tachometer-alt"></i> @lang('dashboard.title')</h1>
@stop

@section('css') 
<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
    #sortable li { padding: 5px; float: left; text-align: center; }
    #sortable li div { display: block; height: 80px; }
</style>
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

            $( "#sortable" ).sortable({
                opacity: 0.4, 
                cursor: 'move',
                update: function( event, ui ) {
                    $.ajax({
                        url: '{{ route("home.dashboard.updateList") }}',
                        type: 'get',
                        data: 'q='+$(this).sortable('toArray').toString(),
                        traditional: true,
                        success: function (data) {
                        },
                    });
                }
            });
            $( "#sortable" ).disableSelection();
        });
    </script>
@stop

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-10">
                {{ Form::open(['method' => 'POST', 'route' => ['home.dashboard.store']]) }}
                    {{ Form::select('dashboard_id', $items, old('dashboard_id'), ['class' => 'form-control select2', 'required' => '']) }}
                </div>
                <div class="col-md-2">
                    {{ Form::submit(trans('global.app_create'), ['class' => 'btn btn-success btn-block']) }}
                {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
            @if (count($authorizedWidgets) > 0)
                <h4 class="text-center">@lang('dashboard.msg.authorized_widgets')</h4>
                <ul id="sortable">
                    @foreach ($authorizedWidgets as $item)
                    <li id="{{$item->id}}" style="width: {{100 * $item->grid / 12}}%">
                        <div class="btn btn-primary align-middle">
                            <span class="fa fa-arrows-alt float-left"></span>
                            {{ Form::open([
                                'id' => 'deleteItem'.$item->id,
                                'class' => 'float-right',
                                'method' => 'DELETE',
                                'route' => ['home.dashboard.destroy', $item->id]
                            ]) }}
                            {{ Form::button('<i class="fa fa-trash"></i> ', ['type' => 'button', 'data-form' => 'deleteItem'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem']) }}
                            {{ Form::close() }}
                            <b>{{$item->name}}</b><br />
                            {{$item->title}}
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="col-md-12">
                @if (count($unauthorizedWidgets) > 0)
                <h4 class="text-center">@lang('dashboard.msg.unauthorized_widgets')</h4>
                <ul id="notsortable">
                    @foreach ($unauthorizedWidgets as $item)
                    <li class="btn btn-warning btn-block align-middle">
                        {{ Form::open([
                            'id' => 'deleteItem'.$item->id,
                            'class' => 'float-right',
                            'method' => 'DELETE',
                            'route' => ['home.dashboard.destroy', $item->id]
                        ]) }}
                        {{ Form::button('<i class="fa fa-trash"></i> ', ['type' => 'button', 'data-form' => 'deleteItem'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem']) }}
                        {{ Form::close() }}
                        <b>{{$item->name}}</b><br />
                        {{$item->title}}
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
        <div class="card-footer text-center">
        </div>
    </div>
@endsection
