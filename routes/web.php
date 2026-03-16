<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Livewire\User\CreateReservation;
use App\Http\Controllers\ProviderController;
use App\Livewire\Admins\Users;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/register',[UserController::class,'create'])->middleware('role:administrador')->name('user.create');
    Route::post('/register',[UserController::class,'store'])->middleware('role:administrador')->name('user.register');
    Route::get('/booking/new', CreateReservation::class)->middleware('role:cliente')->name('booking.create');
});
Route::get('/auth/{provider}', [ProviderController::class,'ProviderRedirect'])->where('provider', 'google|facebook')->name('auth.social.provider');
Route::get('/auth/{provider}/callback',[ProviderController::class,'ProviderCallback'] );