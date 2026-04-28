<div>
    @php $config = $model->getStatusActionConfig(); @endphp
    <button 
        type="button"
        class="inline-flex items-center px-3 py-1.5 {{ $config->color }} text-white text-xs font-bold uppercase rounded shadow-md transition-all duration-150 mx-1"
        onclick="
            Swal.fire({
                icon: '{{ $config->icon }}',
                title: '{{ $config->swalTitle }}',
                text: '{{ $model->name }}',
                showCancelButton: true,
                confirmButtonColor: '{{ $config->confirmColor }}',
                confirmButtonText: '{{ $config->swalConfirm }}',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete({{ $model->id }})
                }
            });
        "
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            @if($config->isActive)
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            @endif
        </svg>

        {{ $config->btnText }}
    </button>
    <button
        class="inline-flex items-center px-3 py-1.5 bg-[#0f1a26] hover:bg-gray-700 text-white text-xs font-bold uppercase rounded shadow-md mx-1"
        onclick="@this.edit({{ $model->id }})"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
        </svg>
        Editar
    </button>
</div>