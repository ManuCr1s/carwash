<div>
     <a href="{{ route('reservation.print', $model->id) }}" target="_blank"
         class="inline-flex items-center px-3 py-1.5 bg-[#0f1a26] hover:bg-gray-700 text-white text-xs font-bold uppercase rounded shadow-md mx-1">
        
        <svg viewBox="0 0 512 512" fill="currentColor" class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <path d="M400 32H112C89.9 32 72 49.9 72 72v88h368V72c0-22.1-17.9-40-40-40z" opacity=".5"></path>
            <path d="M464 160h-16l-384 0H48c-26.5 0-48 21.5-48 48v160c0 26.5 21.5 48 48 48h24v72c0 22.1 17.9 40 40 40h288c22.1 0 40-17.9 40-40v-72h24c26.5 0 48-21.5 48-48V208c0-26.5-21.5-48-48-48zM360 448H152v-72h208v72zm72-208a24 24 0 1 1-24 24 24 24 0 0 1 24-24z"></path>
        </svg>
    </a>
   <button
        class="inline-flex items-center px-3 py-1.5 bg-[#0f1a26] hover:bg-gray-700 text-white text-xs font-bold uppercase rounded shadow-md mx-1"
        onclick="@this.handle({{ $model->id }})"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
        </svg>
     
        Despachar
    </button>
    <button
        class="inline-flex items-center px-3 py-1.5 bg-[#0f1a26] hover:bg-gray-700 text-white text-xs font-bold uppercase rounded shadow-md mx-1"
        wire:click="$dispatch('openCreateModal', { id: {{ $model->id }} })"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
        </svg>
        
        Editar
    </button>
</div>