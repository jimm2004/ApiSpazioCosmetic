<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PersonalAdministrativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class AuthController extends Controller
{
    // ====================================================================
    // REGISTRO DE CLIENTES PÚBLICO
    // users queda solo para clientes normales
    // ====================================================================
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'device_name' => ['nullable', 'string'],
        ]);

        $existeEnAdministrativo = PersonalAdministrativo::where('email', trim($request->email))->exists();

        if ($existeEnAdministrativo) {
            return response()->json([
                'ok' => false,
                'message' => 'Este correo ya está registrado en el personal administrativo.'
            ], 422);
        }

        $user = User::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'role' => 'cliente',
            'activo' => 1,
        ]);

        $token = $user->createToken($request->device_name ?? 'flutter-app')->plainTextToken;

        return response()->json([
            'ok' => true,
            'message' => 'Cliente registrado correctamente',
            'token' => $token,
            'tipo_usuario' => 'cliente',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'activo' => (bool) $user->activo,
                'tipo_usuario' => 'cliente',
            ]
        ], 201);
    }

    // ====================================================================
    // INICIO DE SESIÓN
    // Primero busca en personal_administrativo.
    // Si no existe ahí, busca en users como cliente normal.
    // ====================================================================
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string'],
        ]);

        $email = trim($request->email);

        // 1. Login para administrador / despacho
        $admin = PersonalAdministrativo::where('email', $email)->first();

        if ($admin) {
            if (! Hash::check($request->password, $admin->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Las credenciales son incorrectas.'],
                ]);
            }

            if ((int) $admin->activo !== 1) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Tu cuenta administrativa está desactivada.'
                ], 403);
            }

            $token = $admin->createToken($request->device_name ?? 'flutter-app')->plainTextToken;

            return response()->json([
                'ok' => true,
                'message' => 'Login administrativo correcto',
                'token' => $token,
                'tipo_usuario' => 'personal_administrativo',
                'user' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => $admin->role,
                    'activo' => (bool) $admin->activo,
                    'tipo_usuario' => 'personal_administrativo',
                ]
            ], 200);
        }

        // 2. Login para cliente normal
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        if ($user->role !== 'cliente') {
            return response()->json([
                'ok' => false,
                'message' => 'Esta cuenta no pertenece a clientes. Debe iniciar sesión como personal administrativo.'
            ], 403);
        }

        if (isset($user->activo) && (int) $user->activo !== 1) {
            return response()->json([
                'ok' => false,
                'message' => 'Tu cuenta está desactivada.'
            ], 403);
        }

        $token = $user->createToken($request->device_name ?? 'flutter-app')->plainTextToken;

        return response()->json([
            'ok' => true,
            'message' => 'Login correcto',
            'token' => $token,
            'tipo_usuario' => 'cliente',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'activo' => isset($user->activo) ? (bool) $user->activo : true,
                'tipo_usuario' => 'cliente',
            ]
        ], 200);
    }

    // ====================================================================
    // CERRAR SESIÓN
    // Funciona para User y PersonalAdministrativo
    // ====================================================================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Logout correcto',
        ]);
    }

    // ====================================================================
    // OBTENER DATOS DEL USUARIO ACTUAL
    // Funciona para User y PersonalAdministrativo
    // ====================================================================
    public function me(Request $request)
    {
        $user = $request->user();

        $tipoUsuario = $user instanceof PersonalAdministrativo
            ? 'personal_administrativo'
            : 'cliente';

        return response()->json([
            'ok' => true,
            'tipo_usuario' => $tipoUsuario,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'activo' => isset($user->activo) ? (bool) $user->activo : true,
                'tipo_usuario' => $tipoUsuario,
            ],
        ]);
    }

    // ====================================================================
    // ENVÍO MANUAL DE CORREO DE BIENVENIDA
    // ====================================================================
    public function sendWelcomeEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'name' => ['required', 'string'],
            'role' => ['nullable', 'string']
        ]);

        $role = $request->role ?? 'cliente';

        try {
            Mail::to($request->email)->send(new WelcomeEmail($request->name, $role));

            return response()->json([
                'ok' => true,
                'message' => 'Correo de bienvenida enviado exitosamente.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo enviar el correo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ====================================================================
    // CREAR USUARIO DESDE EL PANEL
    // cliente => users
    // administrador/despacho => personal_administrativo
    // ====================================================================
    public function createAdministrativeUser(Request $request)
    {
        $authUser = $request->user();

        if (! $authUser || $authUser->role !== 'administrador') {
            return response()->json([
                'ok' => false,
                'message' => 'No tienes permisos para crear usuarios.'
            ], 403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:cliente,despacho,administrador'],
        ]);

        $email = trim($request->email);

        $existeEnUsers = User::where('email', $email)->exists();
        $existeEnAdministrativo = PersonalAdministrativo::where('email', $email)->exists();

        if ($existeEnUsers || $existeEnAdministrativo) {
            return response()->json([
                'ok' => false,
                'message' => 'Este correo ya está registrado en el sistema.'
            ], 422);
        }

        if ($request->role === 'cliente') {
            $user = User::create([
                'name' => trim($request->name),
                'email' => $email,
                'password' => Hash::make($request->password),
                'role' => 'cliente',
                'activo' => 1,
            ]);

            $tipoUsuario = 'cliente';
            $mensaje = 'Cliente creado correctamente.';
        } else {
            $user = PersonalAdministrativo::create([
                'name' => trim($request->name),
                'email' => $email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'activo' => 1,
            ]);

            $tipoUsuario = 'personal_administrativo';
            $mensaje = 'Usuario administrativo creado correctamente.';
        }

        try {
            Mail::to($user->email)->send(new WelcomeEmail($user->name, $user->role));
        } catch (\Exception $e) {
            // No frenamos la creación si falla el correo.
        }

        return response()->json([
            'ok' => true,
            'message' => $mensaje,
            'tipo_usuario' => $tipoUsuario,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'activo' => (bool) $user->activo,
                'tipo_usuario' => $tipoUsuario,
            ]
        ], 201);
    }

    // ====================================================================
    // LISTAR USUARIOS
    // Lista clientes desde users
    // Lista admin/despacho desde personal_administrativo
    // ====================================================================
    public function listUsers(Request $request)
    {
        $authUser = $request->user();

        if (! $authUser || $authUser->role !== 'administrador') {
            return response()->json([
                'ok' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        $clientes = User::select('id', 'name', 'email', 'role', 'activo', 'created_at')
            ->where('role', 'cliente')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'activo' => isset($user->activo) ? (bool) $user->activo : true,
                    'created_at' => $user->created_at,
                    'tipo_usuario' => 'cliente',
                ];
            });

        $personalAdministrativo = PersonalAdministrativo::select('id', 'name', 'email', 'role', 'activo', 'created_at')
            ->whereIn('role', ['administrador', 'despacho'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'activo' => (bool) $user->activo,
                    'created_at' => $user->created_at,
                    'tipo_usuario' => 'personal_administrativo',
                ];
            });

        $usuarios = $personalAdministrativo
            ->merge($clientes)
            ->values();

        return response()->json([
            'ok' => true,
            'total' => $usuarios->count(),
            'users' => $usuarios,
            'clientes' => $clientes->values(),
            'personal_administrativo' => $personalAdministrativo->values(),
        ], 200);
    }

    // ====================================================================
    // ACTIVAR / DESACTIVAR USUARIO
    // Recibe tipo_usuario:
    // cliente
    // personal_administrativo
    // ====================================================================
    public function toggleUserStatus(Request $request, $id)
    {
        $authUser = $request->user();

        if (! $authUser || $authUser->role !== 'administrador') {
            return response()->json([
                'ok' => false,
                'message' => 'No tienes permisos'
            ], 403);
        }

        $request->validate([
            'activo' => ['required', 'boolean'],
            'tipo_usuario' => ['nullable', 'in:cliente,personal_administrativo'],
        ]);

        $tipoUsuario = $request->tipo_usuario;

        if ($tipoUsuario === 'cliente') {
            $user = User::where('role', 'cliente')->find($id);
        } elseif ($tipoUsuario === 'personal_administrativo') {
            $user = PersonalAdministrativo::whereIn('role', ['administrador', 'despacho'])->find($id);
        } else {
            // Compatibilidad si el frontend viejo todavía no manda tipo_usuario.
            $user = PersonalAdministrativo::whereIn('role', ['administrador', 'despacho'])->find($id);

            if (! $user) {
                $user = User::where('role', 'cliente')->find($id);
                $tipoUsuario = 'cliente';
            } else {
                $tipoUsuario = 'personal_administrativo';
            }
        }

        if (! $user) {
            return response()->json([
                'ok' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $authTipoUsuario = $authUser instanceof PersonalAdministrativo
            ? 'personal_administrativo'
            : 'cliente';

        if (
            $user->id === $authUser->id &&
            $tipoUsuario === $authTipoUsuario
        ) {
            return response()->json([
                'ok' => false,
                'message' => 'No puedes desactivar tu propia cuenta.'
            ], 400);
        }

        $user->activo = $request->boolean('activo');
        $user->save();

        return response()->json([
            'ok' => true,
            'message' => $user->activo ? 'Usuario reactivado' : 'Usuario desactivado',
            'activo' => (bool) $user->activo,
            'tipo_usuario' => $tipoUsuario,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'activo' => (bool) $user->activo,
                'tipo_usuario' => $tipoUsuario,
            ]
        ], 200);
    }
}