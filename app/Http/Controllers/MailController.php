<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingOk;
use Symfony\Component\HttpFoundation\Response;

class MailController extends Controller
{
  public function sendEmail(Request $request) {

    // error_reporting(E_ALL);
    // var_dump(fsockopen("ssl://smtp.gmail.com", 465, $errno, $errstr));
    // var_dump($errno);
    // var_dump($errstr); die();

    $to_email = 'vaidas@vaivar.eu';

    $mailData = [
      'title' => 'Jūs užsiregistravote konsultacijai BTN',
      'url' => 'https://www.btn.lt',
      'client' => 'Test Client',
      'date' => 'Date',
      'time' => 'Time',
    ];

    Mail::to($to_email)->send(new BookingOk($mailData));

    return response()->json([
      'message' => 'Email has been sent'
    ], Response::HTTP_OK);
  }
}
