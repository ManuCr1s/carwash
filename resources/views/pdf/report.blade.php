<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4; margin: 0; }
        body { font-family: 'Helvetica', sans-serif; color: #1a202c; }
    </style>
</head>
<body class="p-10 bg-white">

    <div class="flex justify-between items-center mb-6">
        <div class="flex flex-col">
            <img src="{{ asset('img/logo_lavame.png') }}" alt="Logo" class="h-20 w-auto">
        </div>
        <div class="text-right">
            <h2 class="text-lg font-bold text-gray-600">ORDEN DE LAVADO</h2>
            <p class="text-xl font-black text-blue-900">N° {{$order->id ?? 'AUN NO REGISTRADO'}}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8 border-y-2 py-6 border-gray-100">
        <div class="space-y-1">
            <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Información del Cliente</h3>
            <p class="text-md"><strong>NOMBRE:</strong> {{$reservation->user->name .' '.$reservation->user->lastname?? 'AUN NO REGISTRADO'}}</p>
            <p class="text-md text-gray-700"><strong>DNI/RUC:</strong> {{ $reservation->user->dni ??  'AUN NO REGISTRADO'}}</p>
            <p class="text-md text-gray-700"><strong>TELÉFONO:</strong> {{ $reservation->user->phone ??  'AUN NO REGISTRADO'}}</p>
        </div>
        <div class="space-y-1 text-right border-l pl-8 border-gray-100">
            <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Detalles del Vehículo</h3>
            <p class="text-md"><strong>VEHÍCULO:</strong>  {{$reservation->vehicle->model->brand->name ?? 'AUN NO REGISTRADO' }} - {{$reservation->vehicle->model->name ?? 'AUN NO REGISTRADO'}}</p>
            <p class="text-md text-gray-700"><strong>PLACA:</strong> <span class="font-black text-blue-900">{{$reservation->vehicle->placa ?? 'AUN NO REGISTRADO'}}</span></p>
            <p class="text-md font-bold text-blue-700 uppercase">{{ $reservation->service?->name ?? 'AUN NO REGISTRADO' }}</p>
        </div>
    </div>

    <div class="mb-8">
        <h3 class="text-xs font-black text-blue-900 uppercase mb-3 border-b pb-1">📸 Inspección Inicial (Antes)</h3>
        <div class="grid grid-cols-3 gap-3">
            @foreach($reservation->order->photos->where('type_photo', 1) as $photo)
                <div class="rounded-lg overflow-hidden border-2 border-gray-100">
                    <img src="{{ public_path('storage/' . $photo->url_image) }}" class="w-full h-48 object-cover">
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-8">
        @if ($order->date_end !== null )
            <h3 class="text-xs font-black text-green-800 uppercase mb-3 border-b pb-1">✨ Entrega Final (Después)</h3>
        @endif
        <div class="grid grid-cols-3 gap-3">
            @foreach($reservation->order->photos->where('type_photo', 0) as $photo)
                <div class="rounded-lg overflow-hidden border-2 border-gray-100">
                    <img src="{{ public_path('storage/' . $photo->url_image) }}" class="w-full h-48 object-cover">
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-auto">
        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl mb-6">
            <p class="text-[9px] text-gray-500 w-2/3 leading-tight">
                * AhoraSi Auto Spa se compromete a realizar un servicio de calidad. No nos responsabilizamos por objetos de valor o fallas mecánicas ajenas al servicio.
            </p>
            <div class="text-right">
                <p class="text-2xl font-black text-[#003366]">TOTAL: S/. {{$order->price ?? 'AUN NO REGISTRADO'}}</p>
            </div>
        </div>

        <div class="flex justify-between items-end px-2 pt-4">
            <div class="w-1/2 border-b-2 border-black pb-1">
                <p class="text-[10px] font-black uppercase">Firma del Cliente</p>
            </div>
            <div class="text-right text-[10px] font-bold">
                FECHA DE IMPRESION: {{ \Carbon\Carbon::now()->format('d / m / Y') }}
            </div>
        </div>
    </div>

    <div class="absolute bottom-6 left-0 right-0 text-center text-[9px] text-gray-400">
        RUC: 20601234567 - Av. Los Incas 450, Pasco - www.ahorasi.pe
    </div>

</body>
</html>