@extends('adminlte::master')

@section('adminlte_css')
<!--
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
-->
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
    <div class="wrapper">
        @if(config('adminlte.layout') == 'top-nav')
            @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
        @else
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                        <strong>{{ auth()->user()->name }}</strong> 
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
@php
/*
                        <a class="dropdown-item" tabindex="-1" href="#">
                            <i class="fa fa-fw fa-user"></i>
                            @lang('global.app_profile_title')
                        </a>
                        <a class="dropdown-item" tabindex="-1" href="#">
                            <i class="fa fa-fw fa-envelope"></i>
                            @lang('global.app_messages_title')
                        </a>
                        <a class="dropdown-item" tabindex="-1" href="#">
                            <i class="fa fa-fw fa-bell"></i>
                            @lang('global.app_notifications_title')
                        </a>
                        <div class="dropdown-divider"></div>
*/
@endphp
                        @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                            <a class="dropdown-item" tabindex="-1" href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                <i class="fa fa-fw fa-power-off"></i> 
                                @lang('adminlte::adminlte.log_out')
                            </a>
                        @else
                            <a class="dropdown-item" tabindex="-1" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
                                <i class="fa fa-fw fa-power-off"></i> @lang('adminlte::adminlte.log_out')
                            </a>
                            <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                @csrf
                                @if(config('adminlte.logout_method'))
                                    {{ method_field(config('adminlte.logout_method')) }}
                                @endif
                            </form>
                        @endif
                    </div>
                </li>
                <li class="nav-item">
                </li>
            </ul>
        </nav>
        @endif

        @if(config('adminlte.layout') != 'top-nav')
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="/" class="brand-link">
                <img src="/img/logopm.png" alt="{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                    </ul>
                </nav>
            </div>
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-check"></i> {{ session('success') }}
                </div>
            @endif 
            @if(Session::has('warning'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-exclamation "></i> {{ session('warning') }}
                </div>
            @endif 
            @if(Session::has('info'))
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-info"></i> {{ session('info') }}
                </div>
            @endif 
            @if( $errors->any() || Session::has('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @if ( Session::has('error') )
                    <i class="icon fa fa-ban"></i> {{ session('info') }}
                    {{ session('error') }}
                @else
                    <ul>
                    @foreach ( $errors->all() as $error )
                        <li> {{ $error }}</li>
                    @endforeach
                    </ul>
                @endif
                </div>
            @endif 
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        @if (@$nobreadcrumbs == false)
                        <div class="col-sm-6">
                            @yield('content_header')
                        </div>
                        <div class="col-sm-6 float-sm-right">
                            <div class="float-sm-right">
                                {{ Breadcrumbs::render('breadcrumb') }}
                            </div>
                        </div>
                        @else
                        <div class="col-sm-12">
                            @yield('content_header')
                        </div>
                        @endif
                    </div>
                </div>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            &nbsp;
            <div class="float-right d-none d-sm-block"><b>Vers??o</b> 1.0.0</div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
