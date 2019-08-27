@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-suitcase"></i> @lang('roles.title')</h1>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $(".selectAll").click(function() {
            var className = this.getAttribute("data-class");
            var setCheck = this.checked;

            $('.' + className).each(function () { 
                $(this).prop('checked', setCheck);
            });
        });
    });
</script>
@stop

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_edit')</h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="roles-mode-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="roles-mode-default-tab" data-toggle="pill" href="#roles-mode-default" role="tab" aria-controls="roles-mode-default" aria-selected="true">@lang('roles.easymode')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="roles-mode-advanced-tab" data-toggle="pill" href="#roles-mode-advanced" role="tab" aria-controls="roles-mode-advanced" aria-selected="false">@lang('roles.advancedmode')</a>
                </li>
            </ul>
            <div class="tab-content" id="roles-mode-tabContent">
                <div class="tab-pane fade show active" id="roles-mode-default" role="tabpanel" aria-labelledby="roles-mode-default-tab">
                {{ Form::model($item, ['method' => 'PUT', 'route' => [$routeURL, $item->id]]) }}
                    {{ Form::hidden('mode', 'default') }}
                    <div class="row">
                        <div class="col-md-12 form-group">
                            {{ Form::label('name', __('roles.fields.name').'*', ['class' => 'control-label']) }}
                            {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            {{ Form::label('permission', __('roles.fields.permission'), ['class' => 'control-label']) }}
                            {{ Form::select('permission[]', $permissions, old('permission') ? old('permission') : $myPermissions, ['style' => 'width: 100%;', 'class' => 'form-control select2', 'multiple' => 'multiple']) }}
                            @if($errors->has('permission'))
                                <span class="text-danger">{{ $errors->first('permission') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        {{ Form::submit(__('global.app_edit'), ['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
                </div>
                <div class="tab-pane fade" id="roles-mode-advanced" role="tabpanel" aria-labelledby="roles-mode-advanced-tab">
                {{ Form::model($item, ['method' => 'PUT', 'route' => [$routeURL, $item->id]]) }}
                    {{ Form::hidden('mode', 'advanced') }}
                    <div class="row">
                        <div class="col-md-12 form-group">
                            {{ Form::label('name', __('roles.fields.name').'*', ['class' => 'control-label']) }}
                            {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('name'))
                                <div class="form-group has-error">
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            @foreach ($routes as $module => $controllers)
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>{{$module}}</h4>
                                </div>
                                <div class="card-body">
                                @foreach ($controllers as $controller => $actions)
                                    <h5>
                                        <label>
                                            <div class="checkbox icheck-primary d-inline">
                                                {{ Form::checkbox('', true, false, ['id' => 'select-all'.$module.$controller, 'class' => 'selectAll', 'data-class' => $module.$controller]) }}
                                                {{ Form::label('select-all'.$module.$controller, '&nbsp;') }}
                                            </div>
                                            {{$controller}}
                                        </label>
                                    </h5>
                                    @foreach ($actions as $name => $action)
                                    <label class="ml-5">
                                        <div class="checkbox icheck-primary d-inline">
                                            {{ Form::checkbox('permission[' .$module. '][]', $action, in_array($action, $myPermissions) ? true : false, ['class' => $module.$controller, 'id' => 'selectId'.$module.$controller.$name]) }}
                                            {{ Form::label('selectId'.$module.$controller.$name, '&nbsp;') }}
                                        </div>
                                        {{$name}}
                                    </label>
                                    @endforeach
                                    <hr />
                                @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-right">
                        {{ Form::submit(__('global.app_edit'), ['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
        </div>
    </div>
@stop