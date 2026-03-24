<div>
    @if($show)
        <div class="fixed inset-0 flex items-center justify-center z-50">

            <!-- Fondo -->
            <div class="absolute inset-0 bg-black opacity-50"
                 wire:click="close"></div>

            <div class="bg-white rounded-lg shadow-lg p-6 z-10 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">
                    {{ $title }}
                </h2>
            <p class="mb-6">{{ $message }}</p>

            <div class="flex justify-end gap-2">
                <button wire:click="close"
                    class="px-4 py-2 bg-gray-300 rounded">
                    Cancelar
                </button>

                <button wire:click="confirm"
                    class="px-4 py-2 bg-red-500 text-white rounded">
                    Confirmar
                </button>
            </div>
</div>
        </div>
    @endif
</div>