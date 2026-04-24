<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoDetalle extends Model
{
    protected $table = 'carrito_detalles';

    protected $fillable = [
        'carrito_id',
        'producto_master_id',
        'producto_imagen_id',
        'nombre_producto',
        'precio_unitario',
        'precio_final',
        'cantidad',
        'subtotal',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'precio_final' => 'decimal:2',
        'cantidad' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    // =========================================================
    // RELACIÓN: DETALLE PERTENECE A UN CARRITO
    // =========================================================
    public function carrito()
    {
        return $this->belongsTo(Carrito::class, 'carrito_id', 'id');
    }

    // =========================================================
    // RELACIÓN: DETALLE PERTENECE A PRODUCTO MAESTRO
    // productos.id_producto = carrito_detalles.producto_master_id
    // =========================================================
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_master_id', 'id_producto');
    }

    // =========================================================
    // RELACIÓN: DETALLE USA UNA IMAGEN/PRESENTACIÓN DEL PRODUCTO
    // producto_imagenes.id = carrito_detalles.producto_imagen_id
    // =========================================================
    public function productoImagen()
    {
        return $this->belongsTo(ProductoImagen::class, 'producto_imagen_id', 'id');
    }

    // =========================================================
    // CALCULAR SUBTOTAL DEL DETALLE
    // =========================================================
    public function calcularSubtotal()
    {
        $precio = $this->precio_final ?? $this->precio_unitario ?? 0;

        $this->subtotal = $precio * $this->cantidad;

        return $this;
    }
}