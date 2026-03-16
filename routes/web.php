<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Livewire\User\CreateReservation;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/register',[UserController::class,'create'])->name('user.create');
    Route::post('/register',[UserController::class,'store'])->name('user.register');
    Route::get('/booking/new', CreateReservation::class)->name('booking.create');
});
