<x-slot name="header">
    <div class="flex justify-between items-stretch h-16 -my-6"> 
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight ml-4">
                {{ __('Despachar Reserva') }}       
            </h2>   
        </div>
    </div>
</x-slot>
    <div class="flex justify-center mt-5">
        <div class="w-full max-w-5xl">
            <livewire:components.tables.response-dispatch-table />
            <livewire:components.modals.dispatch-reservation-modal />
        </div>
    </div>