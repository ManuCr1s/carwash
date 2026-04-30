<div>
    @if( $model->state_id !== 1)
        <a href="{{ route('reservation.print', $model->id) }}" target="_blank"
            class="inline-flex items-center px-3 py-1.5 bg-[#0f1a26] hover:bg-gray-700 text-white text-xs font-bold uppercase rounded shadow-md mx-1">
            
            <svg viewBox="0 0 512 512" fill="currentColor" class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                <path d="M400 32H112C89.9 32 72 49.9 72 72v88h368V72c0-22.1-17.9-40-40-40z" opacity=".5"></path>
                <path d="M464 160h-16l-384 0H48c-26.5 0-48 21.5-48 48v160c0 26.5 21.5 48 48 48h24v72c0 22.1 17.9 40 40 40h288c22.1 0 40-17.9 40-40v-72h24c26.5 0 48-21.5 48-48V208c0-26.5-21.5-48-48-48zM360 448H152v-72h208v72zm72-208a24 24 0 1 1-24 24 24 24 0 0 1 24-24z"></path>
            </svg>
        </a>
    @endif
    <button 
        type="button"
        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700' text-white text-xs font-bold uppercase rounded shadow-md transition-all duration-150 mx-1"
        onclick="
            Swal.fire({
                icon: 'warning',
                title: '¿Eliminar reserva?',
                text: 'No podra deshacer esta operacion',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, Eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete({{ $model->id }})
                }
            });
        "
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Eliminar
    </button>
</div>