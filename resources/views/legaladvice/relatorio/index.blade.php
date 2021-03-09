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
        }
    </style>

    @section('content')


        <div class="card-body" style="background: white;" >
    

            <div class="container" style="">
                <div class="row">
                    <h5 style="font-family: Arial, Helvetica, sans-serif; margin-left: 15px;"><u> Relatório </u></h5>

                    <!-- qtd por ano -->
                    <div class="col-12" style="margin: auto;">
                        <div class="p-3 border bg-light">
                            <div class="card" align="center">
                                <div class="card-header"><span> Período 2 Últimos anos </span></div>
                            </div>

                            <!-- row into -->
                            <div class="row">
                                <div class="col">
                                    <div class="p-3 border bg-light">
                                        <p> Ano: 2000</p>
                                        <p> Total: 2000</p>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="p-3 border bg-light">
                                        <p> Ano: 2000</p>
                                        <p> Total: 2000</p>
                                    </div>
                                </div>
                            </div>
                            <!-- row into -->
                        </div>
                    </div>
                    <!-- qtd por ano -->
                                
                </div>
            </div>


            <br/>


            <div class="container" >
                <!-- row row-cols-2 -->
                <div class="row row-cols-2">
                    
                    <!-- qtd prioridade -->
                    <div class="col-4">
                        <div class="p-3 border bg-light">
                            <div class="card" align="center">
                                <div class="card-header"><span>Por prioridade</span></div>
                            </div>

                            <div class="col">
                                <div class="p-3 border bg-light" align="center">
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- qtd prioridade -->


                    <!-- qtd situação statusess -->
                    <div class="col-4">
                        <div class="p-3 border bg-light">
                            <div class="card" align="center">
                                <div class="card-header"><span> Por situação </span></div>
                            </div>

                            <div class="col">
                                <div class="p-3 border bg-light" align="center">
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- qtd situação statusess -->


                    <!-- qtd por lugares -->
                    <div class="col-4">
                        <div class="p-3 border bg-light">
                            <div class="card" align="center">
                                <div class="card-header"><span> Por lugares </span></div>
                            </div>

                            <div class="col">
                                <div class="p-3 border bg-light" align="center">
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                    <p>uma pri &nbsp; Total:000</p><hr>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- qtd lugares -->
                </div>
                <!-- row row-cols-2 -->
            </div>
        </div>
    @stop