<!-- resources/views/pdf/reservation.blade.php -->

<div style="font-family: sans-serif;">
    <h1>Detalle de Reservación</h1>

    <p>Fecha: {{ $reservation->date_reservation }}</p>
    <p>Hora: {{ $reservation->time_reservation }}</p>

    <h3>Vehículo</h3>
    <p>{{ $reservation->vehicle->marca }} - {{ $reservation->vehicle->modelo }}</p>

    <h3>Fotos</h3>

    <div style="display: flex; gap: 20px;">
        <div>
            <p>Antes</p>
            <img src="{{ $before }}" width="300">
        </div>

        <div>
            <p>Después</p>
            <img src="{{ $after }}" width="300">
        </div>
    </div>
</div>