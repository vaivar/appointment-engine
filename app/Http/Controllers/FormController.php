<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingOk;
// use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FormController extends Controller
{
  private function _sendEmail($appointment = null) {
	$to_email = ['edita@btn.lt', 'arunas@btn.lt', 'andrius.rotar@btn.lt', 'ilda.jancis@electrolux.com'];	// email recipients at btn
    $to_email[] = $appointment->email;	// production
    //$to_email = 'avavaus@gmail.com';	// staging

    $mailData = [
      'title' => 'Jūs užsiregistravote konsultacijai BTN',
      'url' => 'https://www.btn.lt',
      'client' => $appointment->full_name,
      'date' => $appointment->date,
      'time' => $appointment->time,
      'consultant' => $appointment->consultant,
    ];

    Mail::to($to_email)->send(new BookingOk($mailData));

    return;
    // return response()->json([
    //   'message' => 'Email has been sent'
    // ], Response::HTTP_OK);
  }

    public function get(Request $request) {
        $referrer = request()->headers->get('referer');
        $referrer = true;
        if ($referrer) {
            $salons = $this->getSalons();
            $dates = $this->getDates();
            $params = [
                'salons' => $salons,
                'dates' => $dates,
            ];
            return view('form', $params);
        }
        die();
    }

    public function submit(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $salons = $this->getSalons();
            $dates = $this->getDates();
            $params = [
                'salons' => $salons,
                'dates' => $dates,
                'errors' => $validator->errors(),
            ];
            return view('form', $params);
        }

        $params = [];
        $consultant = $request->input('salon');

        $timeSlot = \App\Models\Timeslot::where([
            ['date', $request->input('date')],
            ['time', $request->input('time')]
            ])
            ->whereColumn('slots_total', '>', 'slots_occupied');
        if($consultant !== '0') {
            $timeSlot->where('salon_id', $consultant);
        }
        $timeSlot = $timeSlot->first();

        if($timeSlot) {
            $appointment = new \App\Models\FormSubmission;
            $appointment->full_name = $request->input('name');
            $appointment->phone = $request->input('phone');
            $appointment->email = $request->input('email');
            $appointment->message = $request->input('message');
            $appointment->salon_id = $consultant!=='0'? $consultant : $timeSlot->salon_id;
            $appointment->timetable_id = $timeSlot->id;
            $appointment->save();
            $timeSlot->slots_occupied += 1;
            $timeSlot->save();
            $appointment['date'] = $timeSlot->date;
            $appointment['time'] = $timeSlot->time;
            $appointment['consultant'] = DB::table('salons')
                ->where('id', $appointment['salon_id'])
                ->first()
                ->address;
            $this->_sendEmail($appointment); // send an email to the client and btn
            $params['status'] = 'Užregistravome. Patvirtinimą gausite e-paštu.';
        } else {
            $params['status'] = 'Gal nenurodėte laiko arba šį laiką ką tik užėmė.';
        }

        $salons = $this->getSalons();
        $dates = $this->getDates();
        $params['salons'] = $salons;
        $params['dates'] = $dates;
        return view('form', $params);
    }

    public function getDates($salon='0') {
        $dateToday = Carbon::now()->toDateString();
        $timeSlotsAll = DB::table('timetables')
            ->select('date')
            ->where('date', '>=', $dateToday)
            ->whereColumn('slots_total', '>', 'slots_occupied')
            ->distinct();
        if ($salon === '0') {
            return $timeSlotsAll->get();
        }
        $timeSlots = $timeSlotsAll
            ->join('salons', 'salons.id', '=', 'timetables.salon_id')
            ->where('salon_id', $salon);
        return $timeSlots->get();
    }

    // public function getDatesForSalon($salon) {
    //     $timeslots = DB::table('timetables')
    //     ->join('salons', 'salons.id', '=', 'timetables.salon_id')
    //     ->select('date')
    //     ->where('salon_id', $salon)
    //     ->whereColumn('slots_total', '>', 'slots_occupied')
    //     ->distinct()
    //     ->get();
    //     return $timeslots;
    // }

    public function AJAXgetDatesForSalon(Request $request) {
        return $this->getDates($request->input('salon'))->toJson();
    }

    public function getTimesForDate($salon, $date) {
        $timeslotsAll = DB::table('timetables')
            ->select('time')
            ->where('date', $date)
            ->whereColumn('slots_total', '>', 'slots_occupied')
            ->orderBy('time', 'asc')
            ->distinct();
        if ($salon ==='0') {
            return $timeslotsAll->get();
        }
        $timeslots = $timeslotsAll
            ->where('salon_id', $salon);
        return $timeslots->get();
    }

    public function AJAXgetTimesForDate(Request $request) {
        return $this->getTimesForDate($request->input('salon'), $request->input('date'))->toJson();
    }

    private function getSalons() {
        return \App\Models\Salon::all();
    }
}
