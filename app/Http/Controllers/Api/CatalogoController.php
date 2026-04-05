<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;

class CatalogoController extends Controller
{
    public function productoTest($id)
    {
        $producto = Producto::with([
            'categoria:id_categoria,nombre_categoria,descripcion',
            'inventario:id,producto_id,cantidad_stock,fecha_actualizacion'
        ])->find($id);

        if (! $producto) {
            return response()->json([
                'ok' => false,
                'message' => 'Producto no encontrado en BD maestra'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'origen' => 'mysql_master',
            'data' => [
                'id_producto' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'precio_venta' => $producto->precio_venta,
                'categoria' => $producto->categoria,
                'inventario' => [
                    'cantidad_stock' => optional($producto->inventario)->cantidad_stock ?? 0,
                    'fecha_actualizacion' => optional($producto->inventario)->fecha_actualizacion,
                ],
            ]
        ]);
    }

    public function productoPorNombre($nombre)
    {
        $productos = Producto::with([
            'categoria:id_categoria,nombre_categoria,descripcion',
            'inventario:id,producto_id,cantidad_stock,fecha_actualizacion'
        ])
        ->where('nombre', 'like', "%{$nombre}%")
        ->orderBy('nombre', 'asc')
        ->get();

        return response()->json([
            'ok' => true,
            'origen' => 'mysql_master',
            'total' => $productos->count(),
            'data' => $productos->map(function ($producto) {
                return [
                    'id_producto' => $producto->id_producto,
                    'nombre' => $producto->nombre,
                    'descripcion' => $producto->descripcion,
                    'precio_venta' => $producto->precio_venta,
                    'categoria' => $producto->categoria,
                    'inventario' => [
                        'cantidad_stock' => optional($producto->inventario)->cantidad_stock ?? 0,
                        'fecha_actualizacion' => optional($producto->inventario)->fecha_actualizacion,
                    ],
                ];
            })
        ]);
    }

    public function listarProductos()
    {
        $productos = Producto::with([
            'categoria:id_categoria,nombre_categoria,descripcion',
            'inventario:id,producto_id,cantidad_stock,fecha_actualizacion'
        ])
        ->orderBy('nombre', 'asc')
        ->get();

        return response()->json([
            'ok' => true,
            'origen' => 'mysql_master',
            'total' => $productos->count(),
            'data' => $productos->map(function ($producto) {
                return [
                    'id_producto' => $producto->id_producto,
                    'nombre' => $producto->nombre,
                    'descripcion' => $producto->descripcion,
                    'precio_venta' => $producto->precio_venta,
                    'categoria' => $producto->categoria,
                    'inventario' => [
                        'cantidad_stock' => optional($producto->inventario)->cantidad_stock ?? 0,
                        'fecha_actualizacion' => optional($producto->inventario)->fecha_actualizacion,
                    ],
                ];
            })
        ]);
    }
}