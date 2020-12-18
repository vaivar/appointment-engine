<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingOk;
use Symfony\Component\HttpFoundation\Response;

class MailController extends Controller
{
  public function sendEmail() {
    $to_email = 'vaidas@vaivar.eu';

    $mailData = [
      'title' => 'Jūs užsiregistravote konsultacijai BTN',
      'url' => 'https://www.btn.lt'
    ];

    Mail::to($to_email)->send(new BookingOk($mailData));

    return response()->json([
      'message' => 'Email has been sent'
    ], Response::HTTP_OK);
  }
}
