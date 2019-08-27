@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-bars"></i> @lang('menu.title')</h1>
@stop

@section('css') 
<link rel="stylesheet" href="{{ asset('/dist/plugins/nestable/jquery.nestable.css') }}">
@stop

@section('js') 
<script>
    $(document).ready(function() {
        loadCalls = function() {
            $('#nestable').load('{{ route('admin.menuitem.index') }}?id={{ $id }}');
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
{!! Form::model($menu, ['method' => 'PUT', 'route' => ['admin.menu.update', $menu->id]]) !!}
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('menu.edit_menu')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    {!! Form::label('name', __('menu.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {!! Form::label('call', __('menu.fields.call').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('call', old('call'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    @if($errors->has('call'))
                        <span class="help-block">{{ $errors->first('call') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {!! Form::button('<i class="fa fa-edit"></i> ' . trans('global.app_edit'), ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
        </div>
    </div>
{!! Form::close() !!}

<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">@lang('menu.edit_menu_items')</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalBox" data-url="{{ route('admin.menuitem.create') }}?id={{ $id }}"><i class="fa fa-plus"></i> @lang('global.app_create')</button>
            </div>
            <div class="col-md-6 form-group text-right">
                <div id="nestable-menu">
                    <button type="button" class="btn btn-primary btn-sm" data-action="expand-all"><i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" data-action="collapse-all"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="dd" id="nestable"></div>
    </div>
    <div class="card-footer">
    </div>
</div>    
@stop