    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Atender Pedido') }}       
        </h2>
    </x-slot>
    <div class="flex justify-center mt-5">
        <div class="w-full max-w-5xl">
            <livewire:components.tables.response-request-table />
            <livewire:components.modals.handle-reservation-modal />
        </div>
    </div