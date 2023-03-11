<?php

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
    Route::get('/', ScheduleController::class)->name('schedule');
    Route::get('/profile', ProfileController::class)->name('profile');

    Route::prefix('meetings')->name('meetings.')->group(function () {
        Route::get('/', [MeetingController::class, 'index'])->name('index');
        Route::get('/create', [MeetingController::class, 'create'])->name('create');
    });
});
