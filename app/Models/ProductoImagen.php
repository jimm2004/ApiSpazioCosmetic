<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoImagen extends Model
{
    protected $table = 'producto_imagenes';

    protected $fillable = [
        'producto_master_id',
        'nombre',
        'descripcion',
        'precio_venta',
        'imagen',
        'imagen_url',
        'activo', // <-- Nuevo campo agregado aquí
    ];

    // Opcional pero recomendado: Asegurar que Laravel lo trate como un booleano (true/false)
    protected $casts = [
        'activo' => 'boolean',
    ];
}