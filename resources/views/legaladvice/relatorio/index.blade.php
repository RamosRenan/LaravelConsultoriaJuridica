@extends('layouts.app')

    @section('content_header')

    @stop


    @section('js')

    @stop

    <style>
        p, span{
            font-family: Arial, Helvetica, sans-serif;
         }

        span{
            font-size:18px;
            color:white;
        }

        .default{
            background:  #2c3e50;
            border-radius: 1px;
            box-shadow: 1px 1px 4px 1px   #abb2b9 ;
            color:white;
            padding-top: 8px;
            padding-bottom: 8px;
            margin: 2px;
        }
    </style>

    @section('content')
        <div class="card-body" style="background: white; padding-bottom: 8px;" >
            <div class="container" style="">
                <div class="container">
                    <div class="row" align="center">
                        <div class="col default">
                            <h4> <i class="fas fa-archive"></i> &nbsp; Total arquivados </h4>
                            <h5>
                                @if(isset($arquivados))
                                    {{$arquivados}}
                                    @else
                                        -
                                @endif
                            </h5>
                        </div>
                        <div class="col default">
                            <h4> <i class="fas fa-sign-out-alt"></i> &nbsp; Total fora da CJ</h4>
                            <h5>
                                @if(isset($foraCJ))
                                    {{$foraCJ}}
                                    @else
                                        -
                                @endif
                            </h5>
                        </div>
                        <div class="col default">
                            <h4> <i class="fas fa-globe"></i> &nbsp; Total de protocolos</h4>
                            <h5>
                                @if(isset($total))
                                    {{$total}}
                                    @else
                                        -
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    @if($errors->any())
                        <div class="alert alert-warning">
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </div>
                    @endif

                    <!-- qtd por ano -->
                    <div class="col-12" style="margin: auto;">
                        <div class="p-3 border bg-light">
                            <div class=" " align="center">
                                <div class="" style="background: transparent;"><span style="color: gray;"> Faça uma busca por período </span></div>
                                <hr>
                            </div>

                            <!-- row into -->
                            <div class="row" >
                                <div class="col">
                                    <form action="{{ route('legaladvice.relatorioSearch') }}" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <input type="date" name="date_i" class="form-control" placeholder="Data inicial" aria-label="Data inicial" required>
                                            </div>

                                            <div class="col-2">
                                                <input type="time" name="time_i" class="form-control" placeholder="Data inicial" aria-label="Data inicial" required>
                                            </div>

                                            <div class="col-4">
                                                <input type="date" name="date_f" class="form-control" placeholder="Data final" aria-label="Data final" required>
                                            </div>

                                            <div class="col-2">
                                                <input type="time" name="time_f" class="form-control" placeholder="Data final" aria-label="Data final" required>
                                            </div>
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-success btn-sm"> <i class="fas fa-search"></i> &nbsp; Buscar</button>
                                    </form>
                                </div>
                            </div>
                            <!-- row into -->
                        </div>
                    </div>
                    <!-- qtd por ano -->
                </div>
            </div>

            <br>

            <div class="container" >
                <!-- row row-cols-2 -->
                <div class="row row-cols-2">
                    
                    <!-- qtd prioridade -->
                    <div class="col-6">
                        <div class="p-3 border bg-light">
                            <div class="card" align="center">
                                <div class="card-header" style="background:  #aeb6bf ;"><span><i class="far fa-bell"></i> &nbsp;Prioridades</span></div>
                            </div>

                            <div class="col" align="center">
                                <div class="p-3 border bg-light"  >
                                    @if(isset($priority) && count($priority) > 0)
                                        @foreach($priority as $prior)
                                        <h5> <small>{{$prior->name}}:</small> &nbsp; <span style="font-size:20px; color:black;">{{$prior->total}}</span></h5> 
                                        <hr>
                                        @endforeach
                                        
                                        @elseif(isset($priority) && count($priority) == 0)
                                            <div class="alert alert-info" role="alert">
                                                Não foi encontrado nenhum registro. 
                                            </div> 
                                        @else
                                        <div class="alert alert-light" role="alert">
                                            Informe um período para a busca.
                                        </div>                                           
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- qtd prioridade -->


                    <!-- qtd situação statusess -->
                    <div class="col-6">
                        <div class="p-3 border bg-light">
                            <div class="card" align="center">
                                <div class="card-header" style="background:  #aeb6bf ;"><span><i class="fas fa-business-time"></i> &nbsp;  Situação </span></div>
                            </div>

                            <div class="col" align="center">
                                <div class="p-3 border bg-light" >
                                    @if(isset($status) && count($status) > 0)
                                        @foreach($status as $prior)
                                        <h5> <small>{{$prior->name}}:</small> &nbsp; <span style="font-size:20px; color:black;">{{$prior->total}}</span></h5> 
                                        <hr>
                                        @endforeach
                                        
                                        @elseif(isset($status) && count($status) == 0)
                                            <div class="alert alert-info" role="alert">
                                                Não foi encontrado nenhum registro. 
                                            </div> 
                                        @else
                                        <div class="alert alert-light" role="alert">
                                            Informe um período para a busca.
                                        </div>                                           
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- qtd situação statusess -->


                    <!-- qtd por lugares -->
                    <div class="col-6" style="margin-top:20px;">
                        <div class="p-3 border bg-light">
                            <div class="card" align="center">
                                <div class="card-header" style="background:  #aeb6bf ;"><span><i class="fas fa-map-marker-alt"></i> &nbsp; Locais </span></div>
                            </div>

                            <div class="col" align="center">
                                <div class="p-3 border bg-light"  >
                                    @if(isset($place) && count($place) > 0)
                                        @foreach($place as $prior)
                                        <h5> <small>{{$prior->name}}:</small> &nbsp; <span style="font-size:20px; color:black;">{{$prior->total}}</span></h5> 
                                        <hr>
                                        @endforeach
                                        
                                        @elseif(isset($place) && count($place) == 0)
                                            <div class="alert alert-info" role="alert">
                                                Não foi encontrado nenhum registro. 
                                            </div> 
                                        @else
                                        <div class="alert alert-light" role="alert">
                                            Informe um período para a busca.
                                        </div>                                           
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- qtd lugares -->


                    <!-- qtd por Tipos de documentos -->
                    <div class="col-6" style="margin-top:20px;">
                        <div class="p-3 border bg-light">
                            <div class="card" align="center">
                                <div class="card-header" style="background:  #aeb6bf ;"><span><i class="far fa-file-alt"></i> &nbsp; Documentos </span></div>
                            </div>

                            <div class="col" align="center">
                                <div class="p-3 border bg-light"  >
                                    @if(isset($procedure) && count($procedure) > 0)
                                        @foreach($procedure as $prior)
                                        <h5> <small>{{$prior->name}}:</small> &nbsp; <span style="font-size:20px; color:black;">{{$prior->total}}</span></h5> 
                                        <hr>
                                        @endforeach
                                        
                                        @elseif(isset($procedure) && count($procedure) == 0)
                                            <div class="alert alert-info" role="alert">
                                                Não foi encontrado nenhum registro. 
                                            </div> 
                                        @else
                                        <div class="alert alert-light" role="alert">
                                            Informe um período para a busca.
                                        </div>                                           
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- qtd Tipos de documentos -->
                </div>
                <!-- row row-cols-2 -->
            </div>
        </div>
    @stop