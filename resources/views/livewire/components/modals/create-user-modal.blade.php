<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] flex flex-col">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Ingresar Nuevo Usuario</h3>
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
                    @can('editar usuario')
                    <div class="flex items-center gap-2 mt-4">
                        <div class="w-8 h-8 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3l7 4v5c0 5-3.5 9-7 9s-7-4-7-9V7l7-4z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700">Seleccione Rol de Usuario</h4>
                    </div>
                    
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-2 mt-4">
                            <select 
                                id="role" 
                                wire:model="roleId" 
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option value="">-- Seleccione un Rol --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ strtoupper($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('roleId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @endcan
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