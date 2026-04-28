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

                    <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="flex flex-col">
                                <label class="text-[10px] font-bold mb-1 text-gray-600">FOTO {{ $i }}</label>
                                <div 
                                    wire:ignore 
                                    x-data 
                                    x-init="
                                            FilePond.create($refs.input{{ $i }}, {
                                                labelIdle: `<span class='text-xs'>{{$tags[$i]}}</span>`,
                                                imagePreviewHeight: 80,
                                                circleButtonRemoveItem: true,
                                                credits: false, {{-- Esto quita el texto de 'Powered by FilePond' --}}
                                                captureMethod: 'camera',

                                                {{-- Para la Redimension --}}
                                                allowImageResize: true,
                                                imageResizeTargetWidth: 800,
                                                imageResizeTargetHeight: 800,
                                                imageResizeMode: 'contain',
                                                imageResizeUpscale: false,
                                                
                                                {{--Para la Compresion --}}
                                                allowImageTransform: true,
                                                imageTransformOutputMimeType: 'image/webp',
                                                imageTransformOutputQuality: 75,
                                                imageTransformOutputQualityMode: 'always',

                                                server: {
                                                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                                                        @this.upload('photo{{ $i }}', file, load, error, progress)
                                                    },
                                                    revert: (uniqueFileId, load, error) => {
                                                        @this.removeUpload('photo{{ $i }}', uniqueFileId, load, error)
                                                    },
                                                },
                                            });
                                            
                                            {{-- Escuchamos el evento para limpiar FilePond al abrir el modal --}}
                                            window.addEventListener('init-filepond', event => {
                                                if (window.FilePond.find(document.querySelector('input[x-ref=\'input{{ $i }}\']'))) {
                                                    window.FilePond.find(document.querySelector('input[x-ref=\'input{{ $i }}\']')).removeFiles();
                                                }
                                            });
                                        "
                                >
                                    <input type="file" x-ref="input{{ $i }}">
                                </div>
                                @error('photo' . $i) <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                        @endfor
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-[#0f1a26] rounded-full flex items-center justify-center text-[#e6b84a]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="4" y="8" width="16" height="8" rx="2" stroke-width="2"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-700">Ingrese si hay observaciones</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                <textarea
                                        wire:model='observations'
                                        class="w-full bg-white/10 backdrop-blur-md border border-gray-300 placeholder:text-xs placeholder-gray-400 rounded-xl p-4 focus:ring-2 focus:ring-purple-400 outline-none"
                                        placeholder="Escribe aqui tu observacion"
                                ></textarea>
                                 @error('observations')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
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