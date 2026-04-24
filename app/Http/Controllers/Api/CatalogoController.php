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
            'inventario:id,producto_id,cantidad_stock,fecha_actualizacion',
            'imagenes' => function ($query) {
                $query->where('activo', 1)
                    ->orderBy('es_principal', 'desc')
                    ->orderBy('orden', 'asc')
                    ->orderBy('id', 'asc');
            }
        ])->find($id);

        if (! $producto) {
            return response()->json([
                'ok' => false,
                'message' => 'Producto no encontrado en BD maestra'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'origen' => 'mysql_master_spazio',
            'imagenes_origen' => 'mysql_apinative',
            'data' => $this->formatearProducto($producto),
        ], 200);
    }

    public function productoPorNombre($nombre)
    {
        $productos = Producto::with([
            'categoria:id_categoria,nombre_categoria,descripcion',
            'inventario:id,producto_id,cantidad_stock,fecha_actualizacion',
            'imagenes' => function ($query) {
                $query->where('activo', 1)
                    ->orderBy('es_principal', 'desc')
                    ->orderBy('orden', 'asc')
                    ->orderBy('id', 'asc');
            }
        ])
        ->where('nombre', 'like', "%{$nombre}%")
        ->orderBy('nombre', 'asc')
        ->get()
        ->filter(function ($producto) {
            return $producto->imagenes->count() > 0;
        })
        ->values();

        return response()->json([
            'ok' => true,
            'origen' => 'mysql_master_spazio',
            'imagenes_origen' => 'mysql_apinative',
            'total' => $productos->count(),
            'data' => $productos->map(function ($producto) {
                return $this->formatearProducto($producto);
            })->values(),
        ], 200);
    }

    public function listarProductos()
    {
        $productos = Producto::with([
            'categoria:id_categoria,nombre_categoria,descripcion',
            'inventario:id,producto_id,cantidad_stock,fecha_actualizacion',
            'imagenes' => function ($query) {
                $query->where('activo', 1)
                    ->orderBy('es_principal', 'desc')
                    ->orderBy('orden', 'asc')
                    ->orderBy('id', 'asc');
            }
        ])
        ->orderBy('nombre', 'asc')
        ->get()
        ->filter(function ($producto) {
            return $producto->imagenes->count() > 0;
        })
        ->values();

        return response()->json([
            'ok' => true,
            'origen' => 'mysql_master_spazio',
            'imagenes_origen' => 'mysql_apinative',
            'total' => $productos->count(),
            'data' => $productos->map(function ($producto) {
                return $this->formatearProducto($producto);
            })->values(),
        ], 200);
    }

    private function formatearProducto($producto)
    {
        $imagenes = $producto->imagenes
            ? $producto->imagenes->take(2)
            : collect();

        $imagenPrincipal = $imagenes->firstWhere('es_principal', true)
            ?? $imagenes->first();

        $precioVenta = (float) ($producto->precio_venta ?? 0);

        $precioFinal = $imagenPrincipal
            ? (float) ($imagenPrincipal->precio_final ?? $imagenPrincipal->precio_venta ?? $precioVenta)
            : $precioVenta;

        return [
            'id_producto' => $producto->id_producto,
            'nombre' => $producto->nombre,
            'descripcion' => $producto->descripcion ?? '',
            'precio_venta' => $precioVenta,
            'precio_final' => $precioFinal,

            'categoria' => $producto->categoria ? [
                'id_categoria' => $producto->categoria->id_categoria,
                'nombre_categoria' => $producto->categoria->nombre_categoria,
                'descripcion' => $producto->categoria->descripcion,
            ] : null,

            'inventario' => [
                'cantidad_stock' => optional($producto->inventario)->cantidad_stock ?? 0,
                'fecha_actualizacion' => optional($producto->inventario)->fecha_actualizacion,
            ],

            'imagen' => $imagenPrincipal?->imagen,
            'imagen_url' => $imagenPrincipal?->imagen_url,

            'imagen_principal' => $imagenPrincipal ? [
                'id' => $imagenPrincipal->id,
                'imagen' => $imagenPrincipal->imagen,
                'imagen_url' => $imagenPrincipal->imagen_url,
                'precio_venta' => (float) ($imagenPrincipal->precio_venta ?? $precioVenta),
                'precio_final' => (float) ($imagenPrincipal->precio_final ?? $imagenPrincipal->precio_venta ?? $precioVenta),
                'es_principal' => (bool) $imagenPrincipal->es_principal,
                'orden' => (int) ($imagenPrincipal->orden ?? 0),
            ] : null,

            'imagenes' => $imagenes->map(function ($imagen) use ($precioVenta) {
                return [
                    'id' => $imagen->id,
                    'producto_master_id' => $imagen->producto_master_id,
                    'nombre' => $imagen->nombre,
                    'descripcion' => $imagen->descripcion,
                    'precio_venta' => (float) ($imagen->precio_venta ?? $precioVenta),
                    'precio_final' => (float) ($imagen->precio_final ?? $imagen->precio_venta ?? $precioVenta),
                    'imagen' => $imagen->imagen,
                    'imagen_url' => $imagen->imagen_url,
                    'activo' => (bool) $imagen->activo,
                    'es_principal' => (bool) $imagen->es_principal,
                    'orden' => (int) ($imagen->orden ?? 0),
                ];
            })->values(),

            'total_imagenes' => $imagenes->count(),
            'activo' => $imagenes->count() > 0,
            'tiene_stock' => (optional($producto->inventario)->cantidad_stock ?? 0) > 0,
        ];
    }
}