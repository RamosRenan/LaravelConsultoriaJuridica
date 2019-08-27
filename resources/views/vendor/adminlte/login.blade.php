@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
    </div>
    @if (session()->has('message'))
    <div class="alert alert-warning alert-dismissible text-center">
        {{ session('message') }}
    </div>
    @endif
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>

            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                @csrf
                @if($errors->has('username'))
                    <span class="text-danger">{{ $errors->first('username') }}</span>
                @endif
                <div class="input-group mb-3">
                    <input type="username" name="username" class="form-control" value="{{ old('username') }}" placeholder="{{ trans('adminlte::adminlte.username') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                @if($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                {{ __('adminlte::adminlte.remember_me') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('adminlte::adminlte.sign_in') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
    @yield('js')
@stop
