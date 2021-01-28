<!-- 
   - Não é possível divisão por zero
   - se for zero aponto total para 1
 -->
@if($total <= 0)
    {{$total = 1}}
@endif
<div class="row">
    <div class="col-md-4">
        <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-file"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">@lang('legaladvice.title.uptodate')</span>
                <span class="info-box-number"><h1><a href="{{ route('legaladvice.registries.index') }}?see=uptodate" class="text-white">{{ count($items->uptodate) }}</a></h1></span>
                <div class="progress">
                    
                        <div class="progress-bar" style="width: {{ 100 * count($items->uptodate) / $total }}%"></div>
                </div>
                <span class="progress-description">
                    {{ number_format (100 * count($items->uptodate) / $total, 2) }}% @lang('legaladvice.title.of_all')
                </span>
            </div>
        </div>
        @if (count($items->uptodate) > 0)
        <table class="table table-head-fixed table-hover">
            <thead>
                <tr>
                    <th class="text-center">@lang('legaladvice.registries.fields.protocol')</th>
                    <th class="text-center"><i class="fa fa-skull"></i></th>
                    <th class="text-center"><i class="fa fa-hourglass-end"></i></th>
                    <th class="text-center"><i class="fa fa-file"></i></th>
                    <th class="text-center"><i class="fa fa-share"></i></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            @foreach($items->uptodate as $item)
                <tbody>
                    <tr data-entry-id="{{ $item->id }}">
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->protocol }}</td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ date('d/m/y', strtotime($item->deadline)) }}</td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}"><span class='badge badge-success'>{{ $item->remainingdays }}</span></td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->files }}</td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->procedures }}</td>
                        <td class="text-right {{ ($item->urgent) ? 'table-danger' : '' }}">
                            <a href="{{ route('legaladvice.registries.edit',[$item->id]) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
            @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="col-md-4">
        <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-file"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">@lang('legaladvice.title.deadline')</span>
                <span class="info-box-number"><a href="{{ route('legaladvice.registries.index') }}?see=deadline" class="text-dark"><h1>{{ count($items->deadline) }}</h1></a></span>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ 100 * count($items->deadline) / $total }}%"></div>
                </div>
                <span class="progress-description">
                    {{ number_format (100 * count($items->deadline) / $total, 2) }}% @lang('legaladvice.title.of_all')
                </span>
            </div>
        </div>
        @if (count($items->deadline) > 0)
        <table class="table table-head-fixed table-hover">
            <thead>
                <tr>
                    <th class="text-center">@lang('legaladvice.registries.fields.protocol')</th>
                    <th class="text-center"><i class="fa fa-skull"></i></th>
                    <th class="text-center"><i class="fa fa-hourglass-end"></i></th>
                    <th class="text-center"><i class="fa fa-file"></i></th>
                    <th class="text-center"><i class="fa fa-share"></i></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            @foreach($items->deadline as $item)
                <tbody>
                    <tr data-entry-id="{{ $item->id }}">
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->protocol }}</td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ date('d/m/y', strtotime($item->deadline)) }}</td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}"><span class='badge badge-warning'>{{ $item->remainingdays }}</span></td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->files }}</td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->procedures }}</td>
                        <td class="text-right {{ ($item->urgent) ? 'table-danger' : '' }}">
                            <a href="{{ route('legaladvice.registries.edit',[$item->id]) }}" class="btn btn-sm btn-warning"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
            @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="col-md-4">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-file"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">@lang('legaladvice.title.late')</span>
                <span class="info-box-number"><a href="{{ route('legaladvice.registries.index') }}?see=late" class="text-white"><h1>{{ count($items->late) }}</h1></a></span>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ 100 * count($items->late) / $total }}%"></div>
                </div>
                <span class="progress-description">
                    {{ number_format (100 * count($items->late) / $total, 2) }}% @lang('legaladvice.title.of_all')
                </span>
            </div>
        </div>
        @if (count($items->late) > 0)
        <table class="table table-head-fixed table-hover">
            <thead>
                <tr>
                    <th class="text-center">@lang('legaladvice.registries.fields.protocol')</th>
                    <th class="text-center"><i class="fa fa-skull"></i></th>
                    <th class="text-center"><i class="fa fa-hourglass-end"></i></th>
                    <th class="text-center"><i class="fa fa-file"></i></th>
                    <th class="text-center"><i class="fa fa-share"></i></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            @foreach($items->late as $item)
                <tbody>
                    <tr data-entry-id="{{ $item->id }}">
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->protocol }}</td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ date('d/m/y', strtotime($item->deadline)) }}</td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}"><span class='badge badge-danger'>{{ $item->remainingdays }}</span></td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->files }}</td>
                        <td class="text-center {{ ($item->urgent) ? 'table-danger' : '' }}">{{ $item->procedures }}</td>
                        <td class="text-right {{ ($item->urgent) ? 'table-danger' : '' }}">
                            <a href="{{ route('legaladvice.registries.edit',[$item->id]) }}" class="btn btn-sm btn-danger"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
            @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
