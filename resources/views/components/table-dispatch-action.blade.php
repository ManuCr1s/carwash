<div>
    <button type="button" 
         class="inline-flex items-center px-3 py-1.5 bg-[#0f1a26] hover:bg-gray-700 text-white text-xs font-bold uppercase rounded shadow-md mx-1">
        
        <svg viewBox="0 0 512 512" fill="currentColor" class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <title>Imprimir Comprobante</title>
            <path d="M400 32H112C89.9 32 72 49.9 72 72v88h368V72c0-22.1-17.9-40-40-40z" opacity=".5"></path>
            <path d="M464 160h-16l-384 0H48c-26.5 0-48 21.5-48 48v160c0 26.5 21.5 48 48 48h24v72c0 22.1 17.9 40 40 40h288c22.1 0 40-17.9 40-40v-72h24c26.5 0 48-21.5 48-48V208c0-26.5-21.5-48-48-48zM360 448H152v-72h208v72zm72-208a24 24 0 1 1-24 24 24 24 0 0 1 24-24z"></path>
        </svg>
    </button>
   <button
        class="inline-flex items-center px-3 py-1.5 bg-[#0f1a26] hover:bg-gray-700 text-white text-xs font-bold uppercase rounded shadow-md mx-1"
        onclick="@this.handle({{ $model->id }})"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
        </svg>
        Despachar
    </button>
</div>