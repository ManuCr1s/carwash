<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Livewire\User\CreateReservation;
use App\Livewire\User\ResponseRequest;
use App\Http\Controllers\ProviderController;
use App\Livewire\Admins\Users;
use App\Livewire\User\DispatchReservation; 
use App\Livewire\User\ReportReservation;
use App\Livewire\User\CreateUser;
use App\Http\Controllers\CreatePdfController;

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/user',CreateUser::class)->middleware('role:ADMINISTRADOR')->name('create.user');
    Route::get('/vehicle',[VehicleController::class,'create'])->middleware('role:CLIENTE')->name('vehicle.create');
    Route::get('/booking/new', CreateReservation::class)->middleware('role:CLIENTE')->name('booking.create');
    Route::get('/response/request',ResponseRequest::class)->middleware('role:USUARIO')->name('response.request');
    Route::get('/response/dispatch', DispatchReservation::class)->middleware('role:USUARIO')->name('dispatch.create');
    Route::get('/response/report', ReportReservation::class)->middleware('role:USUARIO')->name('report.reservation');
    Route::get('/response/{id}/print', [CreatePdfController::class, 'print'])->name('reservation.print');
});
Route::get('/auth/{provider}', [ProviderController::class,'ProviderRedirect'])->where('provider', 'google|facebook')->name('auth.social.provider');
Route::get('/auth/{provider}/callback',[ProviderController::class,'ProviderCallback'] );