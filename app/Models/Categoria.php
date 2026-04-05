<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $connection = 'mysql_master';
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';

    protected $fillable = ['nombre_categoria', 'descripcion'];

    public $timestamps = false;

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria', 'id_categoria');
    }

    public function bonificaciones()
    {
        return $this->hasMany(Bonificacion::class, 'categoria_id', 'id_categoria');
    }
}