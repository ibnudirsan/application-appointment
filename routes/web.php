<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Route::get('/api/users',[UserController::class,'index']);
Route::post('/api/users',[UserController::class,'store']);
Route::patch('/api/users/{user}/change-role',[UserController::class,'changeRole']);
Route::put('/api/users/{user}',[UserController::class,'update']);
Route::delete('/api/users/{user}',[UserController::class,'destroy']);
Route::delete('/api/users',[UserController::class,'bulkDelete']);

Route::get('/api/appointments', [AppointmentController::class, 'index']);
Route::get('/api/appointments-status',[AppointmentController::class,'statusWithCount']);
Route::post('/api/appointments/create',[AppointmentController::class,'store']);
Route::get('/api/clients', [AppointmentController::class, 'clients']);
Route::get('/api/appointments/{appointment}/edit', [AppointmentController::class, 'edit']);
Route::put('/api/appointments/{appointment}/update', [AppointmentController::class, 'update']);
Route::delete('/api/appointments/{appointment}', [AppointmentController::class, 'destroy']);

Route::get('{view}', ApplicationController::class)->where('view', '(.*)');