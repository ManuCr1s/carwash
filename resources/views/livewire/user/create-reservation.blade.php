
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
                    <h3 class="text-gray-600 font-medium">Horarios disponibles para el dia</h3>
                    <div class="flex gap-2">
                         </div>
                </div>

                <div class="flex flex-col items-center">

                <!-- Día seleccionado -->
                <span class="text-[13px] font-bold text-gray-400 uppercase tracking-tighter mb-1">
                    {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l') }}
                </span>

                <span class="text-xl font-bold mb-6 text-[#0f1a26]">
                    {{ \Carbon\Carbon::parse($selectedDate)->day }}
                </span>

                    <!-- Horarios -->
                    <div class="w-full space-y-3">
                        @foreach($slots as $slot)
                            @php
                                $isPast = $this->isSlotPast($slot, $selectedDate);
                                $isOccupied = in_array($slot, $this->occupiedSlots);
                            @endphp

                            <button 
                                wire:click="$dispatch('openReservationModal', {
                                    date: '{{ $selectedDate }}',
                                    slot: '{{ $slot }}'
                                })"
                                @disabled($isPast || $isOccupied)
                                @class([
                                    'w-full py-2 px-1 border rounded text-sm font-semibold transition-all',

                                    // 🔴 Ocupado
                                    'bg-red-100 text-red-400 border-red-200 cursor-not-allowed' => $isOccupied,

                                    // ⚫ Pasado
                                    'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed opacity-50' => $isPast,

                                    // 🔵 Disponible
                                    'border-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white' => !$isPast && !$isOccupied,
                                ])
                            >
                                {{ $slot }}

                                @if($isOccupied)
                                    <span class="block text-xs">Ocupado</span>
                                @endif
                            </button>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>






           
        </div>
        <livewire:components.modals.create-reservation-modal />
    </div>
