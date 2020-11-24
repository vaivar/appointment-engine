<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    public function defaultEntry(Request $request) {
        return redirect('timetable/' . date("Y-m-d"));
    }

    public function manage(Request $request, $date, $salon = '0') {
        if(checkdate(intval(substr($date, 5, 2)), intval(substr($date, 8, 2)), intval(substr($date, 0, 4)))) {
            $filledDates = DB::table('timetables')->selectRaw('date, COUNT(id) as cnt')->where('date', 'like', substr($date, 0, 7) . '%')->groupBy('date')->get();
            $timeslots = DB::table('timetables')->join('salons', 'salons.id', '=', 'timetables.salon_id')->select('timetables.id', 'address', 'time', 'slots_total', 'slots_occupied')->where('date', $date);
            if($salon !== '0') {
                $timeslots = $timeslots->where('salon_id', $salon);
            }
            $timeslots = $timeslots->get();
            $params = [
                'defaultDate' => $date,
                'salons' => \App\Models\Salon::all(),
                'selectedSalon' => $salon,
                'filledDates' => $filledDates,
                'timeslots' => $timeslots
            ];
            return view('timetable', $params);
        } else {
            return redirect('timetable/' . date("Y-m-d"));
        }
    }

    public function new(Request $request, $date) {
        if(checkdate(intval(substr($date, 5, 2)), intval(substr($date, 8, 2)), intval(substr($date, 0, 4)))) {
            $params = [
                'salons' => \App\Models\Salon::all()
            ];
            return view('timeslot', $params);
        } else {
            return redirect('timetable/' . date("Y-m-d"));
        }
    }

    public function submit(Request $request, $date) {
        if(checkdate(intval(substr($date, 5, 2)), intval(substr($date, 8, 2)), intval(substr($date, 0, 4)))) {
            $times = explode(';', $request->input('times'));
            foreach($times as $time) {
                $timeslot = new \App\Models\Timeslot;
                $timeslot->slots_total = $request->input('slots');
                $timeslot->date = $date;
                $timeslot->time = $time;
                $timeslot->admin_id = Auth::id();
                $timeslot->save();
            }
            $params = [
                'defaultDate' => $date,
                'salons' => \App\Models\Salon::all(),
                'selectedSalon' => '0',
                'status' => 'Sėkmingai įrašyta'
            ];
            return view('timetable', $params);
        } else {
            return redirect('timetable/' . date("Y-m-d"));
        }
    }
}
