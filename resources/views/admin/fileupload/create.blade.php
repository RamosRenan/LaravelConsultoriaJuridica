@extends('layouts.modal')

@section('content_header')
    <h1 id="fafileupload"> <i class="fa fa-file-upload"></i> @lang('global.app_file_upload_title') </h1>
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('#uploadFileAjax').ajaxForm({
            beforeSend:function() {
                $('#message').empty();
            },
            success:function(data) {
                if(data.errors) {
                        document.getElementById('uploadFileAjax').reset();
                        $('#message').html('<div class="alert alert-error alert-dismissible">'+'Dados não enviado. Título já existente  !' || data.errors +'</div>');
                        document.getElementById('file-name').textContent = '';
                        document.getElementById('chargedFile').innerHTML = "";
                }
                if(data.success) {
                    $('#hiddenForm').css('display', 'none');
                    // $('#message').html('<div class="alert alert-success alert-dismissible">'+data.success+'</div>');
                    $('#fafileupload').css('dislpay', 'none');
                    $('#message').html('<br/><div class="alert alert-success alert-dismissible fade show" role="alert">'+data.success+'</div>');
                    document.getElementById('fafileupload').style.display = "none";
                }
                /* Se desejar que o modal feche automaticamente. Descomente o trecho do código */
                // $('#modalBox').delay( 800 ).hide( 'slow', function(){
                //     $('#modalBox').modal('hide');
                // } );
            }
        });

        document.getElementById('fileUpload').addEventListener('change', function(){
            let splitsFilename = this.files[0].name.split('.');
            document.getElementById('title').value = splitsFilename[0];
        });
    });
</script>
@stop


@section('content')
    {!! Form::open(['id' => 'uploadFileAjax', 'method' => 'POST', 'route' => $store, 'enctype' => 'multipart/form-data']) !!}
        <div id="hiddenForm" style="display:block;">
            {!! Form::hidden('route_id', $id) !!}
            {!! Form::label('fileUpload', 'Selecione o arquivo*', ['class' => '']) !!}
            {!! Form::file('fileUpload', ['style' => 'display: block']) !!}
            <br />
            {!! Form::label('title', __('global.app_file_title').'*', ['class' => 'control-label']) !!}
            {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
            <br />
            {!! Form::label('redator', 'Redator', ['class' => 'control-label']) !!}
            {!! Form::text('redator', ' ', ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
            <br />
            <span id='file-name'></span>
            &nbsp;
            <span id="chargedFile" style="color:green;"></span>
            {!! Form::submit('Enviar dados', ['class' => 'btn btn-success btn-sm']) !!}
        </div>
        <button style="float:right;" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            Fechar
        </button>
    {!! Form::close() !!}
@stop