<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TripApiController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\CustomerApiController;

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
Route::get('/auth/token', [TokenController::class, 'getToken']);
Route::middleware(['api.token'])->group(function () {
    Route::post('/trips/create', [TripApiController::class, 'create']);
    Route::post('/customers/create', [CustomerApiController::class, 'store']);
    Route::put('/customers/update/{id}', [CustomerApiController::class, 'update']);
    Route::post('/addmember',[CustomerApiController::class,'addMembers']);
    Route::post('/createBooking',[BookingApiController::class,'createBooking']);
});