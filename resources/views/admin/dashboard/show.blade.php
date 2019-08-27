@extends('adminlte::page')

@section('nobreadcrumbs', true)

@section('content_header')
    <a class="text-muted float-right" href="{{ route('home.dashboard.index') }}">
        <span class="btn btn-md btn-warning">
            <i class="fa fa-cog" style="color:white"></i>
        </span>
    </a>
    <h1>@lang('dashboard.title')</h1>
@stop

@section('content')
    @if (count($items) > 0)
        @foreach ($items as $item)
            @widget($item->name)
        @endforeach
    @else
    <p class='text-center'>@lang('dashboard.msg.empty_dashboard')</p>
    @endif
@stop