<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $connection = 'mysql_master';
    protected $table = 'inventario';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'producto_id',
        'cantidad_stock',
        'fecha_actualizacion',
    ];
}