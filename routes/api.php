<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogoController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\AdminProductoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Auth públicas
|--------------------------------------------------------------------------
*/

// Registro solo para clientes
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::post('/login', [AuthController::class, 'login']);

// Correo de bienvenida manual
Route::post('/send-welcome-email', [AuthController::class, 'sendWelcomeEmail']);

// Recuperación de contraseña
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| Rutas protegidas con Sanctum
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Usuario autenticado
    |--------------------------------------------------------------------------
    */
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Usuarios administrativos
    |--------------------------------------------------------------------------
    */
    Route::post('/admin/usuarios', [AuthController::class, 'createAdministrativeUser']);
    Route::get('/admin/usuarios', [AuthController::class, 'listUsers']);
    Route::post('/admin/usuarios', [AuthController::class, 'createAdministrativeUser']);
    Route::post('/admin/usuarios/{id}/estado', [AuthController::class, 'toggleUserStatus']);
    /*
    |--------------------------------------------------------------------------
    | Catálogo desde la BD maestra
    |--------------------------------------------------------------------------
    */
    Route::get('/producto-test/{id}', [CatalogoController::class, 'productoTest']);
    Route::get('/producto-nombre/{nombre}', [CatalogoController::class, 'productoPorNombre']);
    Route::get('/productos-master', [CatalogoController::class, 'listarProductos']);

    /*
    |--------------------------------------------------------------------------
    | Administración de productos con imágenes y estados
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/productos', [AdminProductoController::class, 'listarProductosConImagen']);
    Route::get('/admin/productos/{id}', [AdminProductoController::class, 'detalleProducto']);
    Route::post('/admin/productos/{id}/imagen', [AdminProductoController::class, 'subirImagenProducto']);
    Route::post('/admin/productos/{id}/visibilidad', [AdminProductoController::class, 'cambiarVisibilidad']);
});