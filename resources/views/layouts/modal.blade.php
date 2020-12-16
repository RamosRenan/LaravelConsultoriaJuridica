@yield('css')
<section class="content-header">
    <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">&times; </span>
    </button>
    @yield('content_header')  
</section>
<section class="content">
    <div id="message"></div>
    @yield('content')
</section>
    @yield('js')
<script src="{{ asset('/dist/plugins/jQueryForm/js/jquery.form.js') }}"></script>
<script>
    $('.select2').select2({
        language: "pt-BR"
    });
</script>