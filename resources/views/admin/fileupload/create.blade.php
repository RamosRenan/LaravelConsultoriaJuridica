@extends('layouts.modal')

@section('content_header')
    <h1><i class="fa fa-file-upload"></i> @lang('global.app_file_upload_title')</h1>
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('#uploadFileAjax').ajaxForm({
            beforeSend:function() {
                $('#message').empty();
            },
            uploadProgress:function(event, position, total, percentComplete) {
                $('.progress-bar').text(percentComplete + '%');
                $('.progress-bar').css('width', percentComplete + '%');
            },
            success:function(data) {
                if(data.errors) {
                    $('.progress-bar').text('0%');
                    $('.progress-bar').css('width', '0%');
                        document.getElementById('uploadFileAjax').reset();
                        $('#message').html('<div class="alert alert-error alert-dismissible">'+'Dados não enviado. Título já existente  !!!' || data.errors +'</div>');
                        document.getElementById('file-name').textContent = '';
                        document.getElementById('chargedFile').innerHTML = "";

                    }
                if(data.success) {
                    $('.progress-bar').text('@lang('global.app_file_upload_success')');
                    $('.progress-bar').css('width', '100%');
                    $('#message').html('<div class="alert alert-success alert-dismissible">'+data.success+'</div>');
                }
                // $('#modalBox').delay( 800 ).hide( 'slow', function(){
                //     $('#modalBox').modal('hide');
                // } );
            }
        });

        document.getElementById('fileUpload').addEventListener('change', function(){
            let file_name = document.getElementById('file-name');
            file_name.textContent = this.value;
            file_name.style.fontSize = "x-large" ;
            file_name.style.color = "green" ;
            document.getElementById('chargedFile').innerHTML = " DOCUMENTO CARREGADO COM SUCESSO  <i class='fas fa-check'></i>";
        });

    });
</script>
@stop


@section('content')
    {!! Form::open(['id' => 'uploadFileAjax', 'method' => 'POST', 'route' => $store, 'enctype' => 'multipart/form-data']) !!}
        {!! Form::hidden('route_id', $id) !!}
        {!! Form::label('title', __('global.app_file_title').'*', ['class' => 'control-label']) !!}
        {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '', 'title'=>'nao esqueca']) !!}
        {!! Form::label('fileUpload', __('global.app_file_select'), ['class' => 'btn btn-warning btn-sm mt-2']) !!}
        {!! Form::file('fileUpload', ['style' => 'display: none']) !!}
        <br />
        {!! Form::label('redator', 'Redator', ['class' => 'control-label']) !!}
        {!! Form::text('redator', ' ', ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
        <br />
        <span id='file-name'></span>
        &nbsp;
        <span id="chargedFile" STYLE="color:green;"></span>
        {!! Form::submit('Enviar dados', ['class' => 'btn btn-primary btn-sm float-right mt-2']) !!}
        <button style="float:right; margin-right:18px; margin-top: 8px;" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            Fechar
        </button>
    {!! Form::close() !!}
@stop