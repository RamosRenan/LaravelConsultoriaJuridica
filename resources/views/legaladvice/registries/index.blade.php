@extends('layouts.app')

    @section('content_header')
        <link rel="stylesheet" href="https://drvic10k.github.io/bootstrap-sortable/Contents/bootstrap-sortable.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.js"></script>
        <script src="https://drvic10k.github.io/bootstrap-sortable/Scripts/bootstrap-sortable.js"></script>


        <h1 style="color: #339af0;">
            <i class="fa fa-list"></i> 
            @lang('legaladvice.registries.title')  
            
            @if((isset($outCg)))
                Fora da CJ
            @endif

            @if((isset($gabinet)))
                Gabinete
            @endif

            @if((isset($arquivado)))
                Arquivados  
            @endif

            @if((isset($secretaria)))
                Secretaria
            @endif
        </h1>
    @stop

    @section('js') 
        <script>
            $(function() {
                $('.deleteItem').click(function() {
                    Swal.fire({
                        title: '{{ __("global.app_are_you_sure") }}',
                        text: '{{ __("global.app_that_wont_be_undone") }}',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: '{{ __("global.app_cancel") }}',
                        confirmButtonText: '{{ __("global.app_confirm") }}'
                    }).then((result) => {
                        if (result.value) {
                            $('#' + $(this).attr("data-form")).submit();
                        }
                    });
                });   
            });

            var route = '{{ route('legaladvice.registries.mass_destroy') }}';

            
            $(document).ready(function() {
                const subChar = '_';
                var paragraph = $("#search").val();
                $('#search').on('focus click', function() {
                    $(this)[0].setSelectionRange(0, 0);
                    $("#search").keydown(function(e) {
                        $("#search").inputmask({
                            mask: '99.999.999-9',
                            placeholder: '__.___.___-_',
                            showMaskOnHover: false,
                            showMaskOnFocus: false,
                        });
                    });

                    
                });
            });

            function note(e){
                var elementClick = e.offsetParent.parentElement.parentElement.nextElementSibling.ch;

                if(elementClick == ""){
                    e.offsetParent.parentElement.parentElement.nextElementSibling.style="display: contents";
                    e.offsetParent.parentElement.parentElement.nextElementSibling.ch  = "false";
                }else{
                    e.offsetParent.parentElement.parentElement.nextElementSibling.style="display: none";
                    e.offsetParent.parentElement.parentElement.nextElementSibling.ch  = "";
                }

                console.log(e.offsetParent.parentElement.parentElement.nextElementSibling);
                console.log(e.offsetParent.parentElement.parentElement.nextElementSibling.ch);
            }

        </script>         
    @stop

    <style> 
        td{
            font-size:12.5px;
        }
    </style>

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('legaladvice.registries.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> @lang('global.app_create') </a>
                </div>
                <div class="col-md-6">
                    <div class="float-sm-right">
                        {{ Form::open(['method' => 'GET', 'route' => ['legaladvice.registries.index']]) }}
                        @csrf
                        <div class="input-group input-group-sm">
                            {{ Form::text('search', '__.___.___-_', array('class'=>'form-control', 'id'=>'search')) }}                             
                            <span class="input-group-append">
                                {{ Form::button('<i class="fa fa-search"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-flat']) }}
                            </span>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <i class="fas fa-circle" style="color:red;">   <span style="color:black"> <small>Perdeu o prazo</small> </span> </i> &nbsp; &nbsp;
                <i class="fas fa-circle" style="color:orange;">   <span style="color:black"> <small>Vencendo</small> </span> </i> &nbsp; &nbsp;
                <i class="fas fa-circle" style="color:whitesmoke;">   <span style="color:black"> <small>Dentro do prazo </small></span> </i> &nbsp; &nbsp;&nbsp;
                <i class="fas fa-exclamation-triangle" style="color: red;"></i>   <span style="color:black"> <small> &nbsp; Urgente </small></span> </i> &nbsp; &nbsp;
                &nbsp; &nbsp;  &nbsp; <span style="color:black"> <small> <b>(Base de cálculo 3 dias.)</b> </small></span> </i>  
            </div>
        </div>
        

        <div class="card-body table-responsive p-0">
            @if (count($items) > 0)
            <table class="table  table-hover table-bordered sortable">
                <thead class=" ">
                    <tr>
                        <th class="text-center"> </th>
                        <th>@lang('legaladvice.registries.fields.protocol')</th>
                        <th>Prioridade</th>
                        <th>@lang('legaladvice.registries.fields.interested')</th>
                        <th>@lang('legaladvice.registries.fields.subject')</th>
                        <th>@lang('legaladvice.registries.fields.date_in')</th>
                        <th>@lang('legaladvice.registries.fields.deadline')</th>
                        <th><i class="fa fa-file"></i></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

                <tbody style=" ">
                    @foreach ($items as $item)
                        <tr data-entry-id="{{ $item->id }}" class="protocol">
                            <td class="align-middle {{ (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 3) ? (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 0) ? 'table-danger' : 'table-warning' : '' }}">
                                @if(($item->urgent)) <i class="fas fa-exclamation-triangle" style="color: red;"></i> @endif
                            </td>

                            <td value="{{ $item->protocol }}" class=" align-middle {{ (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 3) ? (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 0) ? 'table-danger' : 'table-warning' : '' }}">
                                <a  href="#"> {{ $item->protocol }} </a>
                            </td>

                            <td class="align-middle {{ (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 3) ? (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 0) ? 'table-danger' : 'table-warning' : '' }}">
                                <a href="{{ route('legaladvice.registries.index') }}?priority={{ $item->priority_id  }}&see=true"> {{ $item->name }}</a>
                            </td>

                            <td class="align-middle {{ (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 3) ? (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 0) ? 'table-danger' : 'table-warning' : '' }}"> {{ $item->interested }} </td>

                            <td style="word-wrap: break-word; word-break: break-all;" class=" {{ (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 3) ? (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 0) ? 'table-danger' : 'table-warning' : '' }}"> {{ $item->r_subject }} </td>

                            <td data-dateformat="DD-M-YYY" class="align-middle {{ (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 3) ? (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 0) ? 'table-danger' : 'table-warning' : '' }}"> {{implode("/",array_reverse(explode("-",$item->r_date_in)))}} </td>
                            
                            <td data-dateformat="DD-M-YYY" class="align-middle {{ (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 3) ? (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 0) ? 'table-danger' : 'table-warning' : '' }}"> {{implode("/",array_reverse(explode("-",$item->deadline)))}} </td>
                            
                            <td class="align-middle {{ (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 3) ? (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 0) ? 'table-danger' : 'table-warning' : '' }}"> {{ $item->qtd_file_managers }} </td>
                            
                            <td class="align-middle {{ (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 3) ? (((strtotime($item->deadline) - strtotime(date('Y-m-d')))/(60 * 60 * 24)) < 0) ? 'table-danger' : 'table-warning' : '' }} text-right">
                                {{ Form::open(array(
                                    'id' => 'deleteItem'.$item->id,
                                    'method' => 'DELETE',
                                    'route' => ['legaladvice.registries.destroy', $item->id])) }}
                                {{ Form::close() }}
                                <div class="btn-group">
                                    @if(auth()->user()->can('legaladvice.registries.show') || auth()->user()->can('@@ superadmin @@') || auth()->user()->can('@@ admin @@')) 
                                        <a href="{{ route('legaladvice.registries.show',[$item->id]) }}" class="btn btn-sm btn-warning"><i class="fa fa-eye"></i> </a>
                                    @endif
                                    
                                    @if(auth()->user()->can('legaladvice.registries.edit') || auth()->user()->can('@@ superadmin @@') || auth()->user()->can('@@ admin @@')) 
                                        <a href="{{ route('legaladvice.registries.edit',[$item->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>  </a>
                                    @endif
                                    
                                    @if(auth()->user()->can('legaladvice.registries.destroy') || auth()->user()->can('@@ superadmin @@') || auth()->user()->can('@@ admin @@')) 
                                        {{ Form::button('<i class="fa fa-trash"></i> ', ['type' => 'button', 'data-form' => 'deleteItem'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem']) }}
                                    @endif

                                    @if(auth()->user()->can('legaladvice.registries.note') || auth()->user()->can('@@ superadmin @@') || auth()->user()->can('@@ admin @@')) 
                                        <a onclick="note(this)" class="btn btn-sm btn-light"> <i class="far fa-folder-open"></i>  </a>
                                    @endif
                                </div>
                            </td> 

                            <tr style="display: none;" id="openNote">
                                <td colspan="9" class="{{ $item->protocol }}">
                                    <span>  <strong> Ultima atualização: </strong> &nbsp {{ $item->date_in }}     </span>  
                                    <br>     
                                    <span>  <strong> Responsável:        </strong> &nbsp {{ $item->inserted_by }} </span> 
                                    <p>     <strong> Descrição:          </strong> <span style="width:auto; height:auto; background: #eceff4;"> &nbsp {{ $item->contain }} &nbsp &nbsp</span>     </p> 
                                    
                                    <hr>
                                    
                                    {{Form::open(['method'=>'POST', 'route'=>['legaladvice.registries.note']])}}
                                            <input type="hidden" value="{{ $item->id }}" name="id_registries">
                                            <input type="hidden"  value="{{ $item->protocol }}" name="eProtocolo">
                                            <textarea style="display:inline-block;" type="text" name="contain" class="form-control" rows="2" required>
                                                                
                                            </textarea>
                                            <button type="submit" style="display:inline-block; margin-left:-60px;" class="btn btn-primary" > <i class="fa fa-plus"></i> </button>
                                    {{Form::close()}}
                                </td >
                            </tr>                
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @else
                <div class="m-3">
                    @lang('global.app_no_entries_in_table')
                </div>
            @endif
        </div>

        <div class="card-footer">
            <div class="row"></div>
        </div>
    </div>
@endsection
