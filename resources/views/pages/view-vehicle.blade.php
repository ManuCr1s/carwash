<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Panel de Vehiculos') }}
        </h2>
    </x-slot>
    <div class="flex justify-center mt-5">
        <div class="w-full max-w-5xl">
                <livewire:components.tables.list-vehicle-table />
        </div>
    </div>
</x-app-layout>