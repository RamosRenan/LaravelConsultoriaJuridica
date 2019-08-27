@extends('layouts.app')

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <div class="text-center">
                <h1>@lang('legaladvice.legal_advice')</h1>
                <h5>@lang('legaladvice.document_registration')</h5>
            </div>
        </div>
        <div class="card-body">


            <table class="table">
                <tbody>
                    <tr>
                        <td class="text-right"><strong>@lang('legaladvice.registries.fields.document_number')</strong></td>
                        <td>{{ $item->document_number }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>@lang('legaladvice.registries.fields.interested')</strong></td>
                        <td>{{ $item->interested }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>@lang('legaladvice.registries.fields.protocol')</strong></td>
                        <td>{{ $item->protocol }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>@lang('legaladvice.registries.fields.subject')</strong></td>
                        <td>{{ $item->subject }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>@lang('legaladvice.registries.fields.date_in')</strong></td>
                        <td>{{ $item->date_in }}</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>@lang('legaladvice.registries.fields.deadline')</strong></td>
                        <td>{{ $item->deadline }}</td>
                    </tr>
                </tbody>
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
        </div>
    </div>
@stop
