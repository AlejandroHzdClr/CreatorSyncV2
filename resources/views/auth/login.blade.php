@vite(['resources/css/app.css', 'resources/js/app.js'])
<x-guest-layout>
    <div class="flex flex-col bg-white shadow-xl rounded-2xl overflow-hidden max-w-4xl w-full mx-auto my-12">

        <!-- Lado del logo -->
        <div class="bg-indigo-600 flex items-center justify-center p-10">
            <img src="{{ asset('images/CreatorsSyncLogo.png') }}" alt="Logo" class="w-44 h-44 hover:scale-110 transition-transform duration-300">
        </div>

        <!-- Lado del formulario -->
        <div class="p-8 md:p-12 bg-white">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-6">Iniciar sesión</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Usuario -->
                <div class="mb-4">
                    <x-input-label for="nombre" :value="__('Nombre de usuario')" />
                    <x-text-input id="nombre" name="nombre" type="text"
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        :value="old('nombre')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
                </div>

                <!-- Contraseña -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" name="password" type="password"
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Olvidaste contraseña + botón -->
                <div class="flex items-center justify-between mb-4">

                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg transition-colors duration-200">
                        {{ __('Entrar') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Enlace a registro -->
            <p class="text-center text-sm text-gray-600 mt-4">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800">Regístrate aquí</a>
            </p>
        </div>
    </div>
</x-guest-layout>