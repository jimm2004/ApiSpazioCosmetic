<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class AuthController extends Controller
{
    // ====================================================================
    // REGISTRO DE CLIENTES PÚBLICO
    // ====================================================================
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'role' => 'cliente', // Por defecto siempre es cliente
        ]);

        $token = $user->createToken($request->device_name ?? 'flutter-app')->plainTextToken;

        return response()->json([
            'ok' => true,
            'message' => 'Cliente registrado correctamente',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 201);
    }

    // ====================================================================
    // INICIO DE SESIÓN
    // ====================================================================
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $token = $user->createToken($request->device_name ?? 'flutter-app')->plainTextToken;

        return response()->json([
            'ok' => true,
            'message' => 'Login correcto',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    // ====================================================================
    // CERRAR SESIÓN
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
    // ====================================================================
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'ok' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
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
            'role' => ['nullable', 'string'] // Agregamos 'role' a la validación
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
    // CREAR USUARIO DESDE EL PANEL (Solo Admin)
    // ====================================================================
    public function createAdministrativeUser(Request $request)
    {
        $authUser = $request->user();

        // Solo administrador puede crear usuarios administrativos
        if (!$authUser || $authUser->role !== 'administrador') {
            return response()->json([
                'ok' => false,
                'message' => 'No tienes permisos para crear usuarios.'
            ], 403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:cliente,despacho,administrador'],
        ]);

        $user = User::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Intentar enviar el correo internamente
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user->name, $user->role));
        } catch (\Exception $e) {
            // Si falla el correo, no frenamos la creación del usuario
        }

        return response()->json([
            'ok' => true,
            'message' => 'Usuario creado correctamente en el sistema administrativo.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 201);
    }

    // ====================================================================
    // LISTAR USUARIOS (Solo Admin y Despacho)
    // ====================================================================
// Actualiza tu función listUsers actual para que traiga el campo 'activo'
    public function listUsers(Request $request)
    {
        $authUser = $request->user();
    
        if (!$authUser || $authUser->role !== 'administrador') {
            return response()->json(['ok' => false, 'message' => 'No tienes permisos'], 403);
        }
    
        $users = User::select('id', 'name', 'email', 'role', 'activo', 'created_at') // <-- Agregado 'activo'
            ->whereIn('role', ['administrador', 'despacho']) 
            ->orderBy('id', 'desc')
            ->get();
    
        return response()->json([
            'ok' => true,
            'users' => $users
        ], 200);
    }

    // AGREGA ESTA NUEVA FUNCIÓN AL FINAL DE TU AuthController
    public function toggleUserStatus(Request $request, $id)
    {
        $authUser = $request->user();

        if (!$authUser || $authUser->role !== 'administrador') {
            return response()->json(['ok' => false, 'message' => 'No tienes permisos'], 403);
        }

        $user = User::whereIn('role', ['administrador', 'despacho'])->find($id);

        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Usuario no encontrado'], 404);
        }

        // Evitar que el administrador se desactive a sí mismo por error
        if ($user->id === $authUser->id) {
            return response()->json(['ok' => false, 'message' => 'No puedes desactivar tu propia cuenta.'], 400);
        }

        $user->activo = $request->boolean('activo');
        $user->save();

        return response()->json([
            'ok' => true,
            'message' => $user->activo ? 'Usuario reactivado' : 'Usuario desactivado',
            'activo' => $user->activo
        ]);
    }
}