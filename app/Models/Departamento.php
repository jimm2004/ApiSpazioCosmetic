<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';

    protected $fillable = [
        'nombre',
        'zona_id',
    ];

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'departamento_id');
    }

    public function datosClientes()
    {
        return $this->hasMany(DatosCliente::class, 'departamento_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'departamento_id');
    }
}