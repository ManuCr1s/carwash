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
                   
                    <div>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-8 h-8 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-700">Desea registrar vehiculo</h4>
                            </div>
                           <select wire:model.live="vehicle_id"
                                class="w-full border rounded-lg px-3 py-2 text-sm mb-5">

                                <option value="">Seleccione un vehículo</option>

                                @foreach($vehiculos as $v)
                                    <option value="{{ $v->id }}">
                                        {{ $v->model->brand->name }} {{ $v->model->name }} - {{ $v->placa }}
                                    </option>
                                @endforeach

                                <option value="new">➕ Registrar nuevo vehículo</option>

                            </select>
                    </div>
     
                    @if($vehicle_id === 'new')
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
                           <input type="text" 
                                wire:model="marca"
                                wire:keyup="buscar('marca')"
                                placeholder="Marca"
                                
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @if(!empty($resultadosMarca))
                                
                                <ul class="absolute z-50 border rounded-lg mt-1 bg-white shadow max-h-40 overflow-y-auto">
                                    @foreach($resultadosMarca as  $id => $item)
                                        <li 
                                            wire:click="seleccionar('marca', {{ $id }}, '{{ $item }}')"
                                            class="px-3 py-2 hover:bg-blue-100 cursor-pointer pr-20">
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @error('marca') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                       <div class="relative"> 
                            <input type="text" 
                                wire:model="modelo"
                                wire:keyup="buscar('modelo')"
                                placeholder="{{ empty($marca) ? 'Primero seleccione una marca' : 'Modelo' }}"
                                @disabled(empty($marca))
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 
                                    disabled:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">

                            @if(!empty($resultadosModelo))
                                <ul 
                                    wire:key="lista-modelos-{{ count($resultadosModelo) }}" 
                                    class="absolute z-50 border rounded-lg mt-1 bg-white shadow max-h-40 overflow-y-auto w-full">
                                    @foreach($resultadosModelo as $id => $item)
                                        <li 
                                            wire:click="seleccionar('modelo', {{ $id }}, '{{ $item }}')"
                                            class="px-3 py-2 hover:bg-blue-100 cursor-pointer">
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                    </div>
                    @endif
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