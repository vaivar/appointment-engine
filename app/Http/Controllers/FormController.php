<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingOk;
// use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
  private function _sendEmail($forEmail = null) {
	$to_email = ['edita@btn.lt', 'arunas@btn.lt', 'andrius.rotar@btn.lt', 'ilda.jancis@electrolux.com'];	// email recipients at btn
    $to_email[] = $forEmail->email;	// production
    //$to_email = 'avavaus@gmail.com';	// staging

    $mailData = [
      'title' => 'Jūs užsiregistravote konsultacijai BTN',
      'url' => 'https://www.btn.lt',
      'client' => $forEmail->full_name,
      'date' => $forEmail->date,
      'time' => $forEmail->time,
    ];

    Mail::to($to_email)->send(new BookingOk($mailData));

    return;
    // return response()->json([
    //   'message' => 'Email has been sent'
    // ], Response::HTTP_OK);
  }

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
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|min:9|max:12',
            'email' => 'required|email|max:255',
            'message' => 'required'
        ]);

        $params = [];

        if(\App\Models\Salon::where('id', $request->input('salon'))->exists()){
            $timeslot = \App\Models\Timeslot::where([['salon_id', $request->input('salon')], ['date', $request->input('date')], ['time', $request->input('time')]])->whereColumn('slots_total', '>', 'slots_occupied')->first();
            if($timeslot) {
                $appointment = new \App\Models\FormSubmission;
                $appointment->full_name = $request->input('name');
                $appointment->phone = $request->input('phone');
                $appointment->email = $request->input('email');
                $appointment->message = $request->input('message');
                $appointment->salon_id = $request->input('salon');
                $appointment->timetable_id = $timeslot->id;
                $appointment->save();
                $timeslot->slots_occupied += 1;
                $timeslot->save();
                $appointment['date'] = $timeslot->date;
                $appointment['time'] = $timeslot->time;
                $this->_sendEmail($appointment); // send an email to the client and btn
                $params['status'] = 'Sėkmingai išsiųsta';
            } else {
                $params['status'] = 'Atsiprašome, šį laiką ką tik užėmė';
            }
        } else {
            $params['status'] = 'Klaida';
        }

        $salons = $this->getSalons();
        $dates = $this->getDatesForSalon($salons[0]->id);
        $params['salons'] = $salons;
        $params['dates'] = $dates;
        return view('form', $params);
    }

    public function getDatesForSalon($salon) {
        $timeslots = DB::table('timetables')->join('salons', 'salons.id', '=', 'timetables.salon_id')->select('date')->where('salon_id', $salon)->whereColumn('slots_total', '>', 'slots_occupied')->distinct()->get();
        return $timeslots;
    }

    public function AJAXgetDatesForSalon(Request $request) {
        return $this->getDatesForSalon($request->input('salon'))->toJson();
    }

    public function getTimesForDate($salon, $date) {
        $timeslots = DB::table('timetables')->join('salons', 'salons.id', '=', 'timetables.salon_id')->select('time')->where([['salon_id', $salon], ['date', $date]])->whereColumn('slots_total', '>', 'slots_occupied')->distinct()->get();
        return $timeslots;
    }

    public function AJAXgetTimesForDate(Request $request) {
        return $this->getTimesForDate($request->input('salon'), $request->input('date'))->toJson();
    }

    private function getSalons() {
        return \App\Models\Salon::all();
    }
}
