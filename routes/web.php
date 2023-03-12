<?php

use App\Http\Controllers\AIAdviseController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShowLoginPageController;
use App\Http\Controllers\ShowAuthPageController;
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

Route::middleware('guest')->group(function() {
    Route::get('/login', [ShowAuthPageController::class, 'login'])->name('login');
    Route::get('/register', [ShowAuthPageController::class, 'register'])->name('register');
});

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/meetings');
    //Route::get('/', ScheduleController::class)->name('schedule');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/availability', [ProfileController::class, 'addAvailability'])->name('profile.availability.add');
    Route::delete('/profile/availability/{id}', [ProfileController::class, 'deleteAvailability'])->name('profile.availability.delete');

    Route::prefix('meetings')->name('meetings.')->group(function () {
        Route::get('/availability-check', [MeetingController::class, 'availabilityCheck'])->name('availability-check');
        Route::get('/', [MeetingController::class, 'index'])->name('index');
        Route::post('/', [MeetingController::class, 'store'])->name('store');
        Route::get('/create', [MeetingController::class, 'create'])->name('create');
    });

    Route::prefix('/ai')->name('ai.')->group(function () {
        Route::get('/should-this-be-a-meeting', [AIAdviseController::class, 'shouldThisBeAMeeting'])->name('should-this-be-a-meeting');
        Route::get('/suggest-attendees', [AIAdviseController::class, 'getSuggestedAttendees'])->name('suggest-attendees');
    });
});
