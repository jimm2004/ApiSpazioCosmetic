<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $connection = 'mysql_master';

    protected $table = 'inventario';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'producto_id',
        'cantidad_stock',
        'fecha_actualizacion',
    ];

    public function producto()
    {
        return $this->belongsTo(
            Producto::class,
            'producto_id',
            'id_producto'
        );
    }
}