<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::get('registration', [DashboardController::class, 'registration'])->name('registration');
Route::post('registrationSubmit', [DashboardController::class, 'registrationSubmit'])->name('registrationSubmit');
Route::post('removeImage', [DashboardController::class, 'removeImage'])->name('removeImage');
Route::get('email', [DashboardController::class, 'email'])->name('email');
Route::post('get-state', [DashboardController::class, 'getState'])->name('getState');
Route::get('seeker', [DashboardController::class, 'seeker'])->name('seeker');
Route::post('seekerFormSubmission', [DashboardController::class, 'seekerFormSubmission'])->name('seekerFormSubmission');



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'passwordCheck'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'home'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [DashboardController::class, 'profileUpdate'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // trip
    Route::get('my-trip', [DashboardController::class, 'myTrip'])->name('mytrip');
    Route::get('tripDetails', [DashboardController::class, 'tripDetails'])->name('tripDetails');
    
    Route::post('summary', [DashboardController::class, 'summary'])->name('summary');
    Route::get('my-point', [DashboardController::class, 'myPoint'])->name('myPoint');
    Route::get('how-climb-tier', [DashboardController::class, 'climbTier'])->name('climbTier');
    Route::get('how-to-earn', [DashboardController::class, 'howToEarn'])->name('howToEarn');
    Route::get('redeem-point', [DashboardController::class, 'redeemPoint'])->name('redeemPoint');
    Route::get('transfer-point', [DashboardController::class, 'transferPoint'])->name('transferPoint');
    Route::post('transfer-point', [DashboardController::class, 'transferCoin'])->name('transferCoin');
    Route::get('enquiry', [DashboardController::class, 'enquiry'])->name('enquiry');
    Route::post('enquiry-submit', [DashboardController::class, 'enquirySubmit'])->name('enquirySubmit');
    Route::get('user-profile', [DashboardController::class, 'profile'])->name('user-profile');
    Route::get('change-passwords', [DashboardController::class, 'password'])->withoutMiddleware('passwordCheck')->name('change-passwords');
    Route::post('get-price', [DashboardController::class, 'getPrice'])->name('getPrice');
    Route::get('getExpiryDate', [DashboardController::class, 'nearestExpiringPoint'])->name('getExpiryDate');
    Route::get('faq', [DashboardController::class, 'faq'])->name('faq');
});

require __DIR__.'/auth.php';