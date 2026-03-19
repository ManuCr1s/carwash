
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reserva de Horario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-b-xl border border-gray-200 shadow-sm flex flex-col md:flex-row overflow-hidden">
            
                <div class="w-full md:w-[35%] p-8 border-r border-gray-100">
                    <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a] text-xl font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-#0f1a26">Selecciona el dia de Lavado</h2>
                        <div class="flex items-center text-sm text-gray-500 gap-2">
                            <span>Escoja una fecha</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-6 mt-6">
                    <span class="text-sm font-bold text-gray-600 uppercase tracking-wider">{{ $monthName }}</span>
                    <div class="flex gap-1">
                        <button wire:click="previousMonth" class="p-2 hover:bg-gray-100 rounded-full transition text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button wire:click="nextMonth" class="p-2 hover:bg-gray-100 rounded-full transition text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                    @foreach(['L','M','M','J','V','S','D'] as $dayLabel)
                        <div class="text-[12px] font-bold text-[#0f1a26] py-2">{{ $dayLabel }}</div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7 gap-1 text-center">
                    @foreach($days as $day)
                        <button 
                            wire:click="selectDate('{{ $day['date'] }}')"
                            @disabled($day['isPast'])
                            @class([
                                'text-sm w-10 h-10 flex items-center justify-center rounded-full transition-all relative mx-auto',
                                'text-blue-600 font-bold hover:bg-blue-50' => $day['isCurrentMonth'] && !$day['isPast'],
                                'text-gray-400 pointer-events-none' => !$day['isCurrentMonth'],
                                'bg-[#0f1a26] !text-[#e6b84a] shadow-md shadow-blue-200' => $selectedDate == $day['date'],
                                'border border-[#0f1a26] !text-[#0f1a26]' => $day['isToday'] && $selectedDate != $day['date']
                            ])
                        >
                            {{ \Carbon\Carbon::parse($day['date'])->day }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="w-full md:w-[65%] p-8 bg-white">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-gray-600 font-medium">Horarios disponibles para la semana</h3>
                    <div class="flex gap-2">
                         </div>
                </div>

                <div class="grid grid-cols-5 gap-4">
                    @foreach($weekDays as $day)
                        <div class="flex flex-col items-center">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">
                                {{ $day->translatedFormat('D') }}
                            </span>
                            <span @class([
                                'text-xl font-bold mb-6',
                                'text-[#0f1a26]' => $day->format('Y-m-d') == $selectedDate,
                                'text-gray-400' => $day->format('Y-m-d') != $selectedDate
                            ])>
                                {{ $day->day }}
                            </span>

                            <div class="w-full space-y-3">
                                @php 
                                    $slots = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00']; 
                                @endphp
                                @foreach($slots as $slot)
                                    @php
                                        $isPast = $this->isSlotPast($slot, $day->format('Y-m-d'));
                                    @endphp
                                   
                                    <button 
                                        wire:click="selectDate('{{ $selectedDate }}', '{{ $slot }}')"
                                        @disabled($isPast)
                                       @class([
                                            'w-full py-2 px-1 border rounded text-sm font-semibold transition-all',
                                            // Disponible
                                            'border-blue-10 0 text-blue-600 hover:bg-blue-600 hover:text-white' => !$isPast,
                                            // Pasado (Bloqueado)
                                            'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed opacity-50' => $isPast,
                                        ])
                                    >
                                        {{ $slot }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>






           
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('livewire:init', () => {
       Livewire.on('show-confirm-modal', (event) => {
           const data = Array.isArray(event) ? event[0] : event;
           Swal.fire({
                title: '¿Confirmar reserva?',
                text: "Has seleccionado el " + data.humanDate,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, reservar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.confirmBooking();
                }
            });

       });
    });
</script>