@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-12">
        <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">Registro</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                @if ($errors->has('error'))
                    <div class="alert alert-danger mb-4">
                        {{ $errors->first('error') }}
                    </div>
                @endif

                <!-- Nombre -->
                <div class="mb-4">
                    <label for="nombre" class="block text-gray-600">{{ __('Nombre') }}</label>
                    <input type="text" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Correo Electrónico -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-600">{{ __('Correo Electrónico') }}</label>
                    <input type="email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-600">{{ __('Contraseña') }}</label>
                    <input type="password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" id="password" name="password" required>
                    @error('password')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-600">{{ __('Confirmar Contraseña') }}</label>
                    <input type="password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" id="password_confirmation" name="password_confirmation" required>
                </div>

                <!-- Botón de Registro -->
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition-all duration-300">
                    {{ __('Registrarse') }}
                </button>
            </form>

            <!-- Botón para ir al inicio -->
            <div class="text-center mt-6">
                <a href="/inicio" class="w-full py-3 bg-gray-200 text-gray-800 font-semibold rounded-md hover:bg-gray-300 transition-all duration-300">
                    Ir al inicio
                </a>
            </div>
        </div>
    </div>
@endsection
