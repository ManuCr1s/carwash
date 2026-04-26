<?php

namespace App\Livewire\User;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Models\Service;
use Livewire\Attributes\On;

class CreateReservation extends Component
{
    public $month;
    public $year;
    public $selectedDate;
    public $weekDays = [];
    public $selectedTime;
    public $slots = [
        '08:00', '09:00', '10:00',
        '11:00', '12:00', '13:00',
        '14:00', '15:00', '16:00',
        '17:00', '18:00'
    ];
    public $service_id = null;
    protected $cachedService = null;

    public function getServicioActualProperty()
    {
        if (!$this->service_id) return null;

        if (!$this->cachedService) {
            $this->cachedService = Service::find($this->service_id);
        }

        return $this->cachedService;
    }
    public function getOccupiedSlotsProperty()
    {
       /*  if (!$this->service_id) {
            return [];
        }
        $servicioActual = Service::find($this->service_id); */
        $servicioActual = $this->servicioActual;
        if (!$servicioActual) return [];
        $reservasDelDia = Reservation::where('date_reservation', $this->selectedDate)
        ->whereHas('service', function($q) use ($servicioActual) {
            $q->where('group_id', $servicioActual->group_id);
        })
        ->get();

        if ($servicioActual->group_id == 2 && $reservasDelDia->count() > 0) {
            return $this->slots; 
        }

        return $reservasDelDia->pluck('time_reservation')
            ->map(fn($time) => substr($time, 0, 5))
            ->toArray();
    /*     return Reservation::where('date_reservation', $this->selectedDate)
            ->whereHas('service', function($q) use ($servicioActual) {
                    $q->where('group_id', $servicioActual->group_id);
                })
            ->pluck('time_reservation')
            ->map(fn($time) => substr($time, 0, 5))
            ->toArray(); */
    }
    public function getFinalSlotsProperty()
    {
        $servicio = $this->servicioActual; // Misma consulta que arriba
        if (!$servicio) return [];

        $occupied = $this->occupiedSlots; 
        $duracionNeeded = (int) ceil($servicio->duration);
        
        // Filtro por Grupo 2 (Salón)
        $slotsBase = collect($this->slots)->filter(function($slot) use ($servicio) {
            if ($servicio->group_id == 2) return $slot <= '10:00';
            return true;
        });

        return $slotsBase->map(function($slot, $index) use ($occupied, $duracionNeeded, $servicio) {
            $isPast = $this->isSlotPast($slot, $this->selectedDate);
            $isOccupied = in_array($slot, $occupied);
            
            $tieneEspacio = true;

            // --- LÓGICA DE BLOQUEO DIFERENCIADA ---
            
            if ($servicio->group_id == 2) {
                // Para SALÓN: Si ya hay algo ocupado hoy (tu occupiedSlots ya trae todos los slots),
                // no hay espacio. Aquí no importa la "duración" horaria, sino la disponibilidad diaria.
                if ($isOccupied) $tieneEspacio = false;
            } else {
                // Para LAVADOS: Lógica de bloques continuos (1, 2, 3 horas)
                if (!$isOccupied && !$isPast && $duracionNeeded > 1) {
                    for ($i = 1; $i < $duracionNeeded; $i++) {
                        $nextSlotTime = \Carbon\Carbon::parse($slot)->addHours($i)->format('H:i');
                        if (!in_array($nextSlotTime, $this->slots) || in_array($nextSlotTime, $occupied)) {
                            $tieneEspacio = false;
                            break;
                        }
                    }
                }
            }

            return (object) [
                'hour' => $slot,
                'is_past' => $isPast,
                'is_occupied' => $isOccupied,
                'has_space' => $tieneEspacio,
                'can_reserve' => !$isPast && !$isOccupied && $tieneEspacio
            ];
        });
    }
    #[On('reservationCreated')]
    public function refreshComponent(){}

    public function mount(){
        $this->year = $this->year ?? now()->year;
        $this->month = $this->month ?? now()->month;

        $this->selectedDate = now()->format('Y-m-d');
        $this->calculateWeek();
    }

    public function isSlotPast($slot,$columnDate)
    {
        $date = \Carbon\Carbon::parse($columnDate);
        if ($date->isPast() && !$date->isToday()) {
            return true;
        }
        if ($date->isAfter(now())) {
            return false;
        }
        $slotTime = \Carbon\Carbon::createFromFormat('H:i', $slot);
        return now()->greaterThanOrEqualTo($slotTime);
    }

    public function selectDate($date,$time = null){
        if (Carbon::parse($date)->isPast() && !Carbon::parse($date)->isToday()) {
            $this->dispatch('error-mensaje', message: 'No puedes seleccionar una fecha pasada.');
            return;
        }
        $this->selectedDate = $date;
        $this->calculateWeek();
        if ($time) {
            $this->selectedTime = $time;
            
            $fechaHumana = \Carbon\Carbon::parse($date)->translatedFormat('l d \d\e F');
            
            $this->dispatch('show-confirm-modal', [
                'message' => "¿Confirmar reserva para el {$fechaHumana} a las {$time}?",
                'humanDate'=> $fechaHumana
            ]);
        }
        
    }

    public function calculateWeek()
    {
        $startOfWeek = Carbon::parse($this->selectedDate)->startOfWeek(Carbon::MONDAY);
        $this->weekDays = [];
        
        for ($i = 0; $i < 5; $i++) { 
            $this->weekDays[] = $startOfWeek->copy()->addDays($i);
        }
    }

    private function generateCalendar(Carbon $date)
    {
        $start = $date->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $end = $date->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        $today = now()->startOfDay();

        $days = [];
        while ($start <= $end) {
            $days[] = [
                'date' => $start->format('Y-m-d'),
                'isCurrentMonth' => $start->month === (int)$this->month,
                'isToday' => $start->isToday(),
                'isPast' => $start < $today,
            ];
            $start->addDay();
        }
        return $days;
    }

    public function confirmBooking(){
        if (Carbon::parse($this->selectedDate)->isPast() && !Carbon::parse($this->selectedDate)->isToday()) {
            session()->flash('error', 'No puedes reservar en una fecha pasada.');
            return;
        }
        
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }
    public function render()
    {
       /*  $esSalon = $this->service_id && str_contains(strtolower(Service::find($this->service_id)?->name), 'salon'); */
        $esSalon = Service::find($this->service_id);
        $slotsFiltrados = collect($this->slots)->filter(function($slot) use ($esSalon) {
            if ($esSalon && $esSalon->group_id == 2) return $slot <= '10:00'; // Solo mañana para salón
            return true; 
        });
        $currentDate = \Carbon\Carbon::createFromDate($this->year, $this->month, 1);
        $reservations = Reservation::orderBy('time_reservation')->orderBy('time_reservation')->get();
        return view('livewire.user.create-reservation',[
            'days' => $this->generateCalendar($currentDate),
            'monthName' => $currentDate->translatedFormat('F Y'),
            'services' => Service::select('id','name','description')->get(),
            'availableSlots' => $this->finalSlots
        ])->layout('layouts.app');
    }

}
