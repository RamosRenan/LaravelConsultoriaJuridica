@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-list"></i> @lang('legaladvice.registries.title')</h1>
@stop

@section('js') 
<script>
    $(document).ready(function() {
        loadCalls = function() {
            $.ajax("{{ route('legaladvice.procedures.index') }}?id={{ $id }}").done(function(data) {
                $("#proceduresBox").html(data);
            });

            $.ajax("{{ route('legaladvice.registries.uploadindex') }}?id={{ $id }}").done(function(data) {
                $("#filesBox").html(data);
            });
        }

        loadCalls();

        /* /view/admin/fileupload */ 
        $('#modalBox').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            $('#modalBoxContent').load(button.data('url'));
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
                <div class="modal-body" id="modalBoxContent"> </div>
            </div>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_edit')</h3>
        </div>
        <div class="card-body">
            {{ Form::model($registry, ['id' => 'editForm', 'method' => 'PUT', 'route' => ['legaladvice.registries.update', $id]]) }}
                <div class="row">
                    <div class="col-md-12 form-group">
                        <div class="checkbox icheck-danger d-inline float-left">
                            {{ Form::checkbox('urgent', true, old('urgent'), ['id' => 'urgent']) }}
                            {{ Form::label('urgent', 'URGENTE') }} 
                        </div>
                    </div>
                </div>
                
                <div class="container shadow p-3 mb-5 bg-body rounded">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            {{ Form::label('protocol', __('legaladvice.registries.fields.protocol'), ['class' => 'control-label']) }}
                            {{ Form::text('protocol', old('protocol'), ['class' => 'form-control', 'data-inputmask' => '"mask": "99.999.999-9"', 'data-mask' => '', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('protocol'))
                                <span class="text-danger">{{ $errors->first('protocol') }}</span>
                            @endif
                        </div>
                        <div class="col-md-2 form-group">
                            {{ Form::label('document_type', 'Documento', ['class' => 'control-label']) }}
                            {{ Form::select('document_type', $doctypes, old('document_type'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('document_type'))
                                <span class="text-danger">{{ $errors->first('document_type') }}</span>
                            @endif
                        </div>
                        <div class="col-md-2 form-group">
                            {{ Form::label('document_number', 'N° do documento', ['class' => 'control-label']) }}
                            {{ Form::text('document_number', old('document_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('document_number'))
                                <span class="text-danger">{{ $errors->first('document_number') }}</span>
                            @endif
                        </div>
                        <div class="col-md-5 form-group">
                            {{ Form::label('source', __('legaladvice.registries.fields.source'), ['class' => 'control-label']) }}
                            {{ Form::text('source', old('source'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('source'))
                                <span class="text-danger">{{ $errors->first('source') }}</span>
                            @endif
                        </div>
                    </div>
                    <!-- row -->

                    <div class="row">
                        <div class="col-md-2 form-group">
                            {{ Form::label('status', 'Solução', ['class' => 'control-label']) }}
                            {{ Form::select('status', $statuses, old('status'), ['class' => 'form-control', 'placeholder' => '']) }}
                            @if($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                        <div class="col-md-3 form-group">
                            {{ Form::label('priority', 'Natureza', ['class' => 'control-label']) }}
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
                            {{ Form::label('interested', __('legaladvice.registries.fields.interested'), ['class' => 'control-label']) }}
                            {{ Form::text('interested', old('interested'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                            @if($errors->has('interested'))
                                <span class="text-danger">{{ $errors->first('interested') }}</span>
                            @endif
                        </div>
                    </div>
                    <!-- row -->

                    <div class="row">
                        <div class="col-md-2 form-group">
                            {{ Form::label('date_in', __('legaladvice.registries.fields.date_in'), ['class' => 'control-label']) }}
                            <input type="date" name="date_in" class="form-control" required>
                            @if($errors->has('date_in'))
                                <span class="text-danger">{{ $errors->first('date_in') }}</span>
                            @endif
                        </div>
                        <div class="col-md-2 form-group">
                            {{ Form::label('deadline', __('legaladvice.registries.fields.deadline'), ['class' => 'control-label']) }}
                            <input type="date" name="deadline" class="form-control" required>
                            @if($errors->has('deadline'))
                                <span class="text-danger">{{ $errors->first('deadline') }}</span>
                            @endif
                        </div>
                        <div class="col-md-2 form-group">
                            {{ Form::label('date_out', __('legaladvice.registries.fields.date_out'), ['class' => 'control-label']) }}
                            <input type="date" name="date_out" class="form-control" required>
                            @if($errors->has('date_out'))
                                <span class="text-danger">{{ $errors->first('date_out') }}</span>
                            @endif
                        </div>
                        <div class="col-md-2 form-group">
                            {{ Form::label('date_return', __('legaladvice.registries.fields.date_return'), ['class' => 'control-label']) }}
                            <input type="date" name="date_return" class="form-control" required>
                            @if($errors->has('date_return'))
                                <span class="text-danger">{{ $errors->first('date_return') }}</span>
                            @endif
                        </div>
                        <div class="col-md-4 form-group">
                            {{ Form::label('email', 'email', ['class' => 'control-label']) }} 
                            {{ Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) }}
                            @if($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 form-group">
                            {{ Form::label('key_words', 'Palavra chave', ['class' => 'control-label']) }}<br />
                            {{ Form::select('key_words', $Key_words, old('key_words[]'), ['style' => 'width: 100%', 'class' => 'form-control select2', 'multiple' => '']) }}
                        </div>
                    </div>
                    <!-- row -->

                    <div class="row">
                        <div class="col-md-12 form-group">
                            {{ Form::label('subject', __('legaladvice.registries.fields.subject'), ['class' => 'control-label']) }}
                            {{ Form::textarea('subject', old('subject'), ['class' => 'form-control', 'rows' => 4, 'placeholder' => '']) }}
                            @if($errors->has('subject'))
                                <span class="text-danger">{{ $errors->first('subject') }}</span>
                            @endif
                        </div>
                    </div>
                    <!-- row -->
                </div>
                <!-- container -->
            {{ Form::close() }}

            <div class="container shadow p-3 mb-5 bg-body rounded">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <span> <strong> Protocolos relacionados a palavra chave. </strong> </span>
                        <p> </p> 
                        @if(isset($allProtocolWithFoundIdWords) && count($allProtocolWithFoundIdWords)>0)
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">Protocolo</th>
                                <th scope="col">Interessado     </th>
                                <th scope="col">Natureza         </th>
                                </tr>
                            </thead>

                            <tbody>
                                    @foreach($allProtocolWithFoundIdWords as $key => $value)
                                        <tr>
                                            <td> {{$value->protocol}}          </td>
                                            <td> {{$value->interested}}        </td>
                                            <td colspan="2">{{$value->source}} </td>
                                        </tr>  
                                    @endforeach   
                            </tbody>
                        </table>
                            @else
                                Não há registros no sistema
                        @endif                        
                    </div>
                </div>
            </div>
            <!-- container -->


            <div class="container shadow p-3 mb-5 bg-body rounded">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <span> <strong>  Protocolos de mesmo interessado. </strong> </span>
                        <p> </p> 
                        @if(isset($protocolFromSameInterested) && count($protocolFromSameInterested)>0)
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">Protocolo</th>
                                <th scope="col">Interessado     </th>
                                <th scope="col">Natureza         </th>
                                </tr>
                            </thead>

                            <tbody>
                                    @foreach($protocolFromSameInterested as $key => $value)
                                        <tr>
                                            <td> {{$value->protocol}}          </td>
                                            <td> {{$value->interested}}        </td>
                                            <td colspan="2">{{$value->source}} </td>
                                        </tr>  
                                    @endforeach   
                            </tbody>
                        </table>
                            @else
                                Não há registros no sistema
                        @endif                        
                    </div>
                </div>
            </div>
            <!-- container -->


            <div class="container shadow p-3 mb-5 bg-body rounded">
                <!-- Insere um novo documento -->
                <div class="row">
                    <div class="col-md-12 form-group">
                        <b> Arquivos relacionados </b> &nbsp;
                        <!-- button aciona modal -->
                        <!-- Aqui mostra tabela de arquivos cadastros -->
                        <div id="filesBox"></div> 
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalBox" data-url="{{ route('legaladvice.registries.uploadcreate') }}?id={{ $id }}"> Novo arquivo </button>
                    </div>
                </div>
            </div>
            <!-- container -->


            <div class="container shadow p-3 mb-5 bg-body rounded">
                <div class="row">
                    <div class="col-md-12 form-group">
                        {{ Form::label('procedures', __('legaladvice.registries.fields.procedures'), ['class' => 'control-label']) }} 
                        <div id="proceduresBox"></div>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalBox" data-url="{{ route('legaladvice.procedures.create') }}?id={{ $id }}"> Nova tramitação </button>
                    </div>
                </div>
            </div>
            <!-- container -->

            
            <div class="container shadow p-3 mb-5 bg-body rounded">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <span> <strong> Anotações. </strong> </span>
                        <p> </p> 
                        @if(isset($note_registry) && count($note_registry)>0)
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">Nota de.:</th>
                                <th scope="col">Data     </th>
                                <th scope="col">Conteúdo  </th>
                                <th scope="col">         </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($note_registry as $key => $value)
                                    <tr>
                                        <td> {{$value->inserted_by}} </td>
                                        <td> {{$value->created_at}}              </td>
                                        <td colspan="2">{{$value->contain}}      </td>
                                        <td>                                     </td>
                                    </tr>  
                                @endforeach 
                            </tbody>
                        </table>
                            @else
                                <p>Não há registros no sistema</p>
                        @endif                          
                    </div>
                </div>
            </div>
            <!-- container -->
        </div>
        <div class="card-footer text-right">
            {{ Form::button(__('global.app_edit'), ['onclick' => "event.preventDefault(); document.getElementById('editForm').submit();", 'class' => 'btn btn-primary']) }}
        </div>
    </div>

    <br>
@stop
