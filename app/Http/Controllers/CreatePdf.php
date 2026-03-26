<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Models\Vehicle;
class CreatePdf extends Controller
{
    public function print($id)
        {
            $reservation = Reservation::with('vehicle')->findOrFail($id);

            return Pdf::view('pdf.report', [
                'reservation' => $reservation,
                'before' => asset('storage/'.$reservation->before_photo),
                'after' => asset('storage/'.$reservation->after_photo),
            ])
            ->format('a4')
            ->name('reservacion.pdf');
        }
}
