<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ResetPasswordController;

// 1. RUTA PRINCIPAL ARREGLADA (Ya no busca la vista "welcome")
Route::get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API de Spazio Cosmetic funcionando correctamente en Hostinger 🚀'
    ]);
});

// 2. Ruta para mostrar el formulario (viene del correo)
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

// 3. Ruta para procesar el guardado de la nueva contraseña
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.store');

// 4. Ruta de éxito (Mensaje final elegante)
Route::get('/password-success', function () {
    return '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contraseña Actualizada - Spazio</title>
        <style>
            body {
                background-color: #F0F4F8;
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .card {
                background: white;
                padding: 40px 30px;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.05);
                text-align: center;
                max-width: 400px;
                width: 90%;
                animation: slideUp 0.5s ease-out;
            }
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            h1 {
                color: #2D3748;
                font-size: 24px;
                margin-top: 0;
                margin-bottom: 12px;
            }
            p {
                color: #4A5568;
                font-size: 16px;
                line-height: 1.6;
                margin-bottom: 20px;
            }
            .brand {
                color: #E91E63;
                font-weight: bold;
                letter-spacing: 0.5px;
            }
            .success-icon {
                width: 70px;
                height: 70px;
                margin-bottom: 24px;
                fill: none;
                stroke: #00C853;
                stroke-width: 2.5;
                stroke-linecap: round;
                stroke-linejoin: round;
            }
            .footer-text {
                font-size: 13px;
                color: #A0AEC0;
                margin-top: 30px;
                border-top: 1px solid #EDF2F7;
                padding-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <svg class="success-icon" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            
            <h1>¡Contraseña Actualizada!</h1>
            <p>Tu nueva credencial de acceso ha sido guardada con éxito y tu cuenta está segura.</p>
            <p>Ya puedes cerrar esta ventana y volver a la aplicación <span class="brand">Spazio Cosmetics</span> para iniciar sesión.</p>
            
            <div class="footer-text">
                Puedes cerrar esta pestaña de forma segura.
            </div>
        </div>
    </body>
    </html>
    ';
})->name('password.success');