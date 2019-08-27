@extends('layouts.modal')

@section('content_header')
    <h1><i class="fa fa-list"></i> @lang('menu.edit_menu_item')</h1>
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
                    );
                }

                if(data.code == 'success') {
                    if (data.messages) {
                        Swal.fire(
                            '{{ __("global.app_success") }}',
                            data.messages,
                            'success'
                        );
                    }
                    $('#modalBox').modal('hide')
                }
            }
        });

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
                        $('#formDeleteAjax').submit();
                    }
                });
            });
        });
    });
</script>
@stop

@section('content')
{{ Form::model($item, ['class' => 'formAjax', 'method' => 'PUT', 'route' => ['admin.menuitem.update', $id]]) }}
    <div class="row">
        <div class="col-md-4 form-group">
            {{ Form::label('parent_id', __('menu.fields.parent').'*', ['class' => 'control-label']) }}
            {{ Form::select('parent_id', $menuParent, old('parent_id'), ['class' => 'form-control', 'required' => '']) }}
            @if($errors->has('parent_id'))
                <span class="text-danger">{{ $errors->first('parent_id') }}</span>
            @endif
        </div>
        <div class="col-md-4 form-group">
            {{ Form::label('title', __('menu.fields.title').'*', ['class' => 'control-label']) }}
            {{ Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
            @if($errors->has('title'))
                <span class="text-danger">{{ $errors->first('title') }}</span>
            @endif
        </div>
        <div class="col-md-4 form-group">
            {{ Form::label('permission', __('menu.fields.permission'), ['class' => 'control-label']) }}<br />
            {{ Form::select('permission', $permissions, old('permission'), ['style' => 'width: 100%', 'class' => 'form-control select2', 'placeholder' => '']) }}
            @if($errors->has('permission'))
                <span class="text-danger">{{ $errors->first('permission') }}</span>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 form-group">
            {{ Form::label('url', __('menu.fields.url'), ['class' => 'control-label']) }}
            {{ Form::text('url', old('url'), ['class' => 'form-control', 'placeholder' => '']) }}
            @if($errors->has('url'))
                <span class="text-danger">{{ $errors->first('url') }}</span>
            @endif
        </div>
        <div class="col-md-4 form-group">
            {{ Form::label('route', __('menu.fields.route'), ['class' => 'control-label']) }}<br />
            {{ Form::select('route', $routeList, old('route'), ['style' => 'width: 100%', 'class' => 'form-control select2', 'placeholder' => '']) }}
            @if($errors->has('route'))
                <span class="text-danger">{{ $errors->first('route') }}</span>
            @endif
        </div>
        <div class="col-md-2 form-group">
            {{ Form::label('icon', __('menu.fields.icon'), ['class' => 'control-label']) }}
            {{ Form::text('icon', old('icon'), ['class' => 'form-control', 'placeholder' => '']) }}
            @if($errors->has('icon'))
                <span class="text-danger">{{ $errors->first('icon') }}</span>
            @endif
        </div>
        <div class="col-md-2 form-group">
            {{ Form::label('color', __('menu.fields.color'), ['class' => 'control-label']) }}
            {{ Form::select('color', $colors, old('color'), ['class' => 'form-control', 'placeholder' => '']) }}
            @if($errors->has('color'))
                <span class="text-danger">{{ $errors->first('color') }}</span>
            @endif
        </div>
    </div>
    {{ Form::button('<i class="fa fa-trash"></i> ' . __('global.app_delete'), ['type' => 'button', 'class' => 'btn btn-sm btn-danger float-left deleteItem']) }}
    {{ Form::button('<i class="fa fa-pen"></i> ' .__('global.app_edit'), ['type' => "submit", 'class' => 'btn btn-primary btn-sm float-right']) }}
{{ Form::close() }}

{{ Form::open([
    'id' => 'formDeleteAjax',
    'class' => 'formAjax',
    'method' => 'DELETE',
    'route' => ['admin.menuitem.destroy', $id]
]) }}

@stop
