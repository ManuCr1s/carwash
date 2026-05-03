<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    
         <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] flex flex-col">
            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Editar Vehiculo</h3>
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

                        <div>
                           <input type="text" 
                                wire:model="modelo"
                                wire:keyup="buscar('modelo')"
                                placeholder="Modelo"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 
                                    disabled:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                            @if(!empty($resultadosModelo))
                                
                               <ul class="absolute z-50 border rounded-lg mt-1 bg-white shadow max-h-40 overflow-y-auto">
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

                    <button wire:click="update"
                        class="px-5 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow">
                        Actualizar Vehiculo
                    </button>
                </div>

            </div>
         </div>
    </div>
    @endif
</div>