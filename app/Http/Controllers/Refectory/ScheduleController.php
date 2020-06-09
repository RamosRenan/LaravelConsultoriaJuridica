<?php

namespace App\Http\Controllers\Dentist;

use App\Models\Dentist\Dentist;
use App\Models\Dentist\Patient;
use App\Models\Dentist\Schedule;
use App\Models\Dentist\ScheduleItem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function __construct() {
        $this->middleware('check.permissions');
    }

    /**
     * Display a listing of Schedules.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $daysOfWeek = __('global.app_days_of_week');
        $constraints = ScheduleItem::where('groupId', '<>', '')
            ->distinct()
            ->get()
            ->pluck('groupId', 'groupId');
        $events = ScheduleItem::where('isEvent', true)
            ->orderby('startRecur', 'asc')
            ->orderby('start', 'asc')
            ->get();
        $patients = Patient::all()->pluck('name', 'id');
        $dentists = Dentist::all()->pluck('name', 'id');

        return view('dentist.schedules.index', compact('daysOfWeek', 'constraints', 'events', 'patients', 'dentists'));
    }

    /**
     * Show the list of existing Supplies.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduleListAjax(Request $request)
    {
        
        if ( $request->query('id') ) 
        {
            $out = ScheduleItem::where('id', $request->query('id'))
                ->first();

            $out->datestart = ($out->start == null) ? $out->startRecur : explode( ' ', $out->start )[0];
            $out->dateend = ($out->end == null) ? $out->endRecur : explode( ' ', $out->end )[0];
            $out->timestart = ($out->start == null) ? $out->startTime : explode( ' ', $out->start )[1];
            $out->timeend = ($out->end == null) ? $out->endTime : explode( ' ', $out->end )[1];
        }
        else
        {
            $items = ScheduleItem::get();

            $out = $items->map( function($item) {
                $item = $item->toArray();
                
                $new = New \StdClass;
                foreach( $item as $key => $val ) {
                    if ( $val !== null && $val !== '' )
                    {
                        $new->$key = $val;
                    }
                }
                return $new;
            });
        } 
        
        return response()->json( $out );
    }

    /**
     * Show the form for creating new Schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dentist.schedules.create');
    }

    /**
     * Store a newly created Schedule in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $items = $request->except('_method', '_token', 'id', 'dentist_id', 'patient_id', 'datestart', 'dateend', 'timestart', 'timeend', 'constraint', 'color', 'backgroundColor', 'borderColor', 'textColor', 'backgroundColor_checkbox', 'borderColor_checkbox', 'textColor_checkbox');
        if ( $request['isEvent'] == true ) {
            $items['rendering'] = 'background';
        } else {
//            $items['patient_id'] = $request['patient_id'];
//            $items['dentist_id'] = $request['dentist_id'];
            $items['constraint'] = $request['constraint'];
        }
        
        if (empty ($request['daysOfWeek']) ) {
            $items['start'] = $request['datestart'] . ' ' . $request['timestart'];
            $items['end'] = $request['dateend'] . ' ' . $request['timeend'];
        }
        else 
        {
            $items['daysOfWeek'] = json_encode( $request['daysOfWeek'] );

            $items['startRecur'] = $request['datestart'];
            $items['endRecur'] = $request['dateend'];
            $items['startTime'] = $request['timestart'];
            $items['endTime'] = $request['timeend'];
        }

        if ( $request['backgroundColor_checkbox'] == true ) $items['backgroundColor'] = $request['backgroundColor'];
        if ( $request['borderColor_checkbox'] == true ) $items['borderColor'] = $request['borderColor'];
        if ( $request['textColor_checkbox'] == true ) $items['textColor'] = $request['textColor'];

        ScheduleItem::insert($items);

        return redirect()->route('dentist.schedules.index')->with('success', __('global.app_msg_store_success'));
    }

    /**
     * Show the form for editing Schedule.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Schedule::findOrFail($id);

        return view('dentist.schedules.edit', compact('item'));
    }

    /**
     * Update Schedule in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $items = $request->except('_method', '_token', 'id', 'dentist_id', 'patient_id', 'datestart', 'dateend', 'timestart', 'timeend', 'constraint', 'color', 'backgroundColor', 'borderColor', 'textColor', 'backgroundColor_checkbox', 'borderColor_checkbox', 'textColor_checkbox');

        $fields = ['allDay', 'isEvent', 'daysOfWeek', 'groupId', 'constraint', 'editable', 'startEditable', 'durationEditable', 'overlap', 'backgroundColor', 'borderColor', 'textColor'];

        foreach ($fields as $field) {
            $items[$field] = null;
        }
        
        if ( $request['isEvent'] == true ) {
            $items['rendering'] = 'background';
        } else {
//            $items['patient_id'] = $request['patient_id'];
//            $items['dentist_id'] = $request['dentist_id'];
            $items['constraint'] = $request['constraint'];
        }
        
        if (empty ($request['daysOfWeek']) ) {
            $datestart = trim($request['datestart'] . ' ' . $request['timestart']);
            $dateend = trim($request['dateend'] . ' ' . $request['timeend']);

            if ($datestart)
                $items['start'] = $request['datestart'] . ' ' . $request['timestart'];

            if ($dateend)
                $items['end'] = $request['dateend'] . ' ' . $request['timeend'];
        }
        else 
        {
            $items['daysOfWeek'] = json_encode( $request['daysOfWeek'] );

            $items['startRecur'] = $request['datestart'];
            $items['endRecur'] = $request['dateend'];
            $items['startTime'] = $request['timestart'];
            $items['endTime'] = $request['timeend'];
        }

        if ( $request['backgroundColor_checkbox'] == true ) $items['backgroundColor'] = $request['backgroundColor'];
        if ( $request['borderColor_checkbox'] == true ) $items['borderColor'] = $request['borderColor'];
        if ( $request['textColor_checkbox'] == true ) $items['textColor'] = $request['textColor'];

dd($items);

        $item = ScheduleItem::where('id', $id)->update($items);

        return redirect()->route('dentist.schedules.index')->with('success', __('global.app_msg_update_success'));
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = ScheduleItem::findOrFail($id);
        $item->delete();

        return redirect()->route('dentist.schedules.index')->with('success', __('global.app_msg_destroy_success'));
    }

    /**
     * Delete all selected Schedules at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Schedule::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
            return redirect()->route('dentist.schedules.index')->with('success', __('global.app_msg_mass_destroy_success'));
        } else {
            return redirect()->route('dentist.schedules.index')->with('error', __('global.app_msg_mass_destroy_error'));
        }
    }
}
