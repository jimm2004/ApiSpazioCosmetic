<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer Contraseña</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Efecto de enfoque suave en los inputs */
        input:focus {
            transform: translateY(-1px);
            transition: all 0.2s ease-in-out;
        }

        /* Animación de entrada para la tarjeta */
        .card-animate {
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Variables de color personalizadas basadas en tu imagen */
        .bg-brand-pink { background-color: #E91E63; }
        .hover\:bg-brand-pink-dark:hover { background-color: #D81B60; }
        .focus\:ring-brand-pink:focus { --tw-ring-color: #E91E63; border-color: #E91E63; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 selection:bg-pink-100 selection:text-pink-900">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <div class="card-animate w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border border-gray-100">
            
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-gray-800">Restablecer Contraseña</h2>
                <p class="text-sm text-gray-500 mt-2">Por favor, introduce tu nueva credencial de acceso.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Correo Electrónico') }}
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-brand-pink focus:border-brand-pink sm:text-sm px-4 py-2 border transition-colors" readonly>
                    
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5 relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Nueva Contraseña') }}
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-brand-pink focus:border-brand-pink sm:text-sm px-4 py-2 border transition-colors">
                    
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div id="password-strength" class="h-1.5 mt-2 rounded-full transition-all duration-300 bg-gray-200 w-0"></div>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Confirmar Contraseña') }}
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-brand-pink focus:border-brand-pink sm:text-sm px-4 py-2 border transition-colors">
                    
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-8">
                    <button type="submit" id="submitBtn" 
                        class="w-full inline-flex justify-center items-center px-4 py-3 bg-brand-pink hover:bg-brand-pink-dark border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest shadow-lg shadow-pink-200 transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 disabled:opacity-75 disabled:cursor-not-allowed">
                        <span id="btnText">{{ __('Actualizar Contraseña') }}</span>
                        <svg id="btnSpinner" class="hidden animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('password-strength');
            const form = document.getElementById('resetPasswordForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnSpinner = document.getElementById('btnSpinner');

            // 1. Validador de fuerza de contraseña visual
            passwordInput.addEventListener('input', () => {
                const val = passwordInput.value;
                let strength = 0;
                
                if (val.length > 0) strength += 20; // Algo escrito
                if (val.length >= 8) strength += 20; // Longitud aceptable
                if (val.match(/[A-Z]/)) strength += 20; // Mayúscula
                if (val.match(/[0-9]/)) strength += 20; // Número
                if (val.match(/[^a-zA-Z0-9]/)) strength += 20; // Símbolo especial

                strengthBar.style.width = strength + '%';
                
                // Cambiar color según la fuerza
                if (strength <= 40) {
                    strengthBar.style.backgroundColor = '#f87171'; // Rojo (Débil)
                } else if (strength <= 60) {
                    strengthBar.style.backgroundColor = '#fbbf24'; // Amarillo (Media)
                } else {
                    strengthBar.style.backgroundColor = '#34d399'; // Verde (Fuerte)
                }
            });

            // 2. Estado de carga en el botón al enviar
            form.addEventListener('submit', function() {
                // Deshabilitar botón para evitar doble envío
                submitBtn.disabled = true;
                // Mostrar el spinner de carga
                btnSpinner.classList.remove('hidden');
            });
        });
    </script>
</body>
</html>