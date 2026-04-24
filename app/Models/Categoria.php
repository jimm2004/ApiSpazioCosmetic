<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $connection = 'mysql_master';

    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';

    public $timestamps = false;

    protected $fillable = [
        'nombre_categoria',
        'descripcion',
    ];

    public function productos()
    {
        return $this->hasMany(
            Producto::class,
            'id_categoria',
            'id_categoria'
        );
    }
}