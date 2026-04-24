<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PersonalAdministrativo;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    // ====================================================================
    // RECUPERAR CONTRASEÑA
    // Funciona para:
    // - users = cliente
    // - personal_administrativo = administrador / despacho
    // ====================================================================
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'tipo_usuario' => ['nullable', 'in:cliente,personal_administrativo'],
        ]);

        $email = trim($request->email);

        $broker = $this->resolverBrokerPorEmail($email, $request->tipo_usuario);

        if (! $broker) {
            throw ValidationException::withMessages([
                'email' => ['No encontramos una cuenta registrada con este correo.'],
            ]);
        }

        $status = Password::broker($broker)->sendResetLink([
            'email' => $email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'ok' => true,
                'message' => 'Enlace de recuperación enviado correctamente.',
                'tipo_usuario' => $broker === 'personal_administrativo'
                    ? 'personal_administrativo'
                    : 'cliente',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    // ====================================================================
    // RESETEAR CONTRASEÑA
    // Funciona para:
    // - users = cliente
    // - personal_administrativo = administrador / despacho
    // ====================================================================
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'tipo_usuario' => ['nullable', 'in:cliente,personal_administrativo'],
        ]);

        $email = trim($request->email);

        $broker = $this->resolverBrokerPorEmail($email, $request->tipo_usuario);

        if (! $broker) {
            throw ValidationException::withMessages([
                'email' => ['No encontramos una cuenta registrada con este correo.'],
            ]);
        }

        $status = Password::broker($broker)->reset(
            [
                'email' => $email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'token' => $request->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Cerrar tokens API anteriores de Sanctum
                if (method_exists($user, 'tokens')) {
                    $user->tokens()->delete();
                }

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'ok' => true,
                'message' => 'Contraseña actualizada correctamente.',
                'tipo_usuario' => $broker === 'personal_administrativo'
                    ? 'personal_administrativo'
                    : 'cliente',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    // ====================================================================
    // RESOLVER BROKER
    // cliente usa broker: users
    // admin/despacho usa broker: personal_administrativo
    // ====================================================================
    private function resolverBrokerPorEmail(string $email, ?string $tipoUsuario = null): ?string
    {
        if ($tipoUsuario === 'cliente') {
            return User::where('email', $email)->where('role', 'cliente')->exists()
                ? 'users'
                : null;
        }

        if ($tipoUsuario === 'personal_administrativo') {
            return PersonalAdministrativo::where('email', $email)
                ->whereIn('role', ['administrador', 'despacho'])
                ->exists()
                    ? 'personal_administrativo'
                    : null;
        }

        // Igual que el login: primero revisa personal administrativo.
        if (
            PersonalAdministrativo::where('email', $email)
                ->whereIn('role', ['administrador', 'despacho'])
                ->exists()
        ) {
            return 'personal_administrativo';
        }

        if (
            User::where('email', $email)
                ->where('role', 'cliente')
                ->exists()
        ) {
            return 'users';
        }

        return null;
    }
}