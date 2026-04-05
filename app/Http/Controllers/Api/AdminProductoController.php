<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\ProductoImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminProductoController extends Controller
{
    public function listarProductosConImagen()
    {
        $productos = Producto::query()
            ->from('productos as p')
            ->leftJoin('categorias as c', 'p.id_categoria', '=', 'c.id_categoria')
            ->leftJoin('inventario as i', 'p.id_producto', '=', 'i.producto_id')
            ->orderBy('p.nombre', 'asc')
            ->select([
                'p.id_producto',
                'p.nombre',
                'p.descripcion',
                'p.precio_venta',
                'p.id_categoria',
                'c.nombre_categoria',
                'c.descripcion as categoria_descripcion',
                'i.cantidad_stock',
                'i.fecha_actualizacion',
            ])
            ->get();

        $imagenes = ProductoImagen::all()->keyBy('producto_master_id');

        $data = $productos->map(function ($producto) use ($imagenes) {
            $foto = $imagenes->get($producto->id_producto);

            return [
                'id_producto' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion ?? '',
                'precio_venta' => $producto->precio_venta ?? 0,
                'categoria' => $producto->id_categoria ? [
                    'id_categoria' => $producto->id_categoria,
                    'nombre_categoria' => $producto->nombre_categoria,
                    'descripcion' => $producto->categoria_descripcion,
                ] : null,
                'inventario' => [
                    'cantidad_stock' => $producto->cantidad_stock ?? 0,
                    'fecha_actualizacion' => $producto->fecha_actualizacion,
                ],
                'imagen' => $foto?->imagen,
                'imagen_url' => $foto?->imagen_url,
                // Si existe el registro toma su valor, si no existe asumimos que es visible (true)
                'activo' => $foto ? (bool) $foto->activo : true,
            ];
        });

        return response()->json([
            'ok' => true,
            'message' => 'Productos con imagen consultados correctamente',
            'total' => $data->count(),
            'data' => $data,
        ], 200);
    }

    public function detalleProducto($id)
    {
        $producto = Producto::query()
            ->from('productos as p')
            ->leftJoin('categorias as c', 'p.id_categoria', '=', 'c.id_categoria')
            ->leftJoin('inventario as i', 'p.id_producto', '=', 'i.producto_id')
            ->where('p.id_producto', $id)
            ->select([
                'p.id_producto',
                'p.nombre',
                'p.descripcion',
                'p.precio_venta',
                'p.id_categoria',
                'c.nombre_categoria',
                'c.descripcion as categoria_descripcion',
                'i.cantidad_stock',
                'i.fecha_actualizacion',
            ])
            ->first();

        if (! $producto) {
            return response()->json([
                'ok' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $foto = ProductoImagen::where('producto_master_id', $producto->id_producto)->first();

        return response()->json([
            'ok' => true,
            'data' => [
                'id_producto' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion ?? '',
                'precio_venta' => $producto->precio_venta ?? 0,
                'categoria' => $producto->id_categoria ? [
                    'id_categoria' => $producto->id_categoria,
                    'nombre_categoria' => $producto->nombre_categoria,
                    'descripcion' => $producto->categoria_descripcion,
                ] : null,
                'inventario' => [
                    'cantidad_stock' => $producto->cantidad_stock ?? 0,
                    'fecha_actualizacion' => $producto->fecha_actualizacion,
                ],
                'imagen' => $foto?->imagen,
                'imagen_url' => $foto?->imagen_url,
                'activo' => $foto ? (bool) $foto->activo : true,
            ]
        ], 200);
    }

    public function subirImagenProducto(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (! $producto) {
            return response()->json([
                'ok' => false,
                'message' => 'Producto no encontrado en BD maestra'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Archivo inválido',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('imagen');

        // Ruta real pública en Hostinger
        $carpetaDestino = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/productos';

        if (! file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0775, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-]/', '_', $producto->nombre);
        $nombreArchivo = time() . '_' . $producto->id_producto . '_' . $nombreLimpio . '.' . $extension;

        $file->move($carpetaDestino, $nombreArchivo);

        $rutaFisica = $carpetaDestino . '/' . $nombreArchivo;
        if (! file_exists($rutaFisica)) {
            return response()->json([
                'ok' => false,
                'message' => 'La imagen no se pudo guardar físicamente en el host',
                'debug' => [
                    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? null,
                    'carpetaDestino' => $carpetaDestino,
                    'archivo' => $nombreArchivo,
                ]
            ], 500);
        }

        $path = 'productos/' . $nombreArchivo;
        $imageUrl = url($path);

        $registro = ProductoImagen::updateOrCreate(
            ['producto_master_id' => $producto->id_producto],
            [
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion ?? '',
                'precio_venta' => $producto->precio_venta ?? 0,
                'imagen' => $path,
                'imagen_url' => $imageUrl,
            ]
        );

        return response()->json([
            'ok' => true,
            'message' => 'Imagen guardada correctamente',
            'data' => [
                'id' => $registro->id,
                'producto_master_id' => $registro->producto_master_id,
                'nombre' => $registro->nombre,
                'descripcion' => $registro->descripcion,
                'precio_venta' => $registro->precio_venta,
                'imagen' => $registro->imagen,
                'imagen_url' => $registro->imagen_url,
                'activo' => (bool) $registro->activo,
            ]
        ], 200);
    }

    // =========================================================
    // NUEVO MÉTODO: CAMBIAR VISIBILIDAD
    // =========================================================
    public function cambiarVisibilidad(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (! $producto) {
            return response()->json([
                'ok' => false,
                'message' => 'Producto no encontrado en BD maestra'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'activo' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Dato inválido, se esperaba un booleano.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $esActivo = $request->boolean('activo');

        // firstOrNew busca si ya existe el registro, si no, lo instancia
        $registro = ProductoImagen::firstOrNew(['producto_master_id' => $producto->id_producto]);
        
        // Actualizamos los campos necesarios sin afectar la imagen (por si ya tiene o por si no tiene)
        $registro->nombre = $producto->nombre;
        $registro->descripcion = $producto->descripcion ?? '';
        $registro->precio_venta = $producto->precio_venta ?? 0;
        $registro->activo = $esActivo;
        
        $registro->save();

        return response()->json([
            'ok' => true,
            'message' => $esActivo ? 'Producto visible en el catálogo' : 'Producto oculto exitosamente',
            'data' => [
                'producto_master_id' => $registro->producto_master_id,
                'activo' => (bool) $registro->activo,
            ]
        ], 200);
    }
}