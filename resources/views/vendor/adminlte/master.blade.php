<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
@yield('title', config('adminlte.title', 'AdminLTE 2'))
@yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/dist/plugins/fontawesome-free-5.10.1-web/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('/dist/plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('/dist/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('/dist/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('/dist/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('/dist/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('/dist/plugins/select2/css/select2.min.css') }}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{ asset('/dist/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    @yield('adminlte_css')
</head>

<body class="hold-transition @yield('body_class')">
@yield('body')

    <!-- jQuery -->
    <script src="{{ asset('/dist/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI -->
    <script src="{{ asset('/dist/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- jQuery Form -->
    <script src="{{ asset('/dist/plugins/jQueryForm/js/jquery.form.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('/dist/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('/dist/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('/dist/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/dist/plugins/select2/js/i18n/pt-BR.js') }}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{ asset('/dist/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('/dist/plugins/inputmask/jquery.inputmask.bundle.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('/dist/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('/dist/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('/dist/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('/dist/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('/dist/plugins/fastclick/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/dist/js/adminlte.min.js') }}"></script>
    @yield('adminlte_js')
    <script>
        var title   = '{{ __("global.app_are_you_sure") }}';
        var text    = '{{ __("global.app_that_wont_be_undone") }}';
        var confirm = '{{ __("global.app_confirm") }}';
        var cancel  = '{{ __("global.app_cancel") }}';
        var token   = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
