@extends('layouts.app')

@section('content_header')
    <h1 style="color: #339af0;">
        <i class="fa fa-list"></i> 
        @lang('legaladvice.registries.title')  
        
        @if((isset($outCg)))
            Fora do CG
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
</script>



<!--
    * script responsável por detectar cursor do mouse 
    * quando deslizar sobre as barras dos protocolos. 
-->
<script>

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
        /*

    $(document).ready(function(){
        var verify = false;

        $(".btn-light").on("click", function(){
            console.log($(".btn-light"));
        });
        $(".protocol").on("mouseenter", function(){
            //console.log(this.nextSibling);
            //$("myo").slideDown();
            $(this.nextSibling).slideDown("slow");
            console.log("solide down: "+this.nextSibling);
            console.log(this.nextSibling);
        }).on("mouseleave", function(event){
            //console.log(this.nextSibling);
            $(this.nextSibling).slideUp("slow");
            //console.log(event);
            $(".myo").on("mouseenter", function(){
                $(this).slideDown("slow");
            }).on("mouseleave", function(){
                $(".myo").slideUp("slow");
            });      
              
        }); 
    }); 
        */     
     
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
                    <a href="{{ route('legaladvice.registries.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> @lang('global.app_create')</a>
                </div>
                <div class="col-md-6">
                    <div class="float-sm-right">
                        {{ Form::open(['method' => 'GET', 'route' => ['legaladvice.registries.index']]) }}
                        @csrf
                        <div class="input-group input-group-sm">
                            {{ Form::text('search', $search, ['class' => 'form-control', 'placeholder' => __('global.app_search')]) }}
                            <span class="input-group-append">
                                {{ Form::button('<i class="fa fa-search"></i>', ['type' => 'submit', 'class' => 'btn btn-info btn-flat']) }}
                            </span>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>


        <div class="card-body table-responsive p-0">
            @if (count($items) > 0)
            <table class="table table-head-fixed table-hover">
                <thead>
                    <tr>
                        <th class="text-center">
                            <div class="checkbox icheck-primary">
                                {{ Form::checkbox('', false, false, ['id' => 'select-all']) }}
                                {{ Form::label('select-all', '&nbsp;') }}
                            </div>
                        </th>
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

                <tbody>
                    @foreach ($items as $item)
                        @if($item->place != 4)
                        <tr data-entry-id="{{ $item->id }}" class="protocol">
                            <td class="align-middle {{ ($item->urgent) ? 'table-danger' : '' }} text-center">
                                <div class="checkbox icheck-primary">
                                    {{ Form::checkbox('ids[]', $item->id, false, ['id' => 'selectId'.$item->id]) }}
                                    {{ Form::label('selectId'.$item->id, '&nbsp;') }}
                                </div>
                            </td>
                            <td value="{{ $item->protocol }}" class=" align-middle {{ ($item->urgent) ? 'table-danger' : '' }}"><a  href="#">{{ $item->protocol }}</a></td>
                            <td class="align-middle {{ ($item->urgent) ? 'table-danger' : '' }}">
                                <a href="{{ route('legaladvice.registries.index') }}?priority={{ $item->priority_id  }}&see=true"> {{ $item->name }}</a>
                            </td>
                            <td class="align-middle {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->interested }}        </td>
                            <td class="align-middle {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->r_subject }}         </td>
                            <td class="align-middle {{ ($item->urgent) ? 'table-danger' : '' }}">{{implode("/",array_reverse(explode("-",$item->r_date_in)))}}  </td>
                            <td class="align-middle {{ ($item->urgent) ? 'table-danger' : '' }}">{{implode("/",array_reverse(explode("-",$item->deadline)))}}   </td>
                            <td class="align-middle {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->qtd_file_managers }} </td>
                            <!-- <td class="align-middle {{ ($item->urgent) ? 'table-danger' : '' }}"> chave $item->procedures chave </td> -->
                            <td class="align-middle {{ ($item->urgent) ? 'table-danger' : '' }} text-right">
                                {{ Form::open(array(
                                    'id' => 'deleteItem'.$item->id,
                                    'method' => 'DELETE',
                                    'route' => ['legaladvice.registries.destroy', $item->id])) }}
                                {{ Form::close() }}
                                <div class="btn-group">
                                    <a href="{{ route('legaladvice.registries.show',[$item->id]) }}" class="btn btn-sm btn-warning"><i class="fa fa-eye"></i> </a>
                                    <a href="{{ route('legaladvice.registries.edit',[$item->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>  </a>
                                    {{ Form::button('<i class="fa fa-trash"></i> ', ['type' => 'button', 'data-form' => 'deleteItem'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem']) }}
                                    <!-- open note() -->
                                    <a onclick="note(this)" class="btn btn-sm btn-light"> <i class="far fa-folder-open"></i>  </a>
                                </div>
                            </td> 
                            <tr style="display: none;" class="myo">
                                <td colspan="9" class="{{ $item->protocol }}">
                                    <span>      <strong> Ultima atualização: </strong> &nbsp {{ $item->date_in }}     </span>  
                                    <br>     
                                    <span>      <strong> Responsável:        </strong> &nbsp {{ $item->inserted_by }} </span> 
                                    <p>      <strong> Descrição:          </strong> <span style="width:auto; height:auto; background: #eceff4;"> &nbsp {{ $item->contain }} </span>     </p> 
                                    
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
                        @endif
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
            <div class="row">
                <div class="col-md-3">
                    @if (count($items) > 0)
                    <button class="btn btn-danger massDelete"><i class="fa fa-trash"></i> @lang('global.app_delete_selected')</button>
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="float-right"> 
                    <!-- chave $items->links() chave -->
                     
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
