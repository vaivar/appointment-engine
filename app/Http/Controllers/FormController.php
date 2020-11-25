<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function get(Request $request) {
        $salons = $this->getSalons();
        $dates = $this->getDatesForSalon($salons[0]->id);
        //$times = $this->getTimesForDate($salons[0]->id, $dates[0]->date);
        $params = [
            'salons' => $salons,
            'dates' => $dates
            //'times' => $times
        ];
        return view('form', $params);
    }

    public function submit(Request $request) {
        $salons = $this->getSalons();
        $dates = $this->getDatesForSalon($salons[0]->id);
        $params = [
            'salons' => $salons,
            'dates' => $dates,
            'status' => 'Sėkmingai išsiųsta'
        ];
        return view('form', $params);
    }

    public function getDatesForSalon($salon) {
        $timeslots = DB::table('timetables')->join('salons', 'salons.id', '=', 'timetables.salon_id')->select('date')->where('salon_id', $salon)->where('slots_total', '>', 'slots_occupied')->distinct()->get();
        return $timeslots;
    }

    public function AJAXgetDatesForSalon(Request $request) {
        return $this->getDatesForSalon($request->input('salon'))->toJson();
    }

    public function getTimesForDate($salon, $date) {
        $timeslots = DB::table('timetables')->join('salons', 'salons.id', '=', 'timetables.salon_id')->select('time')->where('salon_id', $salon)->where('slots_total', '>', 'slots_occupied')->where('date', $date)->distinct()->get();
        return $timeslots;
    }

    public function AJAXgetTimesForDate(Request $request) {
        return $this->getTimesForDate($request->input('salon'), $request->input('date'))->toJson();
    }

    private function getSalons() {
        return \App\Models\Salon::all();
    }
}
