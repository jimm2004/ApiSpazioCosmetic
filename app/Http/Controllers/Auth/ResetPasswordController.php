<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    /**
     * Muestra la vista (el formulario HTML) cuando el usuario hace clic en el correo.
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Procesa la nueva contraseña cuando el usuario envía el formulario.
     */
    public function reset(Request $request)
    {
        // 1. Validar que los datos vengan correctos
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // 2. Laravel verifica el token y actualiza la BD
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
                event(new PasswordReset($user));
            }
        );

        // 3. Redirigir según el resultado
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('password.success');
        }

        // Si falla (token expirado o correo incorrecto), vuelve atrás con error
        return back()->withErrors(['email' => __($status)]);
    }
}