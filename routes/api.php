<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 
Route::apiResource('events', EventController::class);
// The attendees does not exist on their own, they always need to be associated with an event
Route::apiResource('events.attendees', AttendeeController::class)
->scoped(['attendee' => 'events']);
// Attendee will be part in the event
// -> scope() means that if you would use route model binding to get an attendee, Laravel will
// now go to terminal and enter the php artisan route:list command to see the list of routes and its parameters and endpoints.

// Route::apiResource();