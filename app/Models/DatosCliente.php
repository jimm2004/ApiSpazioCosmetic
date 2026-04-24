<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosCliente extends Model
{
    protected $table = 'datos_clientes';

    protected $fillable = [
        'user_id',
        'nombres',
        'apellidos',
        'telefono',
        'direccion',
        'departamento_id',
        'municipio_id',
        'referencia',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'datos_cliente_id');
    }
}