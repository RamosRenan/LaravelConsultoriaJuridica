@extends('layouts.app')

@section('content')
    <div class="card card-default">
        <div class=" ">
            <div class="text-center">
                <br>
                <h3>@lang('legaladvice.legal_advice')</h3>
                <h5><small>@lang('legaladvice.document_registration')</small></h5>
            </div>
        </div>
        <div class="card-body">
        <style> 
            tr:nth-child(even){
                background-color: #dddddd;
            }
        </style>
        <table style="width:100%;">
            <tr>
                <th><strong>@lang('legaladvice.registries.fields.document_number')</strong></th>
                <td>&nbsp{{ $item->document_number }}</td>
            </tr>
             
            <tr>
                <th><strong>@lang('legaladvice.registries.fields.interested')</strong></th>
                <td>&nbsp{{ $item->interested }}</td>
            </tr>
             
            <tr>
                <th><strong>@lang('legaladvice.registries.fields.protocol')</strong></th>
                <td>&nbsp{{ $item->protocol }}</td>
            </tr>
             
            <tr>
                <th><strong>@lang('legaladvice.registries.fields.subject')</strong></th>
                <td style="text-align: justify;">
                    &nbsp{{ $item->subject }} 
                </td>
            </tr>
             
            <tr>
                <th><strong>@lang('legaladvice.registries.fields.date_in')</strong></th>
                <td>&nbsp{{ $item->date_in }}</td>
            </tr>
             
            <tr>
                <th><strong>@lang('legaladvice.registries.fields.deadline')</strong></th>
                <td>&nbsp{{ $item->deadline }}</td>
            </tr>
        </table>
            <br />
            <br />
            <div class="text-center">
                <h3>@lang('legaladvice.registries.fields.procedures')</h3>
            </div>
            @if (count($procedures) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('legaladvice.registries.fields.date')</th>
                        <th>@lang('legaladvice.registries.fields.document_type')</th>
                        <th>@lang('legaladvice.registries.fields.document_number')</th>
                        <th>@lang('legaladvice.registries.fields.source')</th>
                        <th>@lang('legaladvice.registries.fields.subject')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($procedures as $id => $item)
                    <tr data-entry-id="{{ $item->id }}">
                        <td class="align-middle">{{ $id + 1 }}</td>
                        <td class="align-middle">{{ $item->dateBR }}</td>
                        <td class="align-middle">{{ $doctypes[$item->document_type] }}</td>
                        <td class="align-middle">{{ $item->document_number }}</td>
                        <td class="align-middle">{{ $item->source }}</td>
                        <td class="align-middle">{{ $item->subject }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div class="m-3 text-center">
                @lang('global.app_no_entries_in_table')
            </div>
            @endif
            <br>
            <div class="text-center">
                <h3>Notas</h3>
            </div>
            @if (count($notes) > 0)
            <table style="width: 100%;">
                <thead>
                    <tr align="left">
                        <th>#</th>
                        <th>Inserido por</th>
                        <th>Conteudo</th>
                        <th>Entrada</th>
                        <th>Atualizado</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($notes as $key => $notes)
                    <tr data-entry-id="{{ $item->id }}">
                        <td><small>{{$key +=1 }}</small></td>
                        <td>{{ $notes->inserted_by }}</td>
                        <td>{{ $notes->contain }}</td>
                        <td>{{ $notes->date_in }}</td>
                        <td>{{ $notes->updated_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div class="m-3 text-center">
                @lang('global.app_no_entries_in_table')
            </div>
            @endif

            <br>
            <div class="text-center">
                <h3>Arquivos</h3>
            </div>
            @if (count($file_managers) > 0)
            <span> Total: &nbsp; {{count($file_managers)}} </span>
            <table style="width: 100%;">
                <thead>
                    <tr align="left">
                        <th>#</th>
                        <th>title</th>
                        <th>Nome</th>
                        <th>extension</th>
                        <th>Entrada</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($file_managers as $key_ => $file_managers)
                    <tr data-entry-id="{{ $item->id }}">
                        <td><small>{{$key_ +=1 }}</small></td>
                        <td>{{ $file_managers->title }}</td>
                        <td>{{ $file_managers->originalfilename }}</td>
                        <td>{{ $file_managers->extension }}</td>
                        <td>{{ $file_managers->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div class="m-3 text-center">
                @lang('global.app_no_entries_in_table')
            </div>
            @endif
        </div>
    </div>
@stop
