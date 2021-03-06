@extends('layouts.app')

@section('content_header')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
<link rel="stylesheet" href="bootstrap.min.css">
<script src="dist/js/BsMultiSelect.js"></script>


<h1><i class="fa fa-list"></i> @lang('legaladvice.registries.title') </h1>
@stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['legaladvice.registries.store']]) }}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="checkbox icheck-danger d-inline float-left">
                        {{ Form::checkbox('urgent', true, old('urgent'), ['id' => 'urgent']) }}
                        {{ Form::label('urgent', 'URGENTE') }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 form-group">
                    {{ Form::label('protocol', __('legaladvice.registries.fields.protocol').'*', ['class' => 'control-label']) }}<small> &nbsp; </small>
                    <div class="input-group mb-3">
                        {{ Form::text('protocol', old('protocol'), ['class' => 'form-control eprotocolkeypress', 'data-inputmask' => '"mask": "99.999.999-9"', 'data-mask' => '', 'placeholder' => '', 'required' => '', 'id'=>'eprotocolkeypress', 'data-trigger'=>'focus']) }}
                        <button class="addon2" type="button" id="addon2"> Procure </button>
                        @if($errors->has('protocol'))
                            <div class="form-group has-error">
                                <span class="help-block">{{ $errors->first('protocol') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('document_type', 'Documento *', ['class' => 'control-label']) }}
                    {{ Form::select('document_type', $doctypes, old('document_type'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('document_type'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('document_type') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('document_number', 'N?? do documento*', ['class' => 'control-label']) }}
                    {{ Form::text('document_number', old('document_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('document_number'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('document_number') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-5 form-group">
                    {{ Form::label('source', __('legaladvice.registries.fields.source').'*', ['class' => 'control-label']) }}
                    {{ Form::text('source', old('source'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('source'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('source') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    {{ Form::label('status', 'Solu????o', ['class' => 'control-label']) }}
                    {{ Form::select('status', $statuses, old('status'), ['class' => 'form-control', 'placeholder' => '']) }}
                    @if($errors->has('status'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('status') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('priority', 'Natureza *', ['class' => 'control-label']) }}
                    {{ Form::select('priority', $priorities, old('priority'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('priority'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('priority') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('place', __('legaladvice.registries.fields.place'), ['class' => 'control-label']) }}
                    {{ Form::select('place', $places, old('place'), ['class' => 'form-control', 'placeholder' => '']) }}
                    @if($errors->has('place'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('place') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-5 form-group">
                    {{ Form::label('interested', __('legaladvice.registries.fields.interested').'*', ['class' => 'control-label']) }}
                    {{ Form::text('interested', old('interested'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('interested'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('interested') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    {{ Form::label('date_in', __('legaladvice.registries.fields.date_in').'*', ['class' => 'control-label']) }}
                    <input type="date" class="form-control" name="date_in" required>
                    @if($errors->has('date_in'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('date_in') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('deadline', __('legaladvice.registries.fields.deadline').'*', ['class' => 'control-label']) }}
                    <input type="date" class="form-control" name="deadline" required>
                    @if($errors->has('deadline'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('deadline') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('date_out', __('legaladvice.registries.fields.date_out'), ['class' => 'control-label']) }}
                    <input type="date" class="form-control" name="date_out" required>
                    @if($errors->has('date_out'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('date_out') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('date_return', __('legaladvice.registries.fields.date_return'), ['class' => 'control-label']) }}
                    <input type="date" class="form-control" name="date_return" required>
                    @if($errors->has('date_return'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('date_return') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-4 form-group">
                    <label> E-mail </label>
                    {{ Form::text('email', '', ['class' => 'form-control', 'placeholder' => '']) }}
                </div>
                <div class="col-md-12 form-group">
                    {{ Form::label('key_words', 'Palavra chave', ['class' => 'control-label']) }}<br />
                    {{ Form::select('key_words[]', $Key_words, old('key_words[]'), ['style' => 'width: 100%', 'class' => 'form-control select2', 'multiple' => '']) }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('subject', __('legaladvice.registries.fields.subject').'*', ['class' => 'control-label']) }}
                    {{ Form::textarea('subject', old('subject'), ['class' => 'form-control', 'rows' => 4, 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('subject'))
                        <div class="form-group has-error">
                            <span class="help-block">{{ $errors->first('subject') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(trans('global.app_create'), ['class' => 'btn btn-success']) }}
            {{ Form::close() }}
        </div>
    </div>

    <div id="rolup" class="rolup" style="100%; height:100px; background: transparent;"> </div>

    <script type="text/javascript">
        $(document).ready(function(){

            $("option").on("click", function(e){
                console.log(e.target.text);
            });
 
            $(".addon2").click(function(){
                // Get CSRF token
	            const token = '{{ csrf_token() }}';

                jQuery.ajax({
                    // beforeSend: function( xhr ) {
                    //     xhr.overrideMimeType( "text/plain; charset=UTF-8" );
                    // },
                    type: "GET",
                    url: '/legaladvice/registries_verify_eProtocol',
                    contentType: "application/json",
                    dataType: "json",
                    data: {
                        'protocol': $(".eprotocolkeypress").val(),
                    },
                    success: function(data)
                    {
                        if(data.resp > 0){
                            alert("Protocolo existe !");
                            $('[data-toggle="popover"]').popover('enable')
                            $('[data-toggle="popover"]').popover('show');
                            console.log(data.resp);
                        }else{
                            alert("Protocolo n??o encontrado !");
                            $('[data-toggle="popover"]').popover('disable');
                            $('[data-toggle="popover"]').popover('hide');
                            console.log(data.resp);
                        }
                    },
                    error: function(data)
                    {
                        alert("error >>>");
                    }
                });
                //end ajax()
            });
        });
    </script>
@stop
