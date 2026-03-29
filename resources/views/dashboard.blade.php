<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ auth()->user()->dashboard_title }}
        </h2>
    </x-slot>

    <div class="flex justify-center mt-5">
        <div class="w-full max-w-5xl">
                @can('ver reserva')
                    <livewire:components.tables.list-reservation-table/>
                @endcan
        </div>
    </div>
</x-app-layout>
