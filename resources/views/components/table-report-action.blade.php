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
</div>