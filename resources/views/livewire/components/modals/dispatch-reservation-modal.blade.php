<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] flex flex-col">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Atender Pedido de Cliente</h3>
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
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="4" stroke-width="2"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700">Subir Fotos del Vehiculo despues de Lavado</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div wire:ignore
                            x-data="{ pond: null }"
                            x-init="
                                    FilePond.setOptions({
                                        // Configuración Global de etiquetas en ESPAÑOL
                                        labelIdle: 'Arrastra las fotos del vehículo o <span class=\'filepond--label-action\'>Busca</span>',
                                        labelMaxFilesExceeded: 'Solo puedes subir 3 fotos',
                                        labelFileTypeNotAllowed: 'Formato de archivo inválido',
                                        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                                            resolve(type);
                                        })
                                    });

                                    pond = FilePond.create($refs.input, {
                                        // --- LIMITACIÓN ---
                                        allowMultiple: true,
                                        maxFiles: 3,
                                        required: true, // No deja enviar si no hay 3 fotos

                                        // --- VALIDACIÓN ---
                                        acceptedFileTypes: ['image/jpeg', 'image/png'],

                                        // --- COMPRESIÓN Y REDIMENSIONADO ---
                                        allowImageResize: true,
                                        imageResizeTargetWidth: 800, // Ancho máximo
                                        imageResizeMode: 'contain', // Mantener proporción
                                        allowImageTransform: true, // Activar la transformación real
                                        imageTransformOutputMimeType: 'image/jpeg', // Forzar JPEG (comprime mejor que PNG)
                                        imageTransformOutputQuality: 70, // Calidad de compresión (0 a 100). 70 es un buen balance.

                                        server: {
                                            process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                                                @this.upload('photos', file, load, error, progress)
                                            },
                                            revert: (uniqueFileId, load, error) => {
                                                @this.removeUpload('photos', uniqueFileId, load)
                                            },
                                        },
                                    });

                                    // Si Livewire borra las fotos (al guardar), reiniciamos FilePond
                                    Livewire.on('reservationSaved', () => {
                                        pond.removeFiles();
                                    });
                                "
                            >
                            <input type="file" x-ref="input">
                        </div>
                        @error('photos.*') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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

                    <button wire:click="processDispatch"
                        class="px-5 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow">
                        Atender
                    </button>
                </div>

            </div>

        </div>

    </div>
    @endif
</div>