<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoImagen extends Model
{
    protected $connection = 'mysql';

    protected $table = 'producto_imagenes';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'producto_master_id',
        'nombre',
        'descripcion',
        'precio_venta',
        'precio_final',
        'imagen',
        'imagen_url',
        'activo',
        'es_principal',
        'orden',
    ];

    protected $casts = [
        'precio_venta' => 'decimal:2',
        'precio_final' => 'decimal:2',
        'activo' => 'boolean',
        'es_principal' => 'boolean',
        'orden' => 'integer',
    ];

    public function producto()
    {
        return $this->belongsTo(
            Producto::class,
            'producto_master_id',
            'id_producto'
        );
    }
}