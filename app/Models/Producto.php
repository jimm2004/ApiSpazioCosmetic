<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $connection = 'mysql_master';
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'id_producto',
        'nombre',
        'descripcion',
        'precio_venta',
        'stock_minimo',
        'id_categoria',
        'estado',
        'genera_comision',
    ];
}