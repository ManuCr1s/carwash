<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Reservar la Servicio</h3>
                    <p class="text-xs text-gray-500">
                        {{ $date_reservation }} - {{ $time_reservation }}
                    </p>
                </div>

                <button wire:click="$set('show', false)" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>
            </div>

            <!-- BODY -->
            <div class="px-6 py-6 space-y-5">

             
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="4" stroke-width="2"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700">Datos del Vehículo</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="md:col-span-2">
                            <input type="text" wire:model="placa"
                                placeholder="Placa (Ej: ABC123)"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('placa') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <input type="text" wire:model="marca"
                                placeholder="Marca"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('marca') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <input type="text" wire:model="modelo"
                                placeholder="Modelo"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('modelo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="4" y="8" width="16" height="8" rx="2" stroke-width="2"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700">Seleccion el tipo de Servicio</h4>
                    </div>

                    <select 
                        wire:model="service_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-600 outline-none transition-all"
                    >
                            <option value="">Seleccione un servicio...</option>
                            <option value="1">Lavado Básico</option>
                            <option value="2">Lavado Completo</option>
                            <option value="3">Lavado de Salón</option>
                    </select>
                    @error('service_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

            </div>

            <!-- FOOTER -->
            <div class="flex justify-between items-center px-6 py-4 border-t">

                <!-- Info -->
                <span class="text-xs text-gray-400">
                    Verifica los datos antes de guardar
                </span>

                <div class="flex gap-2">
                    <button wire:click="$set('show', false)"
                        class="px-4 py-2 text-sm bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>

                    <button wire:click="save"
                        class="px-5 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow">
                        Realizar Reserva
                    </button>
                </div>

            </div>

        </div>

    </div>
    @endif
</div>