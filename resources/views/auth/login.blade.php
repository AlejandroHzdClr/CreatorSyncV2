<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="max-w-md w-full mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">{{ __('Iniciar sesión') }}</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Nombre de Usuario -->
            <div class="mb-4">
                <x-input-label for="nombre" :value="__('Nombre de usuario')" />
                <x-text-input id="nombre" class="block mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" type="text" name="nombre" :value="old('nombre')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            <!-- Contraseña -->
            <div class="mb-6">
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Recordarme -->
            <div class="flex items-center mb-6">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
                </label>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between">
                <div class="text-sm">
                    @if (Route::has('password.request'))
                        <a class="underline text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif
                </div>

                <x-primary-button class="ml-3 bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                    {{ __('Iniciar sesión') }}
                </x-primary-button>
            </div>
        </form>

        <!-- Botón para ir al registro -->
        <div class="text-center mt-6">
            <span class="text-sm text-gray-600">¿No tienes cuenta?</span>
            <a href="{{ route('register') }}" class="ml-2 text-indigo-600 hover:text-indigo-700">
                {{ __('Regístrate aquí') }}
            </a>
        </div>
    </div>
</x-guest-layout>
