<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipios';

    protected $fillable = [
        'nombre',
        'departamento_id',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function datosClientes()
    {
        return $this->hasMany(DatosCliente::class, 'municipio_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'municipio_id');
    }
}