<div>
    <x-slot name="header">
            <div class="flex justify-between items-stretch h-16 -my-6"> 
                <div class="flex items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight ml-4">
                        {{ __('Gestión de Usuarios') }}     
                    </h2>
                </div>

                <button
                onclick="@this.create()"
                class="inline-flex items-center px-8 bg-[#0f1a26]  hover:bg-gray-800 text-white transition ease-in-out duration-150 font-bold uppercase tracking-widest">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
