<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $connection = 'mysql_master';

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_venta',
        'id_categoria',
    ];

    public function categoria()
    {
        return $this->belongsTo(
            Categoria::class,
            'id_categoria',
            'id_categoria'
        );
    }

    public function inventario()
    {
        return $this->hasOne(
            Inventario::class,
            'producto_id',
            'id_producto'
        );
    }

    // Esta relación apunta a Apinative, porque ProductoImagen usa conexión mysql
    public function imagenes()
    {
        return $this->hasMany(
            ProductoImagen::class,
            'producto_master_id',
            'id_producto'
        );
    }
}