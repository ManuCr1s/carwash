<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Livewire\User\CreateReservation;
use App\Livewire\User\ResponseRequest;
use App\Http\Controllers\ProviderController;
use App\Livewire\Admins\Users;
use App\Livewire\User\DispatchReservation; 
use App\Livewire\User\ReportReservation;
use App\Http\Controllers\CreatePdf;

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/register',[UserController::class,'create'])->middleware('role:administrador')->name('user.create');
    Route::post('/register',[UserController::class,'store'])->middleware('role:administrador')->name('user.register');
    Route::get('/booking/new', CreateReservation::class)->middleware('role:cliente')->name('booking.create');
    Route::get('/response/request',ResponseRequest::class)->middleware('role:usuario')->name('response.request');
    Route::get('/response/dispatch', DispatchReservation::class)->middleware('role:usuario')->name('dispatch.create');
    Route::get('/response/report', ReportReservation::class)->middleware('role:usuario')->name('report.reservation');
    Route::get('/response/{id}/print', [CreatePdf::class, 'print'])->name('reservation.print');
});
Route::get('/auth/{provider}', [ProviderController::class,'ProviderRedirect'])->where('provider', 'google|facebook')->name('auth.social.provider');
Route::get('/auth/{provider}/callback',[ProviderController::class,'ProviderCallback'] );