<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession
        <livewire:components.buttons.social provider="google"/>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Correo Electronico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Recuerdame') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('¿Has olvidado tu contraseña?') }}
                    </a>
                @endif
                <button class="inline-flex items-center px-4 py-2 bg-[#0f1a26] border border-transparent rounded-md font-semibold text-xs text-[#e6b84a] uppercase tracking-widest hover:opacity-90 focus:bg-[#0f1a26] active:bg-[#0f1a26] focus:outline-none focus:ring-2 focus:ring-[#e6b84a] focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 ms-4">
                    {{ __('Ingresar') }}
                </button>
                        
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
