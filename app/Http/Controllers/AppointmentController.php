<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function get(Request $request) {
        return redirect('appointments/' . date("Y-m-d"));
    }

    public function manage(Request $request, $date, $salon = '0') {
        if(checkdate(intval(substr($date, 5, 2)), intval(substr($date, 8, 2)), intval(substr($date, 0, 4)))) {
            $filledDates = DB::table('appointments')->join('timetables', 'timetables.id', '=', 'appointments.timetable_id')->selectRaw('date, COUNT(appointments.id) as cnt')->where('date', 'like', substr($date, 0, 7) . '%')->groupBy('date')->get();
            $appointments = DB::table('appointments')->join('timetables', 'timetables.id', '=', 'appointments.timetable_id')->join('salons', 'salons.id', '=', 'appointments.salon_id')->select('appointments.id','full_name', 'phone', 'email', 'message', 'address', 'date', 'time')->where('date', $date);
            if($salon !== '0') {
                $appointments = $appointments->where('appointments.salon_id', $salon);
            }
            $appointments = $appointments->get();
            $params = [
                'defaultDate' => $date,
                'salons' => \App\Models\Salon::all(),
                'selectedSalon' => $salon,
                'filledDates' => $filledDates,
                'appointments' => $appointments
            ];
            return view('appointments', $params);
        } else {
            return redirect('appointments/' . date("Y-m-d"));
        }
    }

    public function delete(Request $request, $date, $id) {
        if(checkdate(intval(substr($date, 5, 2)), intval(substr($date, 8, 2)), intval(substr($date, 0, 4)))) {
            $appointment = \App\Models\FormSubmission::find($id);
            $timeslot = \App\Models\Timeslot::find($appointment->timetable_id);
            $timeslot->slots_occupied -= 1;
            $timeslot->save();
            $appointment->delete();
            return $this->manage($request, $date);
        } else {
            return redirect('timetable/' . date("Y-m-d"));
        }
    }
}
