<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/attendance/fetch', [AttendanceController::class, 'fetch']);
Route::get('/attendance', [AttendanceController::class, 'index']);
Route::get('/attendance/view', [AttendanceController::class, 'view']);
