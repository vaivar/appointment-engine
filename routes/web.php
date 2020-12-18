<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FormController::class, 'get']);
Route::post('/', [FormController::class, 'submit'])->middleware(ProtectAgainstSpam::class);

Route::get('/ajax/getDatesForSalon', [FormController::class, 'AJAXgetDatesForSalon']);
Route::get('/ajax/getTimesForDate', [FormController::class, 'AJAXgetTimesForDate']);

Route::get('/login', function() {
    return view('login');
})->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/timetable', [TimetableController::class, 'get'])->middleware('auth');
Route::get('/timetable/{date}/{salon?}', [TimetableController::class, 'manage'])->middleware('auth');

Route::get('/timeslot/{date}/new', [TimetableController::class, 'new'])->middleware('auth');
Route::post('/timeslot/{date}/new', [TimetableController::class, 'submit'])->middleware('auth');
Route::get('/timeslot/{date}/{id}/delete', [TimetableController::class, 'delete'])->middleware('auth');
Route::get('/timeslot/{date}/{id}/edit', [TimetableController::class, 'edit'])->middleware('auth');
Route::post('/timeslot/{date}/{id}/edit', [TimetableController::class, 'editSubmit'])->middleware('auth');

Route::get('/appointments', [AppointmentController::class, 'get'])->middleware('auth');
Route::get('/appointments/{date}/{salon?}', [AppointmentController::class, 'manage'])->middleware('auth');
Route::get('/appointments/{date}/{id}/delete', [AppointmentController::class, 'delete'])->middleware('auth');

Route::get('/send-email', [MailController::class, 'sendEmail']);