@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-ambulance"></i> @lang('global.dentist.schedules.title') <small>@lang('global.app_list')</small></h1>
@stop

@section('css') 
    <link href='{{ asset('vendor/fullcalendar/packages/core/main.css') }}' rel='stylesheet' />
    <link href='{{ asset('vendor/fullcalendar/packages/daygrid/main.css') }}' rel='stylesheet' />
    <link href='{{ asset('vendor/fullcalendar/packages/timegrid/main.css') }}' rel='stylesheet' />
    <link href='{{ asset('vendor/fullcalendar/packages/list/main.css') }}' rel='stylesheet' />
@stop

@section('js') 
    <script src='{{ asset('vendor/fullcalendar/packages/core/main.js') }}'></script>
    <script src='{{ asset('vendor/fullcalendar/packages/core/locales/pt-br.js') }}'></script>
    <script src='{{ asset('vendor/fullcalendar/packages/interaction/main.js') }}'></script>
    <script src='{{ asset('vendor/fullcalendar/packages/daygrid/main.js') }}'></script>
    <script src='{{ asset('vendor/fullcalendar/packages/timegrid/main.js') }}'></script>
    <script src='{{ asset('vendor/fullcalendar/packages/list/main.js') }}'></script>

    <script>
        var argsModal;

        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                header: {
                    left: 'prevYear,prev,next,nextYear today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay listMonth,listWeek,listDay'
                },
                views: {
                    listDay: { buttonText: '@lang('global.dentist.schedules.dayly_list')' },
                    listWeek: { buttonText: '@lang('global.dentist.schedules.weekly_list')' },
                    listMonth: { buttonText: '@lang('global.dentist.schedules.monthly_list')' },
                },
                defaultView: 'dayGridMonth',
                columnHeaderFormat: {
                    weekday: 'long',
                },
                slotDuration: '00:15:00',
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false,
                },
                eventTimeFormat: {

                    hour: 'numeric',
                    minute: '2-digit',
                    omitZeroMinute: false,
                },
                displayEventEnd: true,
                locale: 'pt-br',
                navLinks: true,
                selectable: true,
                selectMirror: true,
                weekNumbers: true,
                weekNumbersWithinDays: true,
                businessHours: true,
                editable: true,
                eventLimit: true,
                defaultDate: '{{ date('Y-m-d') }}',
/*
                eventConstraint: [
                    {
                    daysOfWeek: [ 1, 2, 4, 5 ],
                    startTime: '08:00',
                    endTime: '17:30'
                    },
                    {
                    daysOfWeek: [ 3 ],
                    startTime: '08:00',
                    endTime: '12:00'
                    }
                ],      
*/
                businessHours: [
                    {
                    daysOfWeek: [ 1, 2, 4, 5 ],
                    startTime: '08:00',
                    endTime: '17:30'
                    },
                    {
                    daysOfWeek: [ 3 ],
                    startTime: '08:00',
                    endTime: '12:00'
                    }
                ],

                eventClick: function(event) {
                    openModal(event.event.id);
                },

                eventResize: function(event) {
                    alert(event.event.title + " end is now " + event.event.end.toISOString());

                    if (!confirm("is this okay?")) {
                        event.revert();
                    }
                },

                eventDrop: function(event) {
                    alert(event.event.title + " was dropped on " + event.event.start.toISOString());

                    if (!confirm("Are you sure about this change?")) {
                        event.revert();
                    }
                },

                select: function(event) {
                    argsModal = event;

                    $('#modalScheduleItem').modal();
                    
                    calendar.unselect()
                },

                eventOverlap: function(stillEvent, movingEvent) {
                    return stillEvent.allDay && movingEvent.allDay;
                },
      
                eventSources: [
                    '{{ route('dentist.schedules.list') }}',
                ],
            });

            calendar.render();

            openModal = function(id) {
                $.ajax({
                        url: '{{ route('dentist.schedules.list') }}',
                        data: { id: id },
                        success: function( data ) {
                            argsModal = data;

                            $('#modalScheduleItem').modal();
                        }
                })
            }

            doCheck = function(obj, objs, condition = true) {
                obj = (typeof obj === 'object') ? obj : $(obj)[0]

                if (obj.checked == condition) {
                    $.each(objs, function( index, value ) {
                        $(value).show()
                        $(value +'_label').show()
                    });
                } else {
                    $.each(objs, function( index, value ) {
                        $(value).hide()
                        $(value +'_label').hide()
                    });
                }
            }
            
            $('#modalScheduleItem').on('show.bs.modal', function (event) {
                var modal = $(this)

                if (argsModal.startStr == undefined) 
                {
                    var datestart = argsModal.datestart
                    var dateend = argsModal.dateend
                    var timestart = argsModal.timestart
                    var timeend = argsModal.timeend
                }
                else
                {
                    var dtstart = argsModal.startStr.split('T')
                    var dtend = argsModal.endStr.split('T')

                    var datestart = dtstart[0]
                    var dateend = dtend[0]

                    if (dtstart[1] != undefined)
                        var timestart = dtstart[1].substr(0,5)

                    if (dtend[1] != undefined)
                        var timeend = dtend[1].substr(0,5)
                }

                modal.find('#datestart').val( datestart );
                modal.find('#dateend').val( dateend );
                modal.find('#timestart').val( timestart );
                modal.find('#timeend').val( timeend );
                modal.find('.schedule_id').val(argsModal.id);
                modal.find('#allDay').prop('checked', argsModal.allDay);
                modal.find('#isEvent').prop('checked', argsModal.isEvent);
                modal.find('#daysOfWeek').val(argsModal.daysOfWeek);
                modal.find('#groupId').val(argsModal.groupId);
                modal.find('#constraint').val(argsModal.constraint);
                modal.find('#dentist_id').val(argsModal.dentist_id);
                modal.find('#patient_id').val(argsModal.patient_id);
                modal.find('#editable').prop('checked', argsModal.editable);
                modal.find('#startEditable').prop('checked', argsModal.startEditable);
                modal.find('#durationEditable').prop('checked', argsModal.durationEditable);
                modal.find('#overlap').prop('checked', argsModal.overlap);
                
                if (argsModal.backgroundColor)
                    modal.find('#backgroundColor_checkbox').prop('checked', argsModal.backgroundColor);

                if (argsModal.backgroundColor)
                    modal.find('#borderColor_checkbox').prop('checked', argsModal.borderColor);

                if (argsModal.backgroundColor)
                    modal.find('#textColor_checkbox').prop('checked', argsModal.textColor);

                modal.find('#backgroundColor').val(argsModal.backgroundColor);
                modal.find('#borderColor').val(argsModal.borderColor);
                modal.find('#textColor').val(argsModal.textColor);

                $('#allDay').click(function() { doCheck( this, ['#timestart', '#timeend'], false ); });
                $('#isEvent').click(function() { doCheck( this, ['#people', '#constraintBox', '#borderColorBox', '#textColorBox'], false ); });
                $('#backgroundColor_checkbox').click(function() { doCheck( this, ['#backgroundColor'] ); });
                $('#borderColor_checkbox').click(function() { doCheck( this, ['#borderColor'] ); });
                $('#textColor_checkbox').click(function() { doCheck( this, ['#textColor'] ); });

                doCheck( '#allDay', ['#timestart', '#timeend'], false );
                doCheck( '#isEvent', ['#people', '#constraintBox', '#borderColorBox', '#textColorBox'], false );
                doCheck( '#backgroundColor_checkbox', ['#backgroundColor'] );
                doCheck( '#borderColor_checkbox', ['#borderColor'] );
                doCheck( '#textColor_checkbox', ['#textColor'] );

                if ( argsModal.id ) {
                    const actionAddEditForm = "{{ route("dentist.schedules.update", ':id') }}";
                    const actionDestroyForm = "{{ route("dentist.schedules.destroy", ':id') }}";

                    modal.find('.schedule_method').val( "PUT" );
                    modal.find('#add_edit_item').attr( "action", actionAddEditForm.replace( ":id", argsModal.id ) );
                    modal.find('#delete_item').attr( "action", actionDestroyForm.replace( ":id", argsModal.id ) );

                    modal.find('#buttonEdit').show();
                    modal.find('#buttonAdd').hide();
                    modal.find('#buttonDestroy').show();
                } else {
                    modal.find('.schedules_method').val( "POST" );
                    modal.find('#add_edit_item').attr( "action", "{{ route("dentist.schedules.store")}}" );

                    modal.find('#buttonEdit').hide();
                    modal.find('#buttonAdd').show();
                    modal.find('#buttonDestroy').hide();
                }
            });
        });


        var route = '{{ route('dentist.schedules.mass_destroy') }}';
        var msg   = '{{ trans("global.app_are_you_sure") }}';
    </script>
@stop

@section('content')
    <div class="modal fade bd-example-modal-lg" id="modalScheduleItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('global.app_close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">@lang('global.menu.edit_menu_item')</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                        {!! Form::model([], ['id' => "add_edit_item"]) !!}
                            {!! Form::hidden('_method', old('method'), ['class' => 'schedule_method']) !!}
                            {!! Form::hidden('id', old('id'), ['class' => 'schedule_id']) !!}
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    {!! Form::label('datestart', __('global.dentist.schedules.fields.datestart').'*', ['class' => 'control-label']) !!}
                                    {!! Form::date('datestart', old('datestart'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    @if($errors->has('datestart'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('datestart') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3 form-group">
                                    {!! Form::label('dateend', __('global.dentist.schedules.fields.dateend').'*', ['class' => 'control-label']) !!}
                                    {!! Form::date('dateend', old('dateend'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    @if($errors->has('dateend'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('dateend') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-2 form-group">
                                    {!! Form::label('allDay', __('global.dentist.schedules.fields.all_day'), ['class' => 'control-label']) !!}<br />
                                    {!! Form::checkbox('allDay', true, old('allDay')) !!}
                                    @if($errors->has('allDay'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('allDay') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-2 form-group">
                                    {!! Form::label('timestart', __('global.dentist.schedules.fields.timestart').'*', ['id' => 'timestart_label', 'class' => 'control-label']) !!}
                                    {!! Form::time('timestart', old('timestart'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    @if($errors->has('timestart'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('timestart') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-2 form-group">
                                    {!! Form::label('timeend', __('global.dentist.schedules.fields.timeend').'*', ['id' => 'timeend_label', 'class' => 'control-label']) !!}
                                    {!! Form::time('timeend', old('timeend'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    @if($errors->has('timeend'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('timeend') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    {!! Form::label('isEvent', __('global.dentist.schedules.fields.is_event'), ['class' => 'control-label']) !!}<br />
                                    {!! Form::checkbox('isEvent', true, old('type')) !!}
                                    @if($errors->has('isEvent'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('type') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-10 form-group">
                                    {!! Form::label('daysOfWeek', __('global.dentist.schedules.fields.days_of_week'), ['class' => 'control-label']) !!}<br />
                                    {!! Form::select('daysOfWeek[]', $daysOfWeek, 123, ['style' => 'width: 100%;', 'class' => 'form-control select2', 'placeholder' => '', 'required' => '', 'multiple' => '']) !!}
                                    @if($errors->has('daysOfWeek'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('daysOfWeek') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {!! Form::label('groupId', __('global.dentist.schedules.fields.group_id'), ['class' => 'control-label']) !!}
                                    {!! Form::text('groupId', old('groupId'), ['class' => 'form-control', 'placeholder' => '']) !!}
                                    @if($errors->has('groupId'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('groupId') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group" id="constraintBox">
                                    {!! Form::label('constraint', __('global.dentist.schedules.fields.constraint'), ['class' => 'control-label']) !!}
                                    {!! Form::select('constraint', $constraints, old('constraint'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    @if($errors->has('constraint'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('constraint') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row" id="people">
                                <div class="col-md-6 form-group">
                                    {!! Form::label('patient_id', __('global.dentist.schedules.fields.patient').'*', ['class' => 'control-label']) !!}
                                    {!! Form::select('patient_id', $patients, old('patient_id'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    @if($errors->has('patient_id'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('patient_id') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            {!! Form::label('dentist_id', __('global.dentist.schedules.fields.dentist').'*', ['class' => 'control-label']) !!}
                                            {!! Form::select('dentist_id', $dentists, old('dentists'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                            @if($errors->has('dentist_id'))
                                                <div class="form-group has-error">
                                                    <span class="help-block">{{ $errors->first('dentist_id') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    {!! Form::label('editable', __('global.dentist.schedules.fields.editable'), ['class' => 'control-label']) !!}
                                    {!! Form::checkbox('editable', true, old('editable')) !!}
                                    @if($errors->has('editable'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('editable') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3 form-group">
                                    {!! Form::label('startEditable', __('global.dentist.schedules.fields.start_editable'), ['class' => 'control-label']) !!}
                                    {!! Form::checkbox('startEditable', true, old('startEditable')) !!}
                                    @if($errors->has('startEditable'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('startEditable') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3 form-group">
                                    {!! Form::label('durationEditable', __('global.dentist.schedules.fields.duration_editable'), ['class' => 'control-label']) !!}
                                    {!! Form::checkbox('durationEditable', true, old('durationEditable')) !!}
                                    @if($errors->has('durationEditable'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('durationEditable') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3 form-group">
                                    {!! Form::label('overlap', __('global.dentist.schedules.fields.overlap'), ['class' => 'control-label']) !!}
                                    {!! Form::checkbox('overlap', true, old('overlap')) !!}
                                    @if($errors->has('overlap'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('overlap') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group" id="backgroundColorBox">
                                    {!! Form::label('backgroundColor_checkbox', __('global.dentist.schedules.fields.backgroundColor'), ['class' => 'control-label']) !!}
                                    {!! Form::checkbox('backgroundColor_checkbox', true, old('backgroundColor_checkbox')) !!}
                                    {!! Form::color('backgroundColor', old('backgroundColor'), ['id' => 'backgroundColor', 'class' => 'form-control', 'placeholder' => '']) !!}
                                    @if($errors->has('backgroundColor'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('backgroundColor') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group" id="borderColorBox">
                                    {!! Form::label('borderColor_checkbox', __('global.dentist.schedules.fields.borderColor'), ['class' => 'control-label']) !!}
                                    {!! Form::checkbox('borderColor_checkbox', true, old('borderColor_checkbox')) !!}
                                    {!! Form::color('borderColor', old('borderColor'), ['id' => 'borderColor', 'class' => 'form-control', 'placeholder' => '']) !!}
                                    @if($errors->has('borderColor'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('backgroundColor') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group" id="textColorBox">
                                    {!! Form::label('textColor_checkbox', __('global.dentist.schedules.fields.textColor'), ['class' => 'control-label']) !!}
                                    {!! Form::checkbox('textColor_checkbox', true, old('textColor_checkbox')) !!}
                                    {!! Form::color('textColor', old('textColor'), ['id' => 'textColor', 'class' => 'form-control', 'placeholder' => '']) !!}
                                    @if($errors->has('textColor'))
                                        <div class="form-group has-error">
                                            <span class="help-block">{{ $errors->first('textColor') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        {!! Form::close() !!}

                        {!! Form::open(array(
                            'id' => "delete_item",
                            'style' => 'display: inline-block;',
                            'method' => 'DELETE',
                            'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                            'route' => ['dentist.schedules.destroy', ':id'])) !!}
                        {!! Form::hidden('mode', 'delete_menu_item') !!}
                        {!! Form::close() !!}

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            {!! Form::button('<i class="fa fa-trash"></i> ' . trans('global.app_delete'), ['id' => "buttonDestroy", 'onClick' => '$("#delete_item").submit();', 'class' => 'btn btn-danger pull-left']) !!}
                        </div>
                        <div class="col-md-6 form-group">
                            {!! Form::button('<i class="fa fa-edit"></i> ' . trans('global.app_edit'), ['id' => "buttonEdit",'onClick' => '$("#add_edit_item").submit();',  'class' => 'btn btn-primary']) !!}
                            {!! Form::button('<i class="fa fa-plus"></i> ' . trans('global.app_create'), ['id' => "buttonAdd",'onClick' => '$("#add_edit_item").submit();',  'class' => 'btn btn-success']) !!}
                            <button type="button" class="btn btn-warning" data-dismiss="modal">@lang('global.app_close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <b>@lang('global.dentist.schedules.fields.events')</b>
                    @foreach ($events as $event)
                    {!! Form::button('ssss', ['style' => 'background-color: ' . $event->backgroundColor , 'onClick' => 'openModal(' . $event->id . ');',  'class' => 'btn']) !!}
                    @endforeach
                </div>
            </div>
        </div>
        <div id='calendar'></div>
    </div>
@endsection
