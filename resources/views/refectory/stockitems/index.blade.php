@if (count($procedures) > 0)
    <table class="table table-head-fixed table-hover">
        <thead>
            <tr>
                <th>@lang('legaladvice.registries.fields.date')</th>
                <th>@lang('legaladvice.registries.fields.document_type')</th>
                <th>@lang('legaladvice.registries.fields.document_number')</th>
                <th>@lang('legaladvice.registries.fields.source')</th>
                <th>@lang('legaladvice.registries.fields.subject')</th>
                <th>@lang('legaladvice.registries.fields.files')</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($procedures as $item)
            <tr data-entry-id="{{ $item->id }}">
                <td class="align-middle">{{ $item->dateBR }}</td>
                <td class="align-middle">{{ $doctypes[$item->document_type] }}</td>
                <td class="align-middle">{{ $item->document_number }}</td>
                <td class="align-middle">{{ $item->source }}</td>
                <td class="align-middle">{{ $item->subject }}</td>
                <td class="align-middle">
                    @if ($item->files <> '[]')
                    @foreach ($item->files as $file)
                    <a href="{{ asset($file->filename) }}"><span class="badge badge-warning">{{ $file->title }}</span></a>
                    @endforeach
                    @else
                    @lang('global.app_file_none')
                    @endif
                </td>
                <td class="align-middle text-right">
                    {{ Form::open([
                        'id' => 'formDeleteAjax'.$item->id,
                        'class' => 'formAjax', 
                        'style' => 'display: inline-block;',
                        'method' => 'DELETE',
                        'route' => ['legaladvice.procedures.destroy', $item->id]
                    ]) }}
                    {{ Form::hidden('registry_id', $_GET['id']) }}
                    <div class="btn-group">
                        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalBox" data-url="{{ route('legaladvice.procedures.edit', $item->id) }}?id={{$item->id}}&registry_id={{$_GET['id']}}"><i class="fa fa-edit"></i> @lang('global.app_edit')</a>
                        {{ Form::button('<i class="fa fa-trash"></i> ' . trans('global.app_delete'), array('type' => 'button', 'data-form' => 'formDeleteAjax'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem')) }}
                    </div>
                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="m-3">
    @lang('global.app_no_entries_in_table')
</div>
@endif
<script>
    $(document).ready(function(){
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

        $('.formAjax').ajaxForm({
            success:function(data) {
                if(data.code == 'error') {
                    Swal.fire(
                        '{{ __("global.app_error") }}',
                        data.messages,
                        'error'
                    )
                }

                if(data.code == 'success') {
                    Swal.fire(
                        '{{ __("global.app_success") }}',
                        data.messages,
                        'success'
                    )
                    eval(data.callback)
                }
            }
        });
    });
</script>