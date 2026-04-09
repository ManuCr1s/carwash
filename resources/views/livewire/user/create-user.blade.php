<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
            <button 
                onclick="@this.create()"
                class="inline-flex items-center px-4 py-2 bg-[#0f1a26] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Nuevo Usuario') }}
            </button>
        </div>
    </x-slot>
    <div class="flex justify-center mt-5">
        <div class="w-full max-w-5xl">
            <livewire:components.tables.list-user-table/>
            <livewire:components.modals.edit-user-modal />
            <livewire:components.modals.create-user-modal />
        </div>
    </div>
</div>
