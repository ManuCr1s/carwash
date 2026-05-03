<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] flex flex-col">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Ingresar Datos de Reservacion</h3>
                    <p class="text-xs text-gray-500">
                
                    </p>
                </div>

                <button wire:click="$set('show', false)" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>
            </div>

            <!-- BODY -->
            <div class="px-6 py-6 space-y-4 overflow-y-auto">

             
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700">Ingrese Datos Personales</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                         <div>
                           <input type="text" 
                                wire:model="name"
                                placeholder="Nombres"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                           <input type="text" 
                                wire:model="lastname"
                                placeholder="Apellidos"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('lastname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>   
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-4">
                         <div>
                           <input type="email" 
                                wire:model="email"
                                placeholder="Correo Electronico"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                           <input type="text" 
                                wire:model="dni"
                                placeholder="Dni Usuario (Ej: 00000000)"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>   
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-2 mt-4">
                       <div>
                           <input type="text" 
                                wire:model="phone"
                                placeholder="Cel Usuario (Ej: 999999999)"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div> 
                    </div>

                     <div class="flex items-center gap-2 mt-4">
                        <div class="w-8 h-8 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700">Ingrese datos del vehiculo</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="md:col-span-2 mt-4">
                            <input type="text" wire:model="placa"
                                placeholder="Placa (Ej: ABC123)"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @error('placa') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="relative w-full"
                            x-data="{dropUp:false}"
                            x-init="$watch('$wire.resultadosMarca', value => {
                                if (value && Object.keys(value).length > 0) {
                                    const rect = $el.getBoundingClientRect();
                                    const spaceBelow = window.innerHeight - rect.bottom;
                                    dropUp = spaceBelow < 200; 
                                }
                            })"
                        >
                           <input type="text" 
                                wire:model="marca"
                                wire:keyup="buscar('marca')"
                                placeholder="Marca"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            @if(!empty($resultadosMarca))
                                
                                <ul class="absolute left-0 z-[100] w-full border rounded-lg bg-white shadow-2xl max-h-40 overflow-y-auto"
                                    :class="dropUp ? 'bottom-full mb-1' : 'top-full mt-1'">
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

                        <div class="relative w-full"
                            x-data="{dropUp:false}"
                            x-init="$watch('$wire.resultadosModelo', value => {
                                if (value && Object.keys(value).length > 0) {
                                    const rect = $el.getBoundingClientRect();
                                    const spaceBelow = window.innerHeight - rect.bottom;
                                    dropUp = spaceBelow < 200; 
                                }
                            })"
                        >
                           <input type="text" 
                                wire:model="modelo"
                                wire:keyup="buscar('modelo')"
                                placeholder="Modelo"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 
                                    disabled:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                            @if(!empty($resultadosModelo))
                                
                                <ul class="absolute left-0 z-[100] w-full border rounded-lg bg-white shadow-2xl max-h-40 overflow-y-auto"
                                    :class="dropUp ? 'bottom-full mb-1' : 'top-full mt-1'">
                                    @foreach($resultadosModelo as  $id => $item)
                                        <li 
                                            wire:click="seleccionar('modelo', {{ $id }}, '{{ $item }}')"
                                            class="px-3 py-2 hover:bg-blue-100 cursor-pointer pr-20">
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @error('modelo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="flex items-center gap-2 mt-4">
                        <div class="w-8 h-8 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a]">
                         <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700">Seleccione servicio y fecha de Reservacion</h4>
                    </div>

                     <div class="grid grid-cols-1 md:grid-cols-1 gap-2 mt-4">
                        <select 
                            wire:model.live="service_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-600 outline-none transition-all"
                        >
                            <option value="">Seleccione un servicio...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id}}">
                                        {{$service->name}} - {{$service->description}}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id') 
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-4">
                        <div>
                            <input type="date" 
                                wire:model="date_reservation"
                                class="w-full border rounded-lg py-2 text-sm focus:ring-2 focus:ring-blue-500 @error('reservation_date') border-red-500 @enderror">
                            @error('date_reservation') 
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <div class="flex">
                                <div class="relative flex-grow">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <input type="number" 
                                        wire:model="time_reservation"
                                        placeholder="Ej: 14"
                                        min="0" 
                                        max="23"
                                        class="w-full border border-r-0 rounded-l-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 @error('reservation_hour') border-red-500 @enderror">
                                </div>
                                
                                <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 bg-gray-50 text-gray-500 text-sm font-bold">
                                    :00
                                </span>
                            </div>
                            @error('time_reservation') 
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>   
                    </div>

                    

                    
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
                        Ingresar Usuario
                    </button>
                </div>

            </div>

        </div>

    </div>
    @endif
</div>