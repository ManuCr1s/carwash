<div class="my-2">
    <button 
        wire:click="redirectToProvider"
        wire:loading.attr="disabled"
        class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 disabled:opacity-50 transition"
    >
        @if($provider == 'google')
            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="h-4 w-4 mr-2">
        @elseif($provider == 'facebook')
            <svg class="h-4 w-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">...</svg>
        @endif

        <span wire:loading.remove>{{ $label }}</span>
        <span wire:loading>Cargando...</span>
    </button>
</div>