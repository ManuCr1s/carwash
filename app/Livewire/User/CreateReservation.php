<?php

namespace App\Livewire\User;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Reservation;
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
        '14:00', '15:00', '16:00'
    ];
    public function getOccupiedSlotsProperty()
    {
        return Reservation::where('date_reservation', $this->selectedDate)
            ->pluck('time_reservation')
            ->map(fn($time) => substr($time, 0, 5))
            ->toArray();
    }
    #[On('reservationCreated')]
    public function refreshComponent()
    {}
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
        $currentDate = \Carbon\Carbon::createFromDate($this->year, $this->month, 1);
        $reservations = Reservation::orderBy('time_reservation')->orderBy('time_reservation')->get();
        return view('livewire.user.create-reservation',[
            'days' => $this->generateCalendar($currentDate),
            'monthName' => $currentDate->translatedFormat('F Y')
        ])->layout('layouts.app');
    }
 /*   public function render()
    {
        return view('livewire.user.create-reservation')->layout('layouts.app');
    }*/
}
