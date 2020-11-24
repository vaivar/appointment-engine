<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TimetableController;

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

Route::get('/', function () {
    return view('form');
});

Route::get('/login', function() {
    return view('login');
})->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/timetable', [TimetableController::class, 'defaultEntry'])->middleware('auth');
Route::get('/timetable/{date}/{salon?}', [TimetableController::class, 'manage'])->middleware('auth');

Route::get('/timeslot/{date}/new', [TimetableController::class, 'new'])->middleware('auth');
Route::post('/timeslot/{date}/new', [TimetableController::class, 'submit'])->middleware('auth');

Route::get('/appointments', function() {
    return view('appointments');
})->middleware('auth');

