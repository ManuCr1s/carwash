<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Models\Vehicle;
use App\Models\Photo;
use App\Models\Order;

class CreatePdfController extends Controller
{
    public function print($id)
        {
            $reservation = Reservation::with(['user','vehicle.model.brand','service','order.photos'])->findOrFail($id);
            $order = $reservation->order;

            return Pdf::view('pdf.report', [
                'reservation' => $reservation,
                'vehicle'     => $reservation->vehicle,
                'order'       => $order,
                'photos'      => $order ? $order->photos : collect(),
            ])
            ->format('a4')
            ->name('reservacion.pdf');
        }
}
