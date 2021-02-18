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

        $('.formUploadedFilesAjax').ajaxForm({
            success:function(data) {
                if (data.code == 'error') {
                    Swal.fire(
                        '{{ __("global.app_error") }}',
                        data.message,
                        'error'
                    )
                }

                if(data.code == 'success') {
                    if (data.callback) {
                        eval(data.callback)
                    }
                }
            }
        });
    });
</script>

@if (count($items) > 0)
<table class="table table-head-fixed table-hover">
    <thead>
        <tr>
            <th>@lang('global.app_file_title')  </th>
            <th>@lang('global.app_file_type')   </th>
            <th>@lang('global.app_file_size')   </th>
            <th>@lang('global.app_file_date')   </th>
            <th>@lang('global.app_file_owner')  </th>
            <th>Redator                         </th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr data-entry-id="{{ $item->id }}">
            <td class="align-middle">{{ $item->title }}</td>
            <td class="align-middle">{{ $item->extension }}</td>
            <td class="align-middle">{{ $item->size }}</td>
            <td class="align-middle">{{ $item->date }}</td>
            <td class="align-middle">{{ $item->ownername }}</td>
            <td class="align-middle">
                @if(isset($item->redator))
                    {{ $item->redator }}
                    @else
                        <span>--</span>
                @endif
            </td>
            <td class="align-middle text-right">
                {{ Form::open([
                    'id' => 'formDelete'.$item->id,
                    'class' => 'formUploadedFilesAjax',
                    'method' => 'DELETE',
                    'route' => [$route]
                ]) }}
                {{ Form::hidden('id', $item->id) }}
                <div class="btn-group">
                    <a href="{{ asset($item->filename) }}" class="btn btn-sm btn-warning"><i class="fa fa-eye"></i> @lang('global.app_show')</a>
                    {{ Form::button('<i class="fa fa-trash"></i> ' . trans('global.app_delete'), array('type' => 'button', 'data-form' => 'formDelete'.$item->id, 'class' => 'btn btn-sm btn-danger deleteItem')) }}
                </div>
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@else
<div class="m-3">
    @lang('global.app_file_none')
</div>
@endif
